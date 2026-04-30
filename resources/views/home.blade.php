{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@push('styles')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <style>
        /* Slideshow styles - ADDED without removing existing styles */
        .hero-slideshow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .hero-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            z-index: 1;
        }
        
        .hero-slide.active {
            opacity: 1;
            z-index: 2;
        }
        
        
        /* Keep original hero-background as fallback */
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
    </style>
@endpush

@section('content')
@php
use App\Models\Homepage;
use App\Models\HomepageSlide;
$image = Homepage::where('key', 'hero_background')->first();
$slides = HomepageSlide::where('is_active', true)->orderBy('order', 'asc')->get();
$hasSlides = $slides->count() > 0;
@endphp

<!-- SECTION 1: Hero Section -->
<div class="hero-wrapper">
    <!-- Slideshow Background - ADDED -->
    @if($hasSlides)
        <div class="hero-slideshow">
            @foreach($slides as $index => $slide)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image: url('{{ asset('storage/' . $slide->image_path) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
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
    
    <div class="gradient-overlay-1"></div>
    <div class="gradient-overlay-2"></div>
    
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
     style="background-image: url('{{ asset('images/home1.png') }}');
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
                    @php
                        $announcements = [
                            ['date' => '2024/04/06', 'text' => 'Recruitment for 2027 graduates is now open for ongoing applications.'],
                            ['date' => '2024/02/18', 'text' => 'Our company brochure is available here.'],
                            ['date' => '2025/04/25', 'text' => 'We have opened applications for positions targeting 2026 graduates.'],
                        ];
                    @endphp

                    @foreach($announcements as $announcement)
                    <div class="news-card">
                        <div class="news-date-box">
                            <span>{{ $announcement['date'] }}</span>
                        </div>
                        <div class="news-text">
                            {{ $announcement['text'] }}
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- MEDIA INFO -->
            <div class="mediainfo-col fade-in-up delay-1">

                <div class="section-header">
                    <img src="{{ asset('images/media.svg') }}" class="section-icon" alt="icon">
                    <h2 class="mediainfo-title">Media Information</h2>
                </div>

                <div class="underline mediainfo-underline"></div>

                <div class="news-cards">
                    @php
                        $mediaItems = [
                            ['date' => '2024/07/31', 'text' => 'Our company was featured in the Chugoku Shimbun newspaper.'],
                            ['date' => '2023/03/27', 'text' => 'Signing of solar power generation PPA agreement.'],
                            ['date' => '2022/11/04', 'text' => 'Featured on RCC "E-Town Sports."'],
                        ];
                    @endphp

                    @foreach($mediaItems as $media)
                    <div class="news-card">
                        <div class="news-date-box">
                            <span>{{ $media['date'] }}</span>
                        </div>
                        <div class="news-text">
                            {{ $media['text'] }}
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

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