@extends('layouts.app')

@section('title', 'Location - About Us')

@section('content')
<style>
    /* CSS Variables - Enhanced Color Scheme (copied from overview) */
    :root {
        --primary-dark: #0A2B3E;
        --primary: #1A6D8F;
        --primary-light: #3A8EB5;
        --primary-lighter: #E6F3F9;
        --primary-glow: rgba(26, 109, 143, 0.2);
        --secondary: #2C3E50;
        --accent: #E76F51;
        --accent-light: #F4EAE6;
        --gray-light: #F8F9FA;
        --gray-border: #E9ECEF;
        --text-dark: #1A2B3C;
        --text-muted: #6C757D;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
        --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
        --shadow-lg: 0 16px 40px rgba(0,0,0,0.12);
        --shadow-xl: 0 24px 56px rgba(0,0,0,0.16);
        --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Hero Section - Enhanced with reduced spacing */
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
        background: linear-gradient(90deg, transparent, var(--primary-light), var(--primary), var(--primary-light), transparent);
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
        background: linear-gradient(90deg, transparent, var(--primary-light), var(--primary-light), transparent);
    }

    .hero-line-dot {
        width: 8px;
        height: 8px;
        background: var(--primary-light);
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
        transition: var(--transition);
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

    /* Animations */
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

    /* Location specific styles */
    .info-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-top: 50px;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #0E334C 0%, #3988BD 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .info-icon i {
        font-size: 24px;
        color: white;
    }
    
    .info-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #0E334C;
        margin-bottom: 20px;
    }
    
    .address-detail {
        margin-bottom: 15px;
        padding-left: 20px;
        border-left: 3px solid #3988BD;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 10px;
        transition: background 0.3s ease;
    }
    
    .contact-item:hover {
        background: #f8f9fa;
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
        background: rgba(57, 136, 189, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3988BD;
        font-size: 18px;
    }
    
    .contact-content {
        flex: 1;
    }
    
    .contact-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 3px;
    }
    
    .contact-value {
        font-size: 1rem;
        color: #0E334C;
        font-weight: 500;
        text-decoration: none;
    }
    
    .contact-value:hover {
        color: #3988BD;
    }
    
    .working-hours {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .hour-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .hour-item:last-child {
        border-bottom: none;
    }
    
    .hour-day {
        font-weight: 600;
        color: #0E334C;
    }
    
    .hour-time {
        color: #3988BD;
    }
    
    .map-card {
        background: white;
        border-radius: 20px;
        margin-top: 50px;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .map-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }
    
    .map-container {
        height: 400px;
        width: 100%;
    }
    
    .map-placeholder {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #6c757d;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(14, 51, 76, 0.1) 0%, rgba(57, 136, 189, 0.1) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
    }
    
    .empty-icon i {
        font-size: 50px;
        color: #3988BD;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
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
        
        .hero-scroll-indicator {
            margin-top: 1rem;
        }
        
        .info-card {
            padding: 20px;
        }
        
        .map-container {
            height: 300px;
        }
    }
    
    @media (max-width: 576px) {
        .hero-section-wrapper {
            padding: 1rem 0 0.75rem 0;
        }
        
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

<!-- Hero Section - Modern Gradient with Subtle Animation (copied from overview) -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Our Location</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Visit us at our headquarters or get in touch through the details below</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Discover More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    @isset($location)
    <div class="row g-4 fade-in-up" style="animation-delay: 0.2s;">
        <!-- Address & Contact Information -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="info-title">Company Headquarters</h3>
                
                <div class="address-detail">
                    <p class="mb-1">{{ $location->address_line1 }}</p>
                    @if($location->address_line2)
                        <p class="mb-1">{{ $location->address_line2 }}</p>
                    @endif
                    <p class="mb-1">
                        {{ $location->city }}
                        @if($location->state), {{ $location->state }}@endif
                        @if($location->postal_code) {{ $location->postal_code }}@endif
                    </p>
                    <p class="mb-0">{{ $location->country }}</p>
                </div>
                
                @if($location->phone || $location->email)
                <div class="mt-4">
                    @if($location->phone)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-content">
                            <div class="contact-label">Phone Number</div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $location->phone) }}" class="contact-value">
                                {{ $location->phone }}
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if($location->email)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-content">
                            <div class="contact-label">Email Address</div>
                            <a href="mailto:{{ $location->email }}" class="contact-value">
                                {{ $location->email }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                @if($location->working_hours)
                <div class="working-hours">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <i class="fas fa-clock" style="color: #3988BD; font-size: 20px;"></i>
                        <h4 class="mb-0" style="font-size: 1.1rem; font-weight: 600; color: #0E334C;">Operating Hours</h4>
                    </div>
                    @php
                        $hours = explode("\n", $location->working_hours);
                    @endphp
                    @foreach($hours as $hour)
                        @if(trim($hour))
                            @php
                                $parts = explode(':', $hour, 2);
                            @endphp
                            <div class="hour-item">
                                <span class="hour-day">{{ trim($parts[0]) }}</span>
                                <span class="hour-time">{{ isset($parts[1]) ? trim($parts[1]) : trim($hour) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="col-lg-6">
            <div class="map-card">
                <div class="p-4 border-bottom" style="background: #f8f9fa;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-map-marker-alt" style="color: #3988BD; font-size: 24px;"></i>
                        <div>
                            <h3 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #0E334C;">Find Us Here</h3>
                            <small class="text-muted">Get directions to our location</small>
                        </div>
                    </div>
                </div>
                
                @if($location->google_maps_embed)
                    <div class="map-container">
                        {!! $location->google_maps_embed !!}
                    </div>
                @elseif($location->latitude && $location->longitude)
                    <div id="map" class="map-container"></div>
                @else
                    <div class="map-placeholder">
                        <i class="fas fa-map-marked-alt fa-4x mb-3" style="color: #3988BD;"></i>
                        <p class="text-center mb-0 px-4">Interactive map will be available soon. Please check back later.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @else
    <!-- Empty State -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="empty-state fade-in-up">
                <div class="empty-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="h4 mb-3" style="color: #0E334C;">Location Information</h3>
                <p class="text-muted mb-4">Company location details will be available soon.</p>
                <div class="alert alert-info" style="background: rgba(57, 136, 189, 0.1); border: none; border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i> 
                    Please check back later for our complete address and map.
                </div>
            </div>
        </div>
    </div>
    @endisset
</div>

<!-- Font Awesome 6 -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

<!-- Smooth Scroll for Hero Indicator -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollIndicator = document.querySelector('.hero-scroll-indicator');
        if (scrollIndicator) {
            scrollIndicator.addEventListener('click', function() {
                window.scrollBy({
                    top: window.innerHeight - 100,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>
@endpush

@endsection

@isset($location)
    @if($location->latitude && $location->longitude && !$location->google_maps_embed)
    @section('scripts')
    <script>
        function initMap() {
            const location = { lat: {{ $location->latitude }}, lng: {{ $location->longitude }} };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: location,
                styles: [
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [{"color": "#ffffff"}, {"lightness": 20}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
                    }
                ],
                disableDefaultUI: false,
                zoomControl: true,
                mapTypeControl: false,
                streetViewControl: true,
                fullscreenControl: true
            });
            
            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "{{ addslashes($location->address_line1) }}",
                animation: google.maps.Animation.DROP,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
            
            // Optional: Add an info window
            const infoWindow = new google.maps.InfoWindow({
                content: '<div style="padding: 10px;"><strong>{{ addslashes($location->address_line1) }}</strong><br>{{ addslashes($location->city) }}, {{ addslashes($location->country) }}</div>'
            });
            
            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', 'YOUR_API_KEY') }}&callback=initMap" async defer></script>
    @endsection
    @endif
@endisset