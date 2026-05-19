{{-- resources/views/guest/about/business-introduction.blade.php --}}
@extends('layouts.app')

@section('title', 'Business Introduction - About Us')

@section('content')
<style>
    /* Hero Section Styles - Copied from overview blade */
    .hero-section-wrapper {
        background: linear-gradient(135deg, #0A2640 0%, #0E334C 50%, #1A4D6F 100%);
        position: relative;
        overflow: hidden;
        margin-top: -1.5rem;
        padding: 2rem 0 0.5rem 0;
        isolation: isolate;
    }

    .hero-particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(255,255,255,0.08) 2px, transparent 2px),
            radial-gradient(circle at 80% 70%, rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 50px 50px, 30px 30px;
        pointer-events: none;
    }

    .hero-section-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 140%;
        height: 140%;
        background: radial-gradient(circle, rgba(57, 136, 189, 0.15) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
        pointer-events: none;
    }

    .hero-section-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #3A8EB5, #1A6D8F, #3A8EB5, transparent);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .hero-section {
        position: relative;
        z-index: 2;
        padding: 0.5rem 0;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(12px);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.4rem 1.5rem;
        border-radius: 50px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid rgba(255, 255, 255, 0.25);
        margin-bottom: 1.5rem;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .hero-badge:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-top: 1rem;
        margin-bottom: 0.75rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.02em;
        background: linear-gradient(135deg, #FFFFFF, #E0F0F8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-line {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 1rem auto;
    }

    .hero-line-main {
        width: 50px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #3A8EB5, #3A8EB5, transparent);
    }

    .hero-line-dot {
        width: 8px;
        height: 8px;
        background: #3A8EB5;
        border-radius: 50%;
        box-shadow: 0 0 12px rgba(91, 163, 212, 0.6);
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
        margin: 0 auto;
        font-weight: 400;
        line-height: 1.5;
    }

    .hero-scroll-indicator {
        margin-top: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .hero-scroll-indicator:hover {
        transform: translateY(3px);
    }

    .scroll-text {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 500;
    }

    .hero-scroll-indicator i {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(8px); }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    /* Original Business Introduction Styles - Updated for centering */
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #0E334C;
        position: relative;
        padding-bottom: 15px;
    }
    
    /* Center alignment for section titles */
    .section-title.text-center,
    .text-center .section-title {
        text-align: center;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: #3988BD;
    }
    
    /* Center the line for centered titles */
    .section-title.text-center:after,
    .text-center .section-title:after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    /* Section description styling */
    .section-description {
        color: #6c757d;
        font-size: 1rem;
        margin-top: 0.5rem;
        margin-bottom: 0;
    }
    
    .automotive-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: solid 3px #e0e0e0;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .automotive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .organization-card {
        transition: all 0.3s ease;
        border: solid 3px #e0e0e0;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .organization-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .organization-card img {
        transition: transform 0.3s ease;
    }
    
    .organization-card:hover img {
        transform: scale(1.05);
    }
    
    .characteristic-icon {
        font-size: 3rem;
        color: #3988BD;
        margin-bottom: 1rem;
    }
    
    .characteristic-card {
        text-align: center;
        padding: 30px 20px;
        border-radius: 15px;
        transition: all 0.3s ease;
        height: 100%;
        border: solid 3px #e0e0e0;
    }
    
    .characteristic-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    
    .partnership-logo {
        padding: 20px;
        background: white;
        border-radius: 10px;
        transition: all 0.3s ease;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: solid 3px #e0e0e0;
    }
    
    .partnership-logo:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #3988BD;
    }
    
    .partnership-logo img {
        max-width: 100%;
        max-height: 100px;
        object-fit: contain;
    }
    
    .badge-custom {
        background: #3988BD;
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        display: inline-block;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #3988BD;
    }
    
    /* Read More Styles */
    .description-wrapper {
        position: relative;
    }
    
    .short-description {
        display: block;
    }
    
    .full-description {
        display: none;
    }
    
    .description-wrapper.expanded .short-description {
        display: none;
    }
    
    .description-wrapper.expanded .full-description {
        display: block;
    }
    
    .read-more-btn {
        background: none;
        border: none;
        color: #3988BD;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0;
        margin-top: 5px;
        display: inline-block;
        transition: color 0.2s ease;
    }
    
    .read-more-btn:hover {
        color: #0E334C;
        text-decoration: underline;
    }
    
    .characteristic-description {
        margin-bottom: 0;
    }
    
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.5rem;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 0.9rem;
        }
        
        .hero-line-main {
            width: 35px;
        }
        
        .hero-line-dot {
            width: 6px;
            height: 6px;
        }
    }
    
    @media (max-width: 576px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 0.85rem;
        }
        
        .hero-line {
            margin: 0.75rem auto;
        }
        
        .hero-line-main {
            width: 25px;
        }
        
        .scroll-text {
            font-size: 0.6rem;
        }
        
        .hero-scroll-indicator i {
            font-size: 0.75rem;
        }
    }
</style>

<!-- Hero Section - Modern Gradient with Subtle Animation -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Business Introduction</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Discover our comprehensive business portfolio and commitment to excellence</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Discover More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Automotive Seat Cover Section - CENTERED -->
    @if($automotiveSeats->count() > 0)
    <div class="mb-5 fade-in-up">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title text-center">Automotive Seat Cover</h2>
                <p class="section-description">Premium quality seat covers designed for comfort and durability</p>
            </div>
        </div>
        <div class="row">
            @foreach($automotiveSeats as $seat)
            @php
                $description = $seat->description;
                $shortDesc = Str::limit($description, 100);
                $needsReadMore = strlen($description) > 100;
                $uniqueId = 'auto-desc-' . $seat->id;
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card automotive-card h-100">
                    <div class="row g-0 h-100">
                        @if($seat->image)
                        <div class="col-md-5">
                            <img src="{{ $seat->image_url }}" class="img-fluid rounded-start h-100" alt="{{ $seat->title }}" style="object-fit: cover;">
                        </div>
                        @endif
                        <div class="col-md-7">
                            <div class="card-body">
                                <h5 class="card-title">{{ $seat->title }}</h5>
                                <div class="description-wrapper" id="{{ $uniqueId }}">
                                    <div class="short-description">
                                        <p class="card-text">{{ $shortDesc }}</p>
                                        @if($needsReadMore)
                                        <button class="read-more-btn" onclick="toggleDescription('{{ $uniqueId }}')">
                                            Read More <i class="fas fa-chevron-down"></i>
                                        </button>
                                        @endif
                                    </div>
                                    @if($needsReadMore)
                                    <div class="full-description">
                                        <p class="card-text">{{ $description }}</p>
                                        <button class="read-more-btn" onclick="toggleDescription('{{ $uniqueId }}')">
                                            Read Less <i class="fas fa-chevron-up"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Organization Section -->
    @if($organizationMembers->count() > 0)
    <div class="mb-5 fade-in-up">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title text-center">Organizational Structure</h2>
                <p class="section-description">Meet our dedicated leadership team</p>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($organizationMembers as $member)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card organization-card h-100 text-center">
                    @if($member->image)
                    <div class="overflow-hidden">
                        <img src="{{ $member->image_url }}" class="card-img-top" alt="{{ $member->name }}" style="height: 250px; object-fit: cover;">
                    </div>
                    @else
                    <div class="bg-light p-5 text-center">
                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $member->name }}</h5>
                        <p class="text-primary mb-0">{{ Str::limit($member->position, 100) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Characteristics Section -->
    @if($characteristics->count() > 0)
    <div class="mb-5 py-4 fade-in-up">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="section-title text-center">Business Characteristics</h2>
                    <p class="section-description">What makes us unique in the industry</p>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach($characteristics as $characteristic)
                @php
                    $description = $characteristic->description;
                    $shortDesc = Str::limit($description, 100);
                    $needsReadMore = strlen($description) > 100;
                    $uniqueId = 'char-desc-' . $characteristic->id;
                @endphp
                <div class="col-md-4 mb-4 {{ $characteristics->count() == 1 ? 'col-md-6 mx-auto' : '' }}">
                    <div class="characteristic-card">
                        @if($characteristic->image)
                        <img src="{{ $characteristic->image_url }}" alt="{{ $characteristic->title }}" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 1rem;">
                        @else
                        <div class="characteristic-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        @endif
                        <h4>{{ $characteristic->title }}</h4>
                        <div class="description-wrapper" id="{{ $uniqueId }}">
                            <div class="short-description">
                                <p class="text-muted characteristic-description">{{ $shortDesc }}</p>
                                @if($needsReadMore)
                                <button class="read-more-btn" onclick="toggleDescription('{{ $uniqueId }}')">
                                    Read More <i class="fas fa-chevron-down"></i>
                                </button>
                                @endif
                            </div>
                            @if($needsReadMore)
                            <div class="full-description">
                                <p class="text-muted characteristic-description">{{ $description }}</p>
                                <button class="read-more-btn" onclick="toggleDescription('{{ $uniqueId }}')">
                                    Read Less <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Partnership Section -->
    @if($partnerships->count() > 0)
    <div class="mb-5 fade-in-up">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title text-center">Our Partners</h2>
                <p class="section-description">Trusted partnerships that drive excellence</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            @foreach($partnerships as $partner)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                <div class="partnership-logo">
                    @if($partner->image)
                    <img src="{{ $partner->image_url }}" alt="{{ $partner->title }}" class="img-fluid">
                    @else
                    <div class="text-center">
                        <i class="fas fa-building fa-3x text-muted"></i>
                        <p class="mt-2 mb-0 small">{{ Str::limit($partner->title, 50) }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Font Awesome 6 -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

<!-- Smooth Scroll & Read More Script -->
@push('scripts')
<script>
    // Smooth scroll for hero indicator
    document.querySelector('.hero-scroll-indicator')?.addEventListener('click', function() {
        const nextSection = document.querySelector('.container.py-5');
        if (nextSection) {
            nextSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
    
    // Toggle description function for Read More / Read Less
    function toggleDescription(elementId) {
        const wrapper = document.getElementById(elementId);
        if (wrapper) {
            wrapper.classList.toggle('expanded');
        }
    }
</script>
@endpush

@endsection