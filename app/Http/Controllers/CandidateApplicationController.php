<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateFile;
use App\Models\CandidatePreference;
use App\Models\CandidateProfile;
use App\Models\Country;
use App\Models\JobRole;
use App\Models\Location;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CandidateApplicationController extends Controller
{
    private const STEPS = 8;

    public function show(Request $request): View
    {
        $step = max(1, min(self::STEPS, (int) $request->integer('step', 1)));
        $candidateUuid = (string) ($request->query('candidate') ?: $request->session()->get('candidate.uuid', ''));

        if ($candidateUuid === '') {
            $candidateUuid = (string) Str::uuid();
            $request->session()->put('candidate.uuid', $candidateUuid);
        }

        $candidate = Candidate::where('uuid', $candidateUuid)->first();

        if (! $candidate) {
            $candidate = new Candidate(['uuid' => $candidateUuid]);
        }

        $state = $this->stateFromCandidate($candidate);

        return view('apply', [
            'step' => $step,
            'state' => $state,
            'candidate' => $candidate,
            'roles' => JobRole::where('is_active', true)->orderBy('order')->orderBy('name')->get(),
            'locations' => Location::where('is_active', true)->orderBy('name')->get(),
            'countries' => Country::orderBy('name')->get(),
            'workingHoursOptions' => $this->workingHoursOptions(),
            'employmentTypes' => ['employed', 'self_employed', 'either'],
            'candidateUuid' => $candidateUuid,
        ]);
    }

    public function success(Request $request): View
    {
        try {
            $request->session()->forget('candidate.uuid');
        } catch (\Exception $e) {
            \Log::warning('Could not forget session on success route', ['error' => $e->getMessage()]);
        }

        return view('apply-success');
    }

    public function submit(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $step = max(1, min(self::STEPS, (int) $request->input('step', 1)));
            $candidateUuid = (string) ($request->input('candidate') ?: $request->session()->get('candidate.uuid', ''));
            if ($candidateUuid === '') {
                $candidateUuid = (string) Str::uuid();
                $request->session()->put('candidate.uuid', $candidateUuid);
            }

            $candidate = Candidate::firstOrCreate(
                ['uuid' => $candidateUuid],
                ['status' => 'draft', 'full_name' => '', 'last_name' => '', 'email' => '', 'phone' => '']
            );

            $navigate = (string) $request->input('navigate', '');
            $gotoStep = (int) $request->input('goto_step', 0);
            $goingBack = $navigate === 'back' || ($gotoStep > 0 && $gotoStep < $step);

            // If navigating back, do a lenient save and redirect
            if ($goingBack) {
                $this->saveCandidateStep($candidate, $step, $request, false);
                $target = $gotoStep > 0 ? $gotoStep : max(1, $step - 1);

                // For API requests, return JSON response
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('apply', ['step' => $target, 'candidate' => $candidateUuid]),
                        'step' => $target,
                        'candidate' => $candidateUuid
                    ]);
                }

                return redirect()->route('apply', ['step' => $target, 'candidate' => $candidateUuid]);
            }

            $validated = match ($step) {
                1 => $this->validateStepOne($request),
                2 => $this->validateStepTwo($request),
                3 => $this->validateStepThree($request),
                4 => $this->validateStepFour($request),
                5 => $this->validateStepFive($request),
                6 => $this->validateStepSix($request),
                7 => $this->validateStepSeven($request),
                default => [],
            };

            // Save validated data
            $this->saveCandidateStep($candidate, $step, $request, true, $validated);

            if ($step < self::STEPS) {
                // For API requests, return JSON response
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('apply', ['step' => $step + 1, 'candidate' => $candidateUuid]),
                        'step' => $step + 1,
                        'candidate' => $candidateUuid,
                        'message' => 'Saved. Next step ready.'
                    ]);
                }

                return redirect()->route('apply', ['step' => $step + 1, 'candidate' => $candidateUuid])->with('success', 'Saved. Next step ready.');
            }

            // Final step - mark as submitted
            return $this->finalize($request, $candidate, $candidateUuid);
        } catch (\Exception $e) {
            \Log::error('Application submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'step' => $step ?? null,
                'candidate_uuid' => $candidateUuid ?? null,
                'request_data' => $request->except(['_token', 'password'])
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while submitting your application. Please try again or contact support.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    private function finalize(Request $request, Candidate $candidate, string $candidateUuid): RedirectResponse|JsonResponse
    {
               
        // Check if candidate has required fields
        if (empty($candidate->email)) {
            \Log::error('Cannot finalize application: candidate missing email', [
                'uuid' => $candidateUuid,
                'candidate_id' => $candidate->id
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot submit application: email is required. Please go back and fill in all required fields.',
                    'redirect' => route('apply', ['step' => 1, 'candidate' => $candidateUuid])
                ], 400);
            }

            return redirect()->route('apply', ['step' => 1, 'candidate' => $candidateUuid])
                ->with('error', 'Cannot submit application: email is required. Please fill in all required fields.');
        }

        DB::transaction(function () use ($request, $candidate): void {

    
            $candidate->status = 'submitted';
            $candidate->user_id = $candidate->user_id ?: $request->user()?->id;
            $candidate->save();

            if ($request->user()) {
                $candidateRoleId = Role::where('slug', 'candidate')->value('id');
                if ($candidateRoleId && ! $request->user()->roles()->where('roles.id', $candidateRoleId)->exists()) {
                    $request->user()->roles()->attach($candidateRoleId);
                }
            }

            // Send admin notification email
            $adminEmail = env('MAIL_FROM_ADDRESS');

            if ($adminEmail) {
                Mail::to($adminEmail)->send(new \App\Mail\ApplicationSubmitted($candidate, true));
                Mail::to($candidate->email)->send(new \App\Mail\ApplicationSubmitted($candidate, false));
            }
        });

        // Push the submitted application to the external portal API (optional; log but don't fail)
        $portalResult = $this->sendToPortal($candidate);

        if (!($portalResult['ok'] ?? false)) {
            \Log::warning('Portal push failed (submission still successful locally)', $portalResult);
        }

        // For API requests, return JSON response with proper route URL
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'redirect' => route('apply.success'),
                'message' => 'Application submitted successfully!'
            ]);
        }

        // For non-API requests, redirect to success page (session cleared there)
        return redirect()->route('apply.success');
    }

    private function sendToPortal(Candidate $candidate): array
    {
        try {
            $candidate->loadMissing(['preferences', 'profile', 'jobRoles:id', 'locations:id', 'files']);

            $baseUrl = rtrim((string) env('PORTAL_API_URL', 'https://portal.curohelp.com'), '/');
            $endpoint = env('PORTAL_API_ENDPOINT', '/api/apply/submit');
            $fullUrl = $baseUrl . $endpoint;
            $token = env('PORTAL_API_TOKEN');

            $preferences = $candidate->preferences?->toArray() ?? [];
            // Convert array fields to JSON strings for portal compatibility
            if (isset($preferences['working_hours']) && is_array($preferences['working_hours'])) {
                $preferences['working_hours'] = json_encode($preferences['working_hours']);
            }

            $payload = [
                'candidate' => $candidate->toArray(),
                'preferences' => $preferences,
                'profile' => $candidate->profile?->toArray(),
                'roles' => $candidate->jobRoles->pluck('id')->values(),
                'locations' => $candidate->locations->pluck('id')->values(),
                'files' => $candidate->files->map(fn ($f) => [
                    'type' => $f->type,
                    'path' => $f->path,
                    'original_name' => $f->original_name,
                    'mime_type' => $f->mime_type,
                    'size' => $f->size,
                ])->values(),
            ];

            \Log::info('Sending application to portal', [
                'url' => $fullUrl,
                'candidate_id' => $candidate->id,
                'candidate_uuid' => $candidate->uuid,
            ]);

            $client = Http::timeout(10)->acceptJson();

            if ($token) {
                $client = $client->withToken($token);
            }

            $response = $client->post($fullUrl, $payload);

            if (! $response->successful()) {
                \Log::error('Portal API error', [
                    'url' => $fullUrl,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ]);
                return [
                    'ok' => false,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ];
            }

            \Log::info('Application sent to portal successfully', [
                'status' => $response->status(),
                'candidate_id' => $candidate->id,
            ]);

            return [
                'ok' => true,
                'status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            \Log::error('Portal send exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function saveCandidateStep(Candidate $candidate, int $step, Request $request, bool $validated = true, array $data = []): void
    {
        $request->validate([
            'candidate' => ['required', 'string'],
            'step' => ['required', 'integer'],
            'navigate' => ['nullable', 'string'],
            'goto_step' => ['nullable', 'integer'],
        ]);

        match ($step) {
            1 => $this->saveStepOne($candidate, $validated ? $data : $request->all()),
            2 => $this->saveStepTwo($candidate, $validated ? $data : $request->all()),
            3 => $this->saveStepThree($candidate, $validated ? $data : $request->all()),
            4 => $this->saveStepFour($candidate, $validated ? $data : $request->all()),
            5 => $this->saveStepFive($candidate, $validated ? $data : $request->all()),
            6 => $this->saveStepSix($candidate, $validated ? $data : $request->all()),
            7 => $this->saveStepSeven($candidate, $validated ? $data : $request->all()),
            default => null,
        };
    }

    private function saveStepOne(Candidate $candidate, array $data): void
    {
        [$lat, $lng] = $this->geocodePostcode($data['postcode'] ?? null);

        $candidate->fill([
            'full_name' => trim($data['first_name'] ?? $data['full_name'] ?? '') ?: null,
            'last_name' => trim($data['last_name'] ?? '') ?: null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'postcode' => $data['postcode'] ?? null,
            'nearest_city' => $data['nearest_city'] ?? null,
            'country' => isset($data['country']) ? substr($data['country'], 0, 50) : null,
            'driving_licence' => isset($data['driving_licence']) ? (bool)$data['driving_licence'] : false,
            'own_car' => isset($data['own_car']) ? (bool)$data['own_car'] : false,
            'lat' => $lat,
            'lng' => $lng,
            'valid_passport' => isset($data['valid_passport']) ? (bool)$data['valid_passport'] : false,
        ])->save();
    }

    private function saveStepTwo(Candidate $candidate, array $data): void
    {
        $roles = $data['roles'] ?? [];
        $candidate->jobRoles()->sync($roles);
    }

    private function saveStepThree(Candidate $candidate, array $data): void
    {
        $prefs = $candidate->preferences ?? new CandidatePreference();
        $prefs->fill([
            'candidate_id' => $candidate->id,
            'pets_preference' => $data['pets_preference'] ?? null,
            'smokers_preference' => $data['smokers_preference'] ?? null,
            'living_arrangement' => $data['living_arrangement'] ?? null,
            'unwanted' => $data['unwanted'] ?? null,
        ])->save();
    }

    private function saveStepFour(Candidate $candidate, array $data): void
    {
        $locations = $data['locations'] ?? [];
        $candidate->locations()->sync($locations);
    }

    private function saveStepFive(Candidate $candidate, array $data): void
    {
        $prefs = $candidate->preferences ?? new CandidatePreference();
        $prefs->fill([
            'candidate_id' => $candidate->id,
            'employment_type' => $data['employment_type'] ?? null,
            'working_hours' => $data['working_hours'] ?? null,
            'salary_expectation_annual' => $data['salary_expectation_annual'] ?? null,
            'salary_expectation_hourly' => $data['salary_expectation_hourly'] ?? null,
        ])->save();
    }

    private function saveStepSix(Candidate $candidate, array $data): void
    {
        $profile = $candidate->profile ?? new CandidateProfile();
        $profile->fill([
            'candidate_id' => $candidate->id,
            'overview' => $data['overview'] ?? null,
            'skills' => $data['skills'] ?? null,
            'qualifications' => $data['qualifications'] ?? null,
            'training' => $data['training'] ?? null,
            'duties_performed' => $data['duties_performed'] ?? null,
            'personal_qualities' => $data['personal_qualities'] ?? null,
        ])->save();
    }

    private function saveStepSeven(Candidate $candidate, array $data): void
    {
        foreach ($data as $type => $fileData) {
            if (!is_array($fileData) || !isset($fileData['path'])) {
                continue;
            }
            CandidateFile::updateOrCreate(
                ['candidate_id' => $candidate->id, 'type' => $type],
                [
                    'disk' => 'public',
                    'path' => $fileData['path'],
                    'original_name' => $fileData['original_name'] ?? null,
                    'mime_type' => $fileData['mime_type'] ?? null,
                    'size' => $fileData['size'] ?? null,
                ]
            );
        }
    }

    private function validateStepOne(Request $request): array
    {
        $validator = validator($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:30'],
            'postcode' => ['nullable', 'string', 'max:20'],
            'nearest_city' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string'],
            'driving_licence' => ['nullable', 'boolean'],
            'own_car' => ['nullable', 'boolean'],
            'valid_passport' => ['nullable', 'boolean'],
        ]);

        // Postcode validity check via geocoding; only when country is United Kingdom
        $validator->after(function ($validator) use ($request) {
            $postcode = $request->input('postcode');
            $country = $request->input('country');

            $isUk = in_array($country, ['United Kingdom', 'GB', 'UK'], true);

            // Only validate postcode if it's provided AND country is UK (GB)
            if (($postcode === null || $postcode === '') || ! $isUk) {
                return;
            }

            [$lat, $lng] = $this->geocodePostcode($postcode);

            if ($lat === null || $lng === null) {
                $validator->errors()->add('postcode', 'Please enter a valid UK postcode.');
            }
        });

        return $validator->validate();
    }

    private function validateStepTwo(Request $request): array
    {
        return $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', Rule::exists('job_roles', 'id')],
        ]);
    }

    private function validateStepThree(Request $request): array
    {
        return $request->validate([
            'pets_preference' => ['nullable', Rule::in(['comfortable_with_pets', 'no_pets'])],
            'smokers_preference' => ['nullable', Rule::in(['comfortable_with_smokers', 'no_smokers'])],
            'living_arrangement' => ['nullable', Rule::in(['live_in', 'live_out', 'either'])],
            'unwanted' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function validateStepFour(Request $request): array
    {
        return $request->validate([
            'locations' => ['required', 'array', 'min:1'],
            'locations.*' => ['integer', Rule::exists('locations', 'id')],
        ]);
    }

    private function validateStepFive(Request $request): array
    {
        return $request->validate([
            'employment_type' => ['nullable', Rule::in(['employed', 'self_employed', 'either'])],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*' => ['string', Rule::in($this->workingHoursOptions())],
            'salary_expectation_annual' => ['nullable', 'numeric', 'min:0'],
            'salary_expectation_hourly' => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    private function validateStepSix(Request $request): array
    {
        return $request->validate([
            'overview' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'qualifications' => ['nullable', 'string'],
            'training' => ['nullable', 'string'],
            'duties_performed' => ['nullable', 'string'],
            'personal_qualities' => ['nullable', 'string'],
        ]);
    }

    private function validateStepSeven(Request $request): array
    {
        // Validate file paths uploaded via FilePond (stored as strings in hidden inputs)
        $request->validate([
            'photo' => ['nullable', 'string'],
            'intro_video' => ['nullable', 'string'],
            'cv' => ['nullable', 'string'],
            'certificates' => ['nullable', 'string'],
            'training_documents' => ['nullable', 'string'],
            'security_checks' => ['nullable', 'string'],
        ]);

        // Return file paths as-is (already uploaded via FilePond)
        $keys = ['photo', 'intro_video', 'cv', 'certificates', 'training_documents', 'security_checks'];
        $stored = [];
        foreach ($keys as $type) {
            $path = $request->input($type);
            if (!$path) continue;

            // Parse the file info from the uploaded file
            // The path is stored in the format: uploads/temp/timestamp_filename
            $filename = basename($path);

            $stored[$type] = [
                'path' => $path,
                'original_name' => $filename,
                'mime_type' => $this->getMimeType($path),
                'size' => $this->getFileSize($path),
            ];
        }
        return $stored;
    }

    private function getMimeType(string $path): ?string
    {
        $fullPath = storage_path('app/public/' . ltrim($path, '/'));
        if (file_exists($fullPath)) {
            return mime_content_type($fullPath);
        }
        return null;
    }

    private function getFileSize(string $path): ?int
    {
        $fullPath = storage_path('app/public/' . ltrim($path, '/'));
        if (file_exists($fullPath)) {
            return filesize($fullPath);
        }
        return null;
    }

    private function mapStepToColumns(int $step, array $input): array
    {
        return match ($step) {
            1 => [
                'full_name' => $input['first_name'] ?? $input['full_name'] ?? null,
                'last_name' => $input['last_name'] ?? null,
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
                'postcode' => $input['postcode'] ?? null,
                'nearest_city' => $input['nearest_city'] ?? null,
                'lat' => $input['lat'] ?? null,
                'lng' => $input['lng'] ?? null,
                'driving_licence' => isset($input['driving_licence']) ? (bool)$input['driving_licence'] : false,
                'own_car' => isset($input['own_car']) ? (bool)$input['own_car'] : false,
                'valid_passport' => isset($input['valid_passport']) ? (bool)$input['valid_passport'] : false,
            ],
            default => [],
        };
    }

    private function geocodePostcode(?string $postcode): array
    {
        if (! $postcode) {
            return [null, null];
        }

        $clean = $this->sanitizePostcode($postcode);
        if ($clean === '') {
            return [null, null];
        }

        $url = 'https://api.postcodes.io/postcodes/' . urlencode($clean);
        $json = @file_get_contents($url);

        if ($json === false) {
            return [null, null];
        }

        $data = json_decode($json, true);

        if (!is_array($data) || ($data['status'] ?? 500) !== 200 || empty($data['result'])) {
            return [null, null];
        }

        return [
            $data['result']['latitude'] ?? null,
            $data['result']['longitude'] ?? null,
        ];
    }

    private function sanitizePostcode(string $postcode): string
    {
        $postcode = strtoupper(trim($postcode));
        // Remove any characters except letters, digits, and spaces
        $postcode = preg_replace('/[^A-Z0-9 ]/', '', $postcode) ?? '';
        // Postcodes.io accepts both spaced and unspaced; use unspaced to avoid formatting issues
        $postcode = preg_replace('/\s+/', '', $postcode) ?? '';
        return $postcode;
    }

    private function stateFromCandidate(Candidate $candidate): array
    {
        $profile = $candidate->profile;
        $prefs = $candidate->preferences;
        $files = $candidate->files;

        return [
            1 => [
                'first_name' => $candidate->full_name,
                'last_name' => $candidate->last_name,
                'full_name' => $candidate->full_name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
                'postcode' => $candidate->postcode,
                'nearest_city' => $candidate->nearest_city,
                'country' => $candidate->country,
                'country_2' => $candidate->country_2,
                'driving_licence' => $candidate->driving_licence,
                'own_car' => $candidate->own_car,
                'valid_passport' => $candidate->valid_passport,
            ],
            2 => [
                'roles' => $candidate->jobRoles->pluck('id')->toArray(),
            ],
            3 => [
                'pets_preference' => $prefs?->pets_preference,
                'smokers_preference' => $prefs?->smokers_preference,
                'living_arrangement' => $prefs?->living_arrangement,
                'unwanted' => $prefs?->unwanted,
            ],
            4 => [
                'locations' => $candidate->locations->pluck('id')->toArray(),
            ],
            5 => [
                'employment_type' => $prefs?->employment_type,
                'working_hours' => $prefs?->working_hours ?? [],
                'salary_expectation_annual' => $prefs?->salary_expectation_annual,
                'salary_expectation_hourly' => $prefs?->salary_expectation_hourly,
            ],
            6 => [
                'overview' => $profile?->overview,
                'skills' => $profile?->skills,
                'qualifications' => $profile?->qualifications,
                'training' => $profile?->training,
                'duties_performed' => $profile?->duties_performed,
                'personal_qualities' => $profile?->personal_qualities,
            ],
            7 => collect($files)->keyBy('type')->map(fn($f) => [
                'path' => $f->path,
                'original_name' => $f->original_name,
                'mime_type' => $f->mime_type,
                'size' => $f->size,
            ])->toArray(),
        ];
    }

    private function workingHoursOptions(): array
    {
        return [
            'rota',
            'day',
            'night',
            'full_time',
            'part_time',
            'seasonal',
            'temporary',
        ];
    }
}
