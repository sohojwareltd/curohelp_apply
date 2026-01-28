<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplyPageController extends Controller
{
    private const STEPS = 8;

    public function show(Request $request): View
    {
        $step = max(1, min(self::STEPS, (int) $request->integer('step', 1)));
        $candidateUuid = (string) ($request->query('candidate') ?: Str::uuid());

        try {
            $countries = Country::query()->orderBy('name')->get();
        } catch (\Throwable $e) {
            Log::warning('Failed to load countries', ['error' => $e->getMessage()]);
            $countries = collect();
        }

        $candidate = (object) [
            'uuid' => $candidateUuid,
            'full_name' => null,
            'last_name' => null,
            'email' => null,
            'phone' => null,
        ];

        return view('apply', [
            'step' => $step,
            'state' => [],
            'candidate' => $candidate,
            'roles' => collect(),
            'locations' => collect(),
            'countries' => $countries,
            'workingHoursOptions' => [],
            'employmentTypes' => ['employed', 'self_employed', 'either'],
            'candidateUuid' => $candidateUuid,
            'portalApiUrl' => config('app.portal_api_url', env('PORTAL_API_URL', 'http://127.0.0.1:8000')),
        ]);
    }

    public function success(Request $request): View
    {
        try {
            $request->session()->forget('candidate.uuid');
        } catch (\Throwable $e) {
            Log::warning('Could not forget session on success route', ['error' => $e->getMessage()]);
        }

        return view('apply-success');
    }
}
