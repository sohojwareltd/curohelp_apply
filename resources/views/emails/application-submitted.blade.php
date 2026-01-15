<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f2f3f7;
            color: #1f2937;
        }

        .wrap {
            max-width: 640px;
            margin: 0 auto;
            padding: 32px 16px;
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(17, 24, 39, 0.08);
            border: 1px solid #e5e7eb;
        }

        .hero {
            background: radial-gradient(circle at 20% 20%, #f3e6d7, #d6b88a 45%, #c19563 90%);
            color: #0f172a;
            padding: 28px 32px;
        }

        .eyebrow {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.85);
            color: #7a5223;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .hero h1 {
            margin: 0;
            font-size: 24px;
            line-height: 1.3;
        }

        .hero p {
            margin: 10px 0 0;
            color: #2f2a25;
            font-size: 15px;
        }

        .section {
            padding: 24px 32px;
            border-bottom: 1px solid #f1f5f9;
        }

        .section:last-child {
            border-bottom: none;
        }

        .badge-row {
            margin: 8px -6px 0;
        }

        .badge {
            display: inline-block;
            margin: 6px;
            padding: 8px 14px;
            border-radius: 999px;
            background: #f9f5ef;
            color: #5b3b1e;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid #eadbc8;
        }

        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e9f7ef;
            color: #0f5132;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .rows {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 18px;
            margin-top: 12px;
        }

        .row-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .row-value {
            font-size: 15px;
            color: #111827;
            font-weight: 600;
        }

        .cta {
            display: inline-block;
            background: linear-gradient(135deg, #d7b27a, #c2915a);
            color: #0f172a;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(180, 127, 70, 0.35);
            margin-top: 12px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            padding: 18px 12px 0;
        }

        .file-list {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
        }

        .file-name {
            color: #374151;
            font-weight: 600;
        }

        .file-size {
            color: #6b7280;
            font-size: 12px;
            margin-left: 8px;
        }

        .profile-text {
            margin-top: 10px;
            padding: 12px;
            background: #f9fafb;
            border-left: 3px solid #d6b88a;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
            white-space: pre-wrap;
        }

        @media (max-width: 600px) {
            .rows {
                grid-template-columns: 1fr;
            }

            .hero,
            .section {
                padding: 20px 18px;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div class="hero">
                @if ($candidate->files->where('type', 'photo')->count())
                    @foreach ($candidate->files->where('type', 'photo') as $file)
                        <img src="{{ config('app.url') }}/storage/{{ $file->path }}" alt="{{ $file->original_name }}"
                            style="float: left; margin-right: 20px; margin-bottom: 12px; width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid rgba(255, 255, 255, 0.9);">
                    @endforeach
                @endif
                <h1>
                    @if (!empty($showAdminActions))
                        A new candidate just applied
                    @else
                        Thanks for submitting your application
                    @endif
                </h1>

            </div>

            <div class="section">
                <div class="pill">Submitted</div>
                <div class="rows">
                    <div>
                        <div class="row-label">Name</div>
                        <div class="row-value">{{ $candidate->full_name }}</div>
                    </div>
                    <div>
                        <div class="row-label">Submitted</div>
                        <div class="row-value">{{ $candidate->updated_at->format('d M Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="row-label">Email</div>
                        <div class="row-value">{{ $candidate->email }}</div>
                    </div>
                    <div>
                        <div class="row-label">Phone</div>
                        <div class="row-value">{{ $candidate->phone }}</div>
                    </div>
                    <div>
                        <div class="row-label">Nearest City</div>
                        <div class="row-value">{{ $candidate->nearest_city ?? 'Not provided' }}</div>
                    </div>
                    <div>
                        <div class="row-label">Postcode</div>
                        <div class="row-value">{{ $candidate->postcode ?? 'Not provided' }}</div>
                    </div>
                    <div>
                        <div class="row-label">Country</div>
                        <div class="row-value">{{ $candidate->country ?? 'Not provided' }}</div>
                    </div>
                </div>
            </div>



            @if ($candidate->preferences)
                <div class="section">
                    <div class="row-label" style="margin-bottom: 6px;">Remuneration &amp; Hours</div>
                    <div class="rows">
                        <div>
                            <div class="row-label">Employment</div>
                            <div class="row-value">{{ $candidate->preferences->employment_type ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="row-label">Working Hours</div>
                            <div class="row-value">
                                {{ $candidate->preferences->working_hours ? implode(', ', (array) $candidate->preferences->working_hours) : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="row-label">Annual Salary</div>
                            <div class="row-value">
                                {{ $candidate->preferences->salary_expectation_annual ? '£' . number_format((float) $candidate->preferences->salary_expectation_annual) : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="row-label">Hourly Rate</div>
                            <div class="row-value">
                                {{ $candidate->preferences->salary_expectation_hourly ? '£' . number_format((float) $candidate->preferences->salary_expectation_hourly, 2) : '—' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="row-label" style="margin-bottom: 6px;">Preferences</div>
                    <div class="rows">
                        <div>
                            <div class="row-label">Pets</div>
                            <div class="row-value">{{ $candidate->preferences->pets_preference ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="row-label">Smokers</div>
                            <div class="row-value">{{ $candidate->preferences->smokers_preference ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="row-label">Living</div>
                            <div class="row-value">{{ $candidate->preferences->living_arrangement ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="row-label">Unwanted</div>
                            <div class="row-value">{{ $candidate->preferences->unwanted ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($candidate->jobRoles->count())
                <div class="section">
                    <div class="row-label" style="margin-bottom: 6px;">Roles Interested</div>
                    <div class="badge-row">
                        @foreach ($candidate->jobRoles as $role)
                            <span class="badge">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($candidate->locations->count())
                <div class="section">
                    <div class="row-label" style="margin-bottom: 6px;">Preferred Locations</div>
                    <div class="badge-row">
                        @foreach ($candidate->locations as $location)
                            <span class="badge"
                                style="background: #eef2ff; border-color: #e0e7ff; color: #312e81;">{{ $location->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($candidate->profile)
                <div class="section">
                    <div class="row-label" style="margin-bottom: 6px;">Profile</div>
                    @if ($candidate->profile->overview)
                        <div>
                            <div class="row-label" style="margin-top: 12px;">Overview</div>
                            <div class="profile-text">{{ $candidate->profile->overview }}</div>
                        </div>
                    @endif
                    @if ($candidate->profile->skills)
                        <div>
                            <div class="row-label" style="margin-top: 12px;">Skills</div>
                            <div class="profile-text">{{ $candidate->profile->skills }}</div>
                        </div>
                    @endif
                    @if ($candidate->profile->qualifications)
                        <div>
                            <div class="row-label" style="margin-top: 12px;">Qualifications</div>
                            <div class="profile-text">{{ $candidate->profile->qualifications }}</div>
                        </div>
                    @endif
                    @if ($candidate->profile->training)
                        <div>
                            <div class="row-label" style="margin-top: 12px;">Training</div>
                            <div class="profile-text">{{ $candidate->profile->training }}</div>
                        </div>
                    @endif
                    @if ($candidate->profile->duties_performed)
                        <div>
                            <div class="row-label" style="margin-top: 12px;">Duties Performed</div>
                            <div class="profile-text">{{ $candidate->profile->duties_performed }}</div>
                        </div>
                    @endif
                    @if ($candidate->profile->personal_qualities)
                        <div>
                            <div class="row-label" style="margin-top: 12px;">Personal Qualities</div>
                            <div class="profile-text">{{ $candidate->profile->personal_qualities }}</div>
                        </div>
                    @endif
                </div>
            @endif

            @if (!empty($showAdminActions))
                <div class="section" style="text-align: center;">
                    <p style="margin: 0 0 12px; color: #475569;">Open the candidate profile to review documents, notes,
                        and take action.</p>
                    <a href="{{ config('app.url') }}/admin/candidates/{{ $candidate->id }}" class="cta">Open in
                        Dashboard</a>
                </div>
            @endif


        </div>

        <div class="footer">
            © {{ date('Y') }} Curohelp. All rights reserved.
        </div>
    </div>
</body>

</html>
