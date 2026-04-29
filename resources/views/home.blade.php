{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@push('styles')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@section('content')
@php
use App\Models\Homepage;

// Try to get image with fallback chain
$image = Homepage::where('key', 'hero_background')->first();

// If no hero_background, try main_image
if (!$image || !$image->image_data) {
    $image = Homepage::where('key', 'main_image')->first();
}

// You can add more fallbacks if needed
// if (!$image || !$image->image_data) {
//     $image = Homepage::where('key', 'background')->first();
// }
@endphp

<!-- SECTION 1: Hero Section -->
<div class="hero-wrapper">
    <!-- Background Image - handles both base64 and regular images -->
    @if($image && $image->image_data)
        <div class="hero-background" style="background-image: url('data:image/png;base64,{{ $image->image_data }}');"></div>
    @else
        <div class="hero-background" style="background-image: url('{{ asset('images/sample8.gif') }}');"></div>
    @endif
    
    <!-- Rest of your content remains the same -->
    <!-- Gradients -->
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
        <div class="together-wrapper">
            <div class="line-11"></div>
            <div class="together-text">TOGETHER, WE DRIVE TOMORROW.</div>
        </div>
    </div>
    
    <div class="bottom-cards">
        <div class="card-item" data-url="{{ url('/about/overview') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 1.svg') }}" alt="Our Thoughts" class="card-icon-img">
            </div>
            <div class="card-title">OUR THOUGHTS</div>
        </div>
        <div class="card-item" data-url="{{ url('/about/overview') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 2.svg') }}" alt="Company Profile" class="card-icon-img">
            </div>
            <div class="card-title">COMPANY PROFILE</div>
        </div>
        <div class="card-item" data-url="{{ url('/about/business-introduction') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 3.svg') }}" alt="Introduction" class="card-icon-img">
            </div>
            <div class="card-title">INTRODUCTION</div>
        </div>
        <div class="card-item" data-url="{{ url('/about/history') }}">
            <div class="card-icon-circle">
                <img src="{{ asset('images/Group 4.svg') }}" alt="History" class="card-icon-img">
            </div>
            <div class="card-title">HISTORY</div>
        </div>
    </div>
</div>

<!-- SECTION 2: Company Profile Section with Announcements & Media -->
<div class="section2" style="background-image: url('{{ asset('images/Home1.png') }}');">
    <div class="section2-overlay"></div>
    
    <div class="section2-content">
        <!-- Announcements Column -->
        <div class="announcements-col">
            <h2 class="announcements-title">News</h2>
            <div class="underline announcements-underline"></div>
            <div class="ellipse-dot ellipse-dot-1"></div>
            
            <div class="news-cards">
                <div class="news-card">
                    <div class="news-date-box">
                        <span>2024/04/06</span>
                    </div>
                    <div class="news-text">
                        Recruitment for 2027 graduates is now open for ongoing applications.
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-date-box">
                        <span>2024/02/18</span>
                    </div>
                    <div class="news-text">
                        Our company brochure is available here.
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-date-box">
                        <span>2025/04/25</span>
                    </div>
                    <div class="news-text">
                        We have opened applications for positions targeting 2026 graduates.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Media Information Column -->
        <div class="mediainfo-col">
            <h2 class="mediainfo-title">Media Information</h2>
            <div class="underline mediainfo-underline"></div>
            <div class="ellipse-dot ellipse-dot-2"></div>
            
            <div class="news-cards">
                <div class="news-card">
                    <div class="news-date-box">
                        <span>2024/07/31</span>
                    </div>
                    <div class="news-text">
                        Our company's initiatives were featured in the Chugoku Shimbun newspaper.
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-date-box">
                        <span>2023/03/27</span>
                    </div>
                    <div class="news-text">
                        Regarding the signing of an off-site corporate PPA agreement for solar power generation.
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-date-box">
                        <span>2022/11/04</span>
                    </div>
                    <div class="news-text">
                        Our company's initiatives will be featured on RCC's "E-Town Sports."
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush