@extends('layouts.app')

@section('title', 'Location - About Us')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Our Location</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
    </div>
    
    @isset($location)
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h3 class="h4 mb-3" style="color: #0E334C;">
                        <i class="fas fa-building me-2"></i>Company Address
                    </h3>
                    <address class="mb-4">
                        <p class="mb-1">{{ $location->address_line1 }}</p>
                        @if($location->address_line2)
                            <p class="mb-1">{{ $location->address_line2 }}</p>
                        @endif
                        <p class="mb-1">
                            {{ $location->city }}
                            @if($location->state), {{ $location->state }}@endif
                            @if($location->postal_code) {{ $location->postal_code }}@endif
                        </p>
                        <p class="mb-3">{{ $location->country }}</p>
                    </address>
                    
                    @if($location->phone)
                    <div class="mb-3">
                        <i class="fas fa-phone me-2" style="color: #3988BD;"></i>
                        <strong>Phone:</strong> 
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $location->phone) }}" class="text-decoration-none">
                            {{ $location->phone }}
                        </a>
                    </div>
                    @endif
                    
                    @if($location->email)
                    <div class="mb-3">
                        <i class="fas fa-envelope me-2" style="color: #3988BD;"></i>
                        <strong>Email:</strong> 
                        <a href="mailto:{{ $location->email }}" class="text-decoration-none">{{ $location->email }}</a>
                    </div>
                    @endif
                    
                    @if($location->working_hours)
                    <div class="mt-4">
                        <h4 class="h6 mb-2">
                            <i class="fas fa-clock me-2" style="color: #3988BD;"></i>
                            Working Hours
                        </h4>
                        <p style="white-space: pre-line;" class="mb-0">{{ $location->working_hours }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h3 class="h4 mb-3" style="color: #0E334C;">
                        <i class="fas fa-map me-2"></i>Find Us
                    </h3>
                    
                    @if($location->google_maps_embed)
                        <div class="ratio ratio-16x9">
                            {!! $location->google_maps_embed !!}
                        </div>
                    @elseif($location->latitude && $location->longitude)
                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Map location will be added soon.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 p-4 text-center">
                <i class="fas fa-map-marker-alt fa-3x mb-3" style="color: #3988BD;"></i>
                <h3 class="h5">Location Information</h3>
                <p class="text-muted">Company location details will be available soon.</p>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> Please check back later for our complete address and map.
                </div>
            </div>
        </div>
    </div>
    @endisset
</div>
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
                    }
                ]
            });
            new google.maps.Marker({
                position: location,
                map: map,
                title: "{{ addslashes($location->address_line1) }}",
                animation: google.maps.Animation.DROP
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
    @endsection
    @endif
@endisset