{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@push('styles')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@section('content')
@php
use App\Models\Homepage;
use App\Models\HomepageSlide;
use App\Models\Announcement;
use App\Models\EventActivity;
use App\Models\Recruitment;

$image = Homepage::where('key', 'hero_background')->first();
$slides = HomepageSlide::where('is_active', true)->orderBy('order', 'asc')->get();
$hasSlides = $slides->count() > 0;

// Fetch top 3 latest announcements (published only)
$announcements = Announcement::where('status', 'published')
    ->orderBy('date', 'desc')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get();

// Fetch top 3 latest event activities (published only)
$eventActivities = EventActivity::where('status', 'published')
    ->orderBy('event_date', 'desc')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get();

// Fetch recruitment statistics
$totalRecruitments = Recruitment::where('status', 'published')->count();
$recentRecruitments = Recruitment::where('status', 'published')
    ->orderBy('created_at', 'desc')
    ->limit(2)
    ->get();
@endphp

<!-- SECTION 1: Hero Section -->
<div class="hero-wrapper">
    <!-- Slideshow Background - ADDED -->
    @if($hasSlides)
        <div class="hero-slideshow">
            @foreach($slides as $index => $slide)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image:  url('{{ '/storage.php?file=' . $slide->image_path }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                </div>
            @endforeach
        </div>
    @else
        <!-- Original Background Image - KEPT -->
        @if($image && $image->image_data && !empty($image->image_data))
            <div class="hero-background" style="background-image: url('data:image/png;base64,{{ $image->image_data }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
        @else
            <div class="hero-background" style="background-image: url('{{ asset('images/mazda.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
        @endif
    @endif
    
<div class="gradient-overlay-1" style="pointer-events: none;"></div>
<div class="gradient-overlay-2" style="pointer-events: none;"></div>
    
    <div class="text-container">
        <div class="shaping-title">SHAPING THE FUTURE</div>
        <div class="line-10"></div>
        <div class="lead-move-motivate">
            <div>Lead.</div>
            <div>Move.</div>
            <div>Motivate.</div>
        </div>
        <div class="quote-text">
            We are committed to delivering high-quality seating solutions that create value for our customers and comfort for everyday life.
        </div>
        <div class="line-11"></div>
        <div class="together-text">TOGETHER, WE DRIVE TOMORROW.</div>
    </div>
    
    <div class="bottom-cards">
        <div class="card-item" data-url="{{ url('/guest/about/overview') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 1.svg') }}" alt="Our Thoughts" class="card-icon-img">
            </div>
            <div class="card-title">OUR THOUGHTS</div>
        </div>
        <div class="card-item" data-url="{{ url('/guest/about/overview') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 2.svg') }}" alt="Company Profile" class="card-icon-img">
            </div>
            <div class="card-title">COMPANY PROFILE</div>
        </div>
        <div class="card-item" data-url="{{ url('/guest/about/business-introduction') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 3.svg') }}" alt="Introduction" class="card-icon-img">
            </div>
            <div class="card-title">INTRODUCTION</div>
        </div>
        <div class="card-item" data-url="{{ url('/guest/about/history') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 4.svg') }}" alt="History" class="card-icon-img">
            </div>
            <div class="card-title">HISTORY</div>
        </div>
    </div>
</div>

<!-- SECTION 2 -->
<div class="section2"
     style="background-image: url('{{ '/storage.php?file=images/Home1.png' }}');"
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;">

    <!-- diagonal transition layers -->
    <div class="diag-layer layer1"></div>
    <div class="diag-layer layer2"></div>
    <div class="diag-layer layer3"></div>

    <div class="section2-white-panel"></div>

    <div class="section2-content">

        <!-- LEFT IMAGE SIDE -->
        <div class="section2-left"></div>

        <!-- RIGHT CONTENT -->
        <div class="section2-right">

             <!-- ANNOUNCEMENTS -->
            <div class="announcements-col fade-in-up">

                <div class="section-header">
                    <img src="{{ asset('images/announcement.svg') }}" class="section-icon" alt="icon">
                    <h2 class="announcements-title">Announcements</h2>
                </div>

                <div class="underline announcements-underline"></div>

                <div class="news-cards">
                    @forelse($announcements as $announcement)
                    <div class="news-card">
                        <div class="news-date-box">
                            <span>{{ date('Y/m/d', strtotime($announcement->date)) }}</span>
                        </div>
                        <div class="news-text">
                            {{ Str::limit($announcement->title, 80) }}
                        </div>
                    </div>
                    @empty
                    <div class="news-card">
                        <div class="news-date-box">
                            <span>No Data</span>
                        </div>
                        <div class="news-text">
                            No announcements available at the moment.
                        </div>
                    </div>
                    @endforelse
                </div>

                @if($announcements->count() > 0)
                <div class="view-all-link">
                    <a href="{{ route('guest.news.announcements') }}" class="view-all-btn">
                        View All Announcements <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endif

            </div>

            <!-- MEDIA INFO -->
            <div class="mediainfo-col fade-in-up delay-1">

                <div class="section-header">
                    <img src="{{ asset('images/media.svg') }}" class="section-icon" alt="icon">
                    <h2 class="mediainfo-title">Events & Activities</h2>
                </div>

                <div class="underline mediainfo-underline"></div>

                <div class="news-cards">
                    @forelse($eventActivities as $event)
                    <div class="news-card">
                        <div class="news-date-box">
                            <span>{{ $event->event_date instanceof \Carbon\Carbon ? $event->event_date->format('Y/m/d') : \Carbon\Carbon::parse($event->event_date)->format('Y/m/d') }}</span>
                        </div>
                        <div class="news-text">
                            {{ Str::limit($event->title, 80) }}
                        </div>
                    </div>
                    @empty
                    <div class="news-card">
                        <div class="news-date-box">
                            <span>No Data</span>
                        </div>
                        <div class="news-text">
                            No media information available at the moment.
                        </div>
                    </div>
                    @endforelse
                </div>

                @if($eventActivities->count() > 0)
                <div class="view-all-link">
                    <a href="{{ route('guest.news.media-information') }}" class="view-all-btn">
                        View All Events & Activities <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

<!-- SECTION 3: RECRUITMENT -->
<div class="section3">
    <div class="section3-container">
        <!-- Background decorative element -->
        <div class="section3-bg-pattern"></div>
        
        <div class="section3-content">
            <!-- Left Side - Welcome Message & Info -->
            <div class="section3-left fade-in-up">
                <div class="recruitment-badge">
                    <img src="{{ asset('images/recruitment-icon.svg') }}" alt="Recruitment" class="recruitment-icon" 
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%233988BD\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpath d=\'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z\'/%3E%3C/svg%3E'">Join Our Team
                </div>
                
                <h2 class="section3-title">Join Our Team</h2>
                
                <div class="section3-divider"></div>
                
                <p class="section3-welcome-text">
                    We're always looking for talented individuals to join our growing family. 
                    At Toyo Seat Philippines, we believe in fostering innovation, encouraging growth, 
                    and building a workplace where every voice matters.
                </p>
                
                <!-- Recent Job Posts Preview -->
                @if($recentRecruitments->count() > 0)
                <div class="recent-jobs">
                    <h4 class="recent-jobs-title">Recent Opportunities</h4>
                    <div class="recent-jobs-list">
                        @foreach($recentRecruitments as $job)
                        <div class="recent-job-item">
                            <div class="recent-job-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="recent-job-info">
                                <div class="recent-job-title">{{ Str::limit($job->title, 50) }}</div>
                                <div class="recent-job-date">Posted: {{ $job->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Side - CTA Button -->
            <div class="section3-right fade-in-up delay-1">
                <div class="recruitment-cta-card">
                    <div class="cta-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="cta-title">Ready to make an impact?</h3>
                    <p class="cta-description">
                        Explore our current openings and find the perfect role that matches your skills and aspirations.
                    </p>
                    <a href="{{ route('guest.recruitment.information') }}" class="recruitment-cta-btn">
                        <span>View All Job Openings</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <div class="cta-footer">
                        <small>Join us in shaping the future of seating solutions</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 4: HISTORY -->
<div class="section4">
    <div class="section4-container">
        <div class="section4-content fade-in-up">
            <div class="history-badge">
                <i class="fas fa-landmark"></i>
            </div>
            <h2 class="section4-title">Our History</h2>
            <div class="section4-divider"></div>
            <p class="section4-description">
                Discover the journey, milestones, and achievements that have defined our legacy of excellence.
            </p>
            <a href="{{ route('guest.about.history') }}" class="history-cta-btn">
                <span>Explore Our Journey</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
    <script>
        // Slideshow functionality - ADDED without removing existing scripts
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.hero-slide');
            if (slides.length <= 1) return;
            
            let currentSlide = 0;
            const slideCount = slides.length;
            
            function showNextSlide() {
                // Remove active class from current slide
                slides[currentSlide].classList.remove('active');
                
                // Move to next slide
                currentSlide = (currentSlide + 1) % slideCount;
                
                // Add active class to next slide
                slides[currentSlide].classList.add('active');
            }
            
            // Change slide every 5 seconds
            setInterval(showNextSlide, 5000);
        });
    </script>
@endpush