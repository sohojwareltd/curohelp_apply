@extends('layouts.site')
<style>
    /* Header always full opacity */
    header, nav, .navbar {
        opacity: 1 !important;
        background-color: rgba(255, 255, 255, 1) !important;
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes borderFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    /* Roles Grid */
    .roles-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap:80px 20px;
    }
    .role-tile {
        position: relative;
        height: 180px;
        border-radius: 14px;
        /* overflow: hidden; */
        box-shadow: 0 10px 22px rgba(0,0,0,0.08);
        transition: transform .2s ease;
    }
    .role-tile:hover { transform: translateY(-4px); }
    .role-tile__img { width: 100%; height: 100%; object-fit: cover; background: #f2f2f2; display: block; }
    .role-tile__overlay {
        position: absolute;
        left: 14px; right: 14px; top: 150px;
        background: rgba(255,255,255,0.97);
        border-radius: 10px;
        padding: 8px 12px;
        box-shadow: 0 10px 22px rgba(0,0,0,.12);
    }
    .role-tile__title { margin: 0; font-size: 13px; font-weight: 700; color: #0c0c0c; }
    .role-tile__desc { margin: 2px 0 0; font-size: 11px; color: #666; }

    /* Our Teams slider */
    .teams-wrapper { 
        position: relative; 
        margin-top: 40px; 
        padding: 0 50px;
        overflow: hidden;
    }
    .teams-track {
        display: flex;
        gap: 24px;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        padding: 10px 0 20px;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
        -webkit-overflow-scrolling: touch;
    }
    .teams-track::-webkit-scrollbar { 
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }
    .teams-track::-webkit-scrollbar-track { display: none !important; }
    .teams-track::-webkit-scrollbar-thumb { display: none !important; }
    
    .team-card {
        flex: 0 0 320px;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        scroll-snap-align: start;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.15);
    }
    
    .team-card__image-wrapper {
        position: relative;
        width: 100%;
        height: 240px;
        overflow: hidden;
        background: linear-gradient(135deg, #dbb88e 0%, #c9a982 100%);
    }
    .team-card__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .team-card:hover .team-card__img {
        transform: scale(1.05);
    }
    
    .team-card__badge {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(255,255,255,0.95);
        color: #0c0c0c;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .team-card__content {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex-grow: 1;
    }
    
    .team-card__name {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0c0c0c;
        line-height: 1.3;
    }
    
    .team-card__role {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #dbb88e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .team-card__meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }
    
    .team-card__location {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #666;
    }
    .team-card__location svg {
        width: 14px;
        height: 14px;
        fill: #999;
    }
    
    .team-card__divider {
        width: 3px;
        height: 3px;
        background: #ccc;
        border-radius: 50%;
    }
    
    .team-card__experience {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }
    
    .team-card__bio {
        margin: 8px 0 0;
        font-size: 14px;
        line-height: 1.6;
        color: #555;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .teams-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        border: 2px solid #dbb88e;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        display: grid;
        place-items: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
        color: #dbb88e;
        font-size: 20px;
        font-weight: bold;
    }
    .teams-nav:hover {
        background: #dbb88e;
        color: #fff;
        transform: translateY(-50%) scale(1.1);
    }
    .teams-nav--prev { left: 0; }
    .teams-nav--next { right: 0; }
</style>

@section('content')
    <!-- Premium Hero Section -->
    <div class="container-fluid" style="background: url('{{ asset('images/hero.webp') }}') center center / cover no-repeat; min-height: calc(100vh - 70px); display: flex; align-items: center; justify-content: center; position: relative; padding: 0;">
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0.3) 100%); z-index: 1;"></div>
        <div style="position: relative; z-index: 2; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 24px;">
            <h1 style="font-size: 56px; line-height: 1.2; margin: 0; font-weight: 800; color: #0c0c0c; letter-spacing: 0.12em; text-transform: uppercase;">PRIVATE STAFF AS A SERVICE</h1>
            <p style="font-size: 32px; line-height: 1.5; margin: 0; color: #333; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600;">MANAGE LIFE ... NOT STAFF ISSUES</p>
            <div style="display: flex; gap: 16px; margin-top: 32px; flex-wrap: wrap; justify-content: center;">
                <a class="btn" href="{{ route('apply') }}" style="display: inline-flex; gap: 8px; align-items: center;">
                    <span>Apply Now</span>
                    <span style="font-size: 16px;">→</span>
                </a>
                <a class="btn outline" href="#roles" style="display: inline-flex; gap: 8px; align-items: center;">
                    <span>Browse Roles</span>
                    <span style="font-size: 16px;">↓</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Roles Grid Section -->
    <section class="container section" id="roles" style="margin-top: 80px;">
        <div>
            <span class="pill">Roles We Offer</span>
            <h2 style="font-size: 36px; font-weight: 700; margin: 12px 0 0;">Anywhere in the world</h2>
            <p style="color: var(--muted); margin: 8px 0 0; max-width: 760px;">Explore our most requested roles. Each position is carefully vetted to ensure excellence in service.</p>
        </div>

        <div class="roles-gallery" style="margin-top: 24px;">
            @foreach($jobRoles as $role)
                <div class="role-tile">
                    <img class="role-tile__img" loading="lazy"
                         src="{{ $role->image_url && str_starts_with($role->image_url, 'http') ? $role->image_url : ($role->image_url ? Storage::url($role->image_url) : asset('images/roles/' . $role->slug . '.webp')) }}"
                         alt="{{ $role->name }}">
                    <div class="role-tile__overlay">
                        <div class="role-tile__title">{{ $role->name }}</div>
                        <div class="role-tile__desc">{{ $role->description ?? 'Experienced ' . $role->name . ' for UHNW households' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Our Teams Slider Section -->
    <section class="container section" id="teams" style="margin-top: 80px;">
        <div>
            <span class="pill">Our Teams</span>
            <h2 style="font-size: 36px; font-weight: 700; margin: 12px 0 0;">Exceptional people. Anywhere.</h2>
            <p style="color: var(--muted); margin: 8px 0 0; max-width: 760px;">Meet our featured specialists who keep estates running flawlessly.</p>
        </div>

        <div class="teams-wrapper">
            <button class="teams-nav teams-nav--prev" type="button" aria-label="Previous">‹</button>
            <div class="teams-track" id="teamsTrack">
                @foreach($featuredEmployees as $employee)
                    <div class="team-card">
                        <div class="team-card__image-wrapper">
                            <img class="team-card__img" loading="lazy"
                                 src="{{ $employee->profile_image && str_starts_with($employee->profile_image, 'http') ? $employee->profile_image : ($employee->profile_image ? Storage::url($employee->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($employee->name) . '&size=600&background=dbb88e&color=fff') }}"
                                 alt="{{ $employee->name }}">
                            @if($employee->years_of_experience > 0)
                                <div class="team-card__badge">{{ $employee->years_of_experience }}+ Years</div>
                            @endif
                        </div>
                        <div class="team-card__content">
                            <h3 class="team-card__name">{{ $employee->name }}</h3>
                            <p class="team-card__role">{{ $employee->jobRole->name }}</p>
                            <div class="team-card__meta">
                                @if($employee->city || $employee->country)
                                    <div class="team-card__location">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                        {{ $employee->city ? $employee->city . ($employee->country ? ', ' : '') : '' }}{{ $employee->country ?? '' }}
                                    </div>
                                @endif
                                @if($employee->is_available)
                                    <span class="team-card__divider"></span>
                                    <span class="team-card__experience" style="color: #22c55e; font-weight: 600;">Available Now</span>
                                @endif
                            </div>
                            @if($employee->bio)
                                <p class="team-card__bio">{{ $employee->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="teams-nav teams-nav--next" type="button" aria-label="Next">›</button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('teamsTrack');
            const prevBtn = document.querySelector('.teams-nav--prev');
            const nextBtn = document.querySelector('.teams-nav--next');
            
            if (!track || !prevBtn || !nextBtn) return;
            
            const cardWidth = 320 + 24; // card width + gap
            let autoSlideInterval;
            let isScrolling = false;
            
            // Manual navigation with smooth animation
            prevBtn.addEventListener('click', () => {
                if (!isScrolling) {
                    isScrolling = true;
                    track.scrollBy({ 
                        left: -cardWidth, 
                        behavior: 'smooth' 
                    });
                    setTimeout(() => { isScrolling = false; }, 800);
                    resetAutoSlide();
                }
            });
            
            nextBtn.addEventListener('click', () => {
                if (!isScrolling) {
                    isScrolling = true;
                    track.scrollBy({ 
                        left: cardWidth, 
                        behavior: 'smooth' 
                    });
                    setTimeout(() => { isScrolling = false; }, 800);
                    resetAutoSlide();
                }
            });
            
            // Auto-slide functionality with smooth transitions
            function startAutoSlide() {
                autoSlideInterval = setInterval(() => {
                    if (isScrolling) return;
                    
                    const maxScroll = track.scrollWidth - track.clientWidth;
                    
                    if (track.scrollLeft >= maxScroll - 10) {
                        // Smooth scroll to start
                        isScrolling = true;
                        track.scrollTo({ 
                            left: 0, 
                            behavior: 'smooth' 
                        });
                        setTimeout(() => { isScrolling = false; }, 800);
                    } else {
                        // Smooth scroll one card
                        isScrolling = true;
                        track.scrollBy({ 
                            left: cardWidth, 
                            behavior: 'smooth' 
                        });
                        setTimeout(() => { isScrolling = false; }, 800);
                    }
                }, 5000); // 5 seconds per slide (includes smooth animation)
            }
            
            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }
            
            // Start auto-slide
            startAutoSlide();
            
            // Pause on hover
            track.addEventListener('mouseenter', () => {
                clearInterval(autoSlideInterval);
            });
            
            track.addEventListener('mouseleave', () => {
                if (!isScrolling) {
                    startAutoSlide();
                }
            });
        });
    </script>
@endsection
