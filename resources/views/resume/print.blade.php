<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $candidate->full_name }} - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #000000;
            background: #f5f5f5;
        }
        
        .container {
            display: grid;
            grid-template-columns: 280px 1fr;
            max-width: 8.5in;
            height: 11in;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            page-break-after: always;
        }
        
        .sidebar {
            background: #f9f7f5;
            padding: 30px 20px;
            border-right: 3px solid #dbb88e;
        }
        
        .main-content {
            padding: 30px 40px;
            overflow-y: auto;
        }
        
        .photo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .photo-section img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #dbb88e;
            display: block;
            margin: 0 auto;
        }
        
        .header {
            margin-bottom: 40px;
        }
        
        .name {
            font-size: 32px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 5px;
        }
        
        .title {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .info-box {
            background: #dbb88e;
            color: #000000;
            padding: 12px;
            border-radius: 4px;
            font-size: 11px;
            line-height: 1.6;
            font-weight: 500;
        }
        
        .info-box div {
            margin-bottom: 4px;
        }
        
        .info-box div:last-child {
            margin-bottom: 0;
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .sidebar-title {
            font-size: 13px;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            border-bottom: 2px solid #dbb88e;
            padding-bottom: 8px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        
        .sidebar-content {
            font-size: 11px;
            line-height: 1.8;
            color: #333333;
        }
        
        .skill-item {
            margin-bottom: 6px;
            padding-left: 0;
        }
        
        .skill-item::before {
            content: "▪ ";
            color: #dbb88e;
            margin-right: 6px;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #000000;
            border-bottom: 2px solid #dbb88e;
            padding-bottom: 8px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .section-content {
            font-size: 11px;
            line-height: 1.6;
            color: #333333;
        }
        
        .position {
            font-weight: bold;
            color: #000000;
            margin-top: 10px;
            margin-bottom: 3px;
            font-size: 12px;
        }
        
        .date {
            font-size: 10px;
            color: #666666;
            margin-bottom: 6px;
        }
        
        .description {
            margin-bottom: 8px;
            padding-left: 15px;
        }
        
        .description::before {
            content: "• ";
            color: #dbb88e;
            margin-left: -12px;
        }
        
        .badge-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .badge {
            display: inline-block;
            background: #f5efe8;
            color: #000000;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            border: 1px solid #dbb88e;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: #333333;
        }
        
        .contact-icon {
            color: #dbb88e;
            font-weight: bold;
        }
        
        @media print {
            body {
                background: white;
            }
            
            .container {
                max-width: 100%;
                height: auto;
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            @php
                $profile = $candidate->profile;
            @endphp
            <!-- Photo -->
            <div class="photo-section">
                @if($candidate->files && $candidate->files->count() > 0)
                @php
                        $photoFile = $candidate->files->where('type', 'photo')->first() ?? $candidate->files->first();
                        @endphp
                    @if($photoFile)
                    {{-- @dd( asset('storage/' . $photoFile->path) ); --}}
                        <img src="{{ asset('storage/' . $photoFile->path) }}" alt="Photo">
                    @endif
                @endif
            </div>

            <!-- Country -->
           
        
            <!-- Skills -->
            @if($profile && $profile->skills)
                <div class="sidebar-section">
                    <div class="sidebar-title">Skills</div>
                    <div class="sidebar-content">
                        @foreach(explode(',', $profile->skills) as $skill)
                            <div class="skill-item">{{ trim($skill) }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

 
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="name">{{ $candidate->full_name }}</div>

            </div>

            <!-- Profile -->
            @if($profile && $profile->overview)
                <div class="section">
                    <div class="section-title">Profile</div>
                    <div class="section-content">
                        {{ $profile->overview }}
                    </div>
                </div>
            @endif

            <!-- Key Skills (if long list) -->
            @if($profile && $profile->key_skills && str_word_count($profile->key_skills) > 5)
                <div class="section">
                    <div class="section-title">Key skills</div>
                    <div class="section-content">
                        <div class="badge-group">
                            @foreach(explode(',', $profile->key_skills) as $skill)
                                <span class="badge">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Qualifications -->
            @if($profile && $profile->qualifications)
                <div class="section">
                    <div class="section-title">Qualifications</div>
                    <div class="section-content">
                        {{ $profile->qualifications }}
                    </div>
                </div>
            @endif

            <!-- Training Completed -->
            @if($profile && $profile->training)
                <div class="section">
                    <div class="section-title">Training Completed</div>
                    <div class="section-content">
                        {{ $profile->training }}
                    </div>
                </div>
            @endif

            <!-- Duties Performed -->
            @if($profile && $profile->duties_performed)
                <div class="section">
                    <div class="section-title">Duties Performed</div>
                    <div class="section-content">
                        {{ $profile->duties_performed }}
                    </div>
                </div>
            @endif

            <!-- Personal Qualities -->
            @if($profile && $profile->personal_qualities)
                <div class="section">
                    <div class="section-title">Personal Qualities</div>
                    <div class="section-content">
                        {{ $profile->personal_qualities }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
</html>