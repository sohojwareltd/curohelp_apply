<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $candidate->full_name }} - CV</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        .container {
            max-width: 210mm;
            height: 297mm;
            padding: 20mm;
            background: white;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .contact-info {
            font-size: 11px;
            color: #666;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .contact-info span {
            display: inline-block;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            border-left: 4px solid #2563eb;
            text-transform: uppercase;
        }
        .section-content {
            font-size: 11px;
            margin-left: 12px;
        }
        .entry {
            margin-bottom: 12px;
        }
        .entry-title {
            font-weight: bold;
            font-size: 12px;
            color: #1f2937;
        }
        .entry-subtitle {
            font-size: 10px;
            color: #666;
            font-style: italic;
            margin-bottom: 3px;
        }
        .entry-text {
            font-size: 11px;
            color: #444;
            line-height: 1.4;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }
        .skill-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            border: 1px solid #93c5fd;
        }
        .qualities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }
        .quality-item {
            background-color: #f0fdf4;
            color: #166534;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            border: 1px solid #bbf7d0;
        }
        .empty-text {
            color: #999;
            font-style: italic;
            font-size: 10px;
        }
        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 12px 0;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $candidate->full_name }}</h1>
            <div class="contact-info">
                @if($candidate->email)
                    <span>📧 {{ $candidate->email }}</span>
                @endif
                @if($candidate->phone)
                    <span>📱 {{ $candidate->phone }}</span>
                @endif
                @if($candidate->nearest_city)
                    <span>📍 {{ $candidate->nearest_city }}</span>
                @endif
                @if($candidate->postcode)
                    <span>📮 {{ $candidate->postcode }}</span>
                @endif
            </div>
        </div>

        <!-- Profile Overview (Excluded as per request) -->

        <!-- Key Skills -->
        @if($candidate->preferences && $candidate->preferences->key_skills)
            <div class="section">
                <div class="section-title">Key Skills</div>
                <div class="section-content">
                    <div class="skills-list">
                        @forelse(explode(',', $candidate->preferences->key_skills) as $skill)
                            @if(trim($skill))
                                <div class="skill-badge">{{ trim($skill) }}</div>
                            @endif
                        @empty
                            <span class="empty-text">No skills listed</span>
                        @endforelse
                    </div>
                </div>
            </div>
            <hr>
        @endif

        <!-- Qualifications -->
        @if($candidate->preferences && $candidate->preferences->qualifications)
            <div class="section">
                <div class="section-title">Qualifications</div>
                <div class="section-content">
                    @php
                        $quals = is_array($candidate->preferences->qualifications) 
                            ? $candidate->preferences->qualifications 
                            : json_decode($candidate->preferences->qualifications, true) ?? [];
                    @endphp
                    @forelse($quals as $qual)
                        @if(is_string($qual) && trim($qual))
                            <div class="entry">
                                <div class="entry-text">{{ $qual }}</div>
                            </div>
                        @elseif(is_array($qual) && !empty($qual))
                            <div class="entry">
                                @if(isset($qual['name']))
                                    <div class="entry-title">{{ $qual['name'] }}</div>
                                @endif
                                @if(isset($qual['grade']))
                                    <div class="entry-text">Grade: {{ $qual['grade'] }}</div>
                                @endif
                                @if(isset($qual['institution']))
                                    <div class="entry-text">{{ $qual['institution'] }}</div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <span class="empty-text">No qualifications listed</span>
                    @endforelse
                </div>
            </div>
            <hr>
        @endif

        <!-- Training Completed -->
        @if($candidate->preferences && $candidate->preferences->training_completed)
            <div class="section">
                <div class="section-title">Training Completed</div>
                <div class="section-content">
                    @php
                        $trainings = is_array($candidate->preferences->training_completed) 
                            ? $candidate->preferences->training_completed 
                            : json_decode($candidate->preferences->training_completed, true) ?? [];
                    @endphp
                    @forelse($trainings as $training)
                        @if(is_string($training) && trim($training))
                            <div class="entry">
                                <div class="entry-text">{{ $training }}</div>
                            </div>
                        @elseif(is_array($training) && !empty($training))
                            <div class="entry">
                                @if(isset($training['name']))
                                    <div class="entry-title">{{ $training['name'] }}</div>
                                @endif
                                @if(isset($training['provider']))
                                    <div class="entry-text">Provider: {{ $training['provider'] }}</div>
                                @endif
                                @if(isset($training['date']))
                                    <div class="entry-text">Date: {{ $training['date'] }}</div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <span class="empty-text">No training listed</span>
                    @endforelse
                </div>
            </div>
            <hr>
        @endif

        <!-- Duties Performed -->
        @if($candidate->preferences && $candidate->preferences->duties_performed)
            <div class="section">
                <div class="section-title">Duties Performed</div>
                <div class="section-content">
                    @php
                        $duties = is_array($candidate->preferences->duties_performed) 
                            ? $candidate->preferences->duties_performed 
                            : json_decode($candidate->preferences->duties_performed, true) ?? [];
                    @endphp
                    @forelse($duties as $duty)
                        @if(is_string($duty) && trim($duty))
                            <div class="entry">
                                <div class="entry-text">• {{ $duty }}</div>
                            </div>
                        @elseif(is_array($duty) && !empty($duty))
                            <div class="entry">
                                @if(isset($duty['title']))
                                    <div class="entry-title">{{ $duty['title'] }}</div>
                                @endif
                                @if(isset($duty['description']))
                                    <div class="entry-text">{{ $duty['description'] }}</div>
                                @endif
                                @if(isset($duty['duration']))
                                    <div class="entry-text">Duration: {{ $duty['duration'] }}</div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <span class="empty-text">No duties listed</span>
                    @endforelse
                </div>
            </div>
            <hr>
        @endif

        <!-- Personal Qualities -->
        @if($candidate->preferences && $candidate->preferences->personal_qualities)
            <div class="section">
                <div class="section-title">Personal Qualities</div>
                <div class="section-content">
                    @php
                        $qualities = is_array($candidate->preferences->personal_qualities) 
                            ? $candidate->preferences->personal_qualities 
                            : json_decode($candidate->preferences->personal_qualities, true) ?? [];
                    @endphp
                    <div class="qualities-list">
                        @forelse($qualities as $quality)
                            @if(is_string($quality) && trim($quality))
                                <div class="quality-item">{{ trim($quality) }}</div>
                            @endif
                        @empty
                            <span class="empty-text">No personal qualities listed</span>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
