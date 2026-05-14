@extends('layouts.app')

@section('title', 'Events & Activities - News')

@section('content')
<!-- Hero Section - Modern Gradient with Subtle Animation -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Events & Activities</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Stay updated with our latest events and activities</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Discover More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="search-box">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           id="searchInput" 
                           class="form-control border-start-0" 
                           placeholder="Search events or activities..." 
                           value="{{ request('search') }}"
                           style="border-left: none;">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Buttons -->
    <div class="d-flex justify-content-center gap-2 mb-5 flex-wrap">
        <button type="button" class="btn {{ request('type', 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill filter-btn" data-type="all">
            <i class="fas fa-th-large me-1"></i> All
        </button>
        <button type="button" class="btn {{ request('type') == 'event' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill filter-btn" data-type="event">
            <i class="fas fa-calendar-alt me-1"></i> Events Only
        </button>
        <button type="button" class="btn {{ request('type') == 'activity' ? 'btn-success' : 'btn-outline-success' }} rounded-pill filter-btn" data-type="activity">
            <i class="fas fa-users me-1"></i> Activities Only
        </button>
    </div>
    
    <!-- Events Container -->
    <div class="row g-4" id="eventsContainer">
        @if(isset($events) && $events->count() > 0)
            @foreach($events as $item)
            <div class="col-md-6 col-lg-4 event-item" data-type="{{ $item->type }}">
                <div class="card h-100 shadow-sm border-0 event-card" data-bs-toggle="modal" data-bs-target="#eventModal{{ $item->id }}" style="cursor: pointer; transition: all 0.3s ease;">
                    <div class="position-relative overflow-hidden">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->title }}" style="height: 240px; width: 100%; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-image.png') }}" class="card-img-top" alt="Default Image" style="height: 240px; width: 100%; object-fit: cover;">
                        @endif
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge {{ $item->type === 'event' ? 'bg-primary' : 'bg-success' }} px-3 py-2 rounded-pill">
                                <i class="fas {{ $item->type === 'event' ? 'fa-calendar-alt' : 'fa-users' }} me-1"></i>
                                {{ ucfirst($item->type) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2" style="color: #0E334C; line-height: 1.4;">
                            {{ Str::limit($item->title, 60) }}
                        </h5>
                        <p class="card-text text-muted mb-0">
                            <i class="fas fa-calendar-alt me-2" style="color: #3988BD;"></i>
                            {{ $item->event_date instanceof \Carbon\Carbon ? $item->event_date->format('F d, Y') : \Carbon\Carbon::parse($item->event_date)->format('F d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal for each item -->
<!-- Modal for each item - Enhanced with image preview icon at bottom right -->
<div class="modal fade" id="eventModal{{ $item->id }}" tabindex="-1" aria-labelledby="eventModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header" style="background: linear-gradient(135deg, #0E334C 0%, #1a4d6e 100%);">
                <h5 class="modal-title text-white fw-bold" id="eventModalLabel{{ $item->id }}">
                    <i class="fas {{ $item->type === 'event' ? 'fa-calendar-alt' : 'fa-users' }} me-2"></i>
                    {{ $item->title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="modal-image-container position-relative" style="overflow: hidden; border-radius: 12px;">
                            <img src="{{ $item->image_url ?: asset('images/default-image.png') }}" 
                                 class="modal-image clickable-image w-100 rounded-3" 
                                 alt="{{ $item->title }}"
                                 style="cursor: pointer; transition: transform 0.2s; max-height: 400px; width: 100%; object-fit: cover; display: block;"
                                 data-full-image="{{ $item->image_url ?: asset('images/default-image.png') }}"
                                 data-image-title="{{ $item->title }}"
                                 onerror="this.src='{{ asset('images/default-image.png') }}'">
                            <div class="image-expand-icon" style="position: absolute; bottom: 12px; right: 12px; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); padding: 8px 12px; border-radius: 25px; color: white; font-size: 14px; cursor: pointer; transition: all 0.2s ease; z-index: 10;">
                                <i class="fas fa-expand-alt me-1"></i> Expand
                            </div>
                        </div>
                        <div class="info-badge mt-3">
                            <div class="info-badge-item">
                                <i class="fas {{ $item->type === 'event' ? 'fa-calendar-alt' : 'fa-users' }}"></i>
                                <span><strong>Type:</strong> {{ ucfirst($item->type) }}</span>
                            </div>
                            <div class="info-badge-item">
                                <i class="fas fa-calendar-day"></i>
                                <span><strong>Date:</strong> {{ $item->event_date instanceof \Carbon\Carbon ? $item->event_date->format('F d, Y') : \Carbon\Carbon::parse($item->event_date)->format('F d, Y') }}</span>
                            </div>
                            @if($item->created_at)
                            <div class="info-badge-item">
                                <i class="fas fa-clock"></i>
                                <span><strong>Published:</strong> {{ $item->created_at instanceof \Carbon\Carbon ? $item->created_at->format('F d, Y') : \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="description-wrapper-custom">
                            <div class="description-title">
                                <i class="fas fa-align-left me-2" style="color: #3988BD;"></i>
                                Description
                            </div>
                            <div class="description-content" style="max-height: 400px; overflow-y: auto;">
                                {!! nl2br(e($item->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">No Events or Activities Available</h3>
                    <p class="text-muted mb-4">Check back later for updates on our upcoming events and activities.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5" id="paginationContainer">
        {{ $events->appends(request()->query())->links() }}
    </div>
    
    <!-- No Results Message (hidden by default) -->
    <div id="noResultsMessage" class="text-center py-5" style="display: none;">
        <div class="empty-state">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4 class="text-muted mb-3">No Results Found</h4>
            <p class="text-muted mb-0">Try adjusting your search or filter criteria</p>
        </div>
    </div>
</div>

<!-- Font Awesome 6 -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/guest/EventActivity.css') }}">
@endpush

<!-- JavaScript -->
@push('scripts')
<!-- Add jQuery first (required for the module to work like announcements) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Your custom JS -->
<script src="{{ asset('js/guest/EventActivity.js') }}"></script>
@endpush

@endsection