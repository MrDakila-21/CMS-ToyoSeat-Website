@extends('layouts.app')

@section('title', 'Events & Activities - News')

@section('content')
<style>
    /* CSS Variables - Enhanced Color Scheme (copied from location) */
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

    /* Hero Section - Enhanced with reduced spacing (copied from location) */
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

    /* Event Card Styles */
    .event-card {
        border-radius: 15px !important;
        transition: all 0.3s ease !important;
        overflow: hidden !important;
    }
    
    .event-card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }
    
    .event-card .card-img-top {
        transition: transform 0.5s ease !important;
    }
    
    .event-card:hover .card-img-top {
        transform: scale(1.08) !important;
    }
    
    .card-body {
        background: white;
    }
    
    /* Improved Modal Styles for Long Descriptions */
    .modal-content {
        border-radius: 20px !important;
        overflow: hidden;
    }
    
    .modal-body {
        padding-bottom: 1.5rem !important;
    }
    
    .modal-body .row:last-child {
        margin-bottom: 0;
    }
    
    .modal-body .col-lg-5,
    .modal-body .col-lg-7 {
        margin-bottom: 0;
    }
    
    .modal-image-container {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #f8f9fa;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }
    
    .modal-image {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    
    .modal-image:hover {
        transform: scale(1.02);
    }
    
    .description-wrapper-custom {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .description-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary-lighter);
        display: inline-block;
    }
    
    .description-content {
        flex: 1;
        overflow-y: auto;
        padding-right: 12px;
        line-height: 1.8;
        color: #555;
        text-align: justify;
        max-height: 450px;
        margin-bottom: 0;
    }
    
    .description-content::-webkit-scrollbar {
        width: 6px;
    }
    
    .description-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .description-content::-webkit-scrollbar-thumb {
        background: #3988BD;
        border-radius: 10px;
    }
    
    .description-content::-webkit-scrollbar-thumb:hover {
        background: #0E334C;
    }
    
    .info-badge {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--gray-border);
    }
    
    .info-badge-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .info-badge-item i {
        color: var(--primary);
        font-size: 0.9rem;
    }
    
    /* Responsive Modal Adjustments */
    @media (max-width: 768px) {
        .modal-body {
            padding: 1.5rem !important;
        }
        
        .modal-image-container {
            min-height: 200px;
            margin-bottom: 1.5rem;
        }
        
        .modal-image {
            max-height: 250px;
        }
        
        .description-content {
            max-height: 300px;
        }
        
        .description-title {
            font-size: 1rem;
        }
    }
    
    @media (min-width: 769px) and (max-width: 992px) {
        .modal-image-container {
            min-height: 250px;
        }
        
        .description-content {
            max-height: 350px;
        }
    }
    
    .empty-state {
        padding: 80px 20px;
        background: #f8f9fa;
        border-radius: 20px;
    }
    
    .filter-btn {
        padding: 8px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
    }
    
    .search-box {
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 50px;
    }
    
    .search-box .input-group-text {
        border-radius: 50px 0 0 50px;
    }
    
    .search-box input {
        border-radius: 0 50px 50px 0;
        padding: 12px 20px;
    }
    
    .search-box input:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    
    .pagination {
        gap: 4px;
        flex-wrap: wrap;
    }
    
    .pagination .page-item .page-link {
        padding: 4px 10px;
        font-size: 12px;
        min-width: 32px;
        height: 32px;
        line-height: 20px;
        border-radius: 6px !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #3988BD;
        border-color: #3988BD;
        color: #fff;
    }
    
    .pagination .page-item .page-link:hover {
        background-color: #f1f5f9;
        color: #3988BD;
    }
    
    .pagination .page-item.disabled .page-link {
        font-size: 11px;
        opacity: 0.6;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 4px 8px;
    }
    
    .pagination .page-item.active .page-link {
        background: #3988BD;
        border-color: #3988BD;
        color: white;
        box-shadow: 0 2px 4px rgba(57, 136, 189, 0.2);
    }
    
    .pagination .page-item .page-link:hover {
        background: #f8f9fa;
        border-color: #3988BD;
        color: #3988BD;
        transform: translateY(-1px);
    }
    
    .pagination .page-item.active .page-link:hover {
        background: #3988BD;
        color: white;
        transform: translateY(-1px);
    }
    
    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        background: #f8f9fa;
        cursor: not-allowed;
    }
    
    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        background: #f8f9fa;
        border-color: #e0e0e0;
    }
    
    /* Animation for cards */
    @keyframes fadeInUpCard {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .event-item {
        animation: fadeInUpCard 0.5s ease-out;
        animation-fill-mode: both;
    }
    
    /* Responsive Design (copied from location) */
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
        
        .filter-btn {
            padding: 6px 16px;
            font-size: 13px;
        }
        
        .filter-btn i {
            font-size: 12px;
        }
        
        .pagination {
            gap: 3px;
        }
        
        .pagination .page-item .page-link {
            padding: 4px 8px;
            font-size: 12px;
            min-width: 30px;
        }
        
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            padding: 4px 8px;
        }
    }
    
    /* Extra small devices */
    @media (max-width: 480px) {
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
        
        .pagination {
            gap: 2px;
        }
        
        .pagination .page-item .page-link {
            padding: 3px 6px;
            font-size: 11px;
            min-width: 26px;
            border-radius: 6px !important;
        }
        
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            padding: 3px 6px;
        }
    }
    
    /* Tablet devices */
    @media (min-width: 769px) and (max-width: 1024px) {
        .pagination .page-item .page-link {
            padding: 5px 10px;
            font-size: 12px;
            min-width: 34px;
        }
    }
</style>

<!-- Hero Section -->
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
                                    <div class="modal-image-container">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" class="modal-image" alt="{{ $item->title }}">
                                        @else
                                            <img src="{{ asset('images/default-image.png') }}" class="modal-image" alt="Default Image">
                                        @endif
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
                                        <div class="description-content">
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
@endpush

<!-- AJAX Real-time Search Script with Pagination -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const eventsContainer = document.getElementById('eventsContainer');
        const paginationContainer = document.getElementById('paginationContainer');
        const noResultsMessage = document.getElementById('noResultsMessage');
        
        let currentType = '{{ request('type', 'all') }}';
        let currentSearch = '{{ request('search') }}';
        let currentPage = 1;
        
        // Function to fetch filtered events via AJAX
        function fetchFilteredEvents(page = 1) {
            let url = "{{ route('guest.news.media-information') }}?ajax=1&page=" + page;
            
            if (currentSearch) {
                url += "&search=" + encodeURIComponent(currentSearch);
            }
            
            if (currentType && currentType !== 'all') {
                url += "&type=" + currentType;
            }
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.total === 0) {
                    eventsContainer.innerHTML = '';
                    paginationContainer.style.display = 'none';
                    noResultsMessage.style.display = 'block';
                } else {
                    paginationContainer.style.display = 'flex';
                    noResultsMessage.style.display = 'none';
                    renderEvents(data.data);
                    updatePagination(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        
        // Function to render events with correct image URLs
        function renderEvents(events) {
            if (!eventsContainer) return;
            
            let html = '';
            
            events.forEach(item => {
                // Use the image_url from the server (which uses the model accessor)
                let imageUrl = item.image_url;
                if (!imageUrl || imageUrl === '') {
                    imageUrl = "{{ asset('images/default-image.png') }}";
                }
                
                const eventDate = new Date(item.event_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                const typeBadge = item.type === 'event' 
                    ? '<span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fas fa-calendar-alt me-1"></i> Event</span>'
                    : '<span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-users me-1"></i> Activity</span>';
                
                html += `
                    <div class="col-md-6 col-lg-4 event-item" data-type="${item.type}">
                        <div class="card h-100 shadow-sm border-0 event-card" data-bs-toggle="modal" data-bs-target="#eventModal${item.id}" style="cursor: pointer; transition: all 0.3s ease;">
                            <div class="position-relative overflow-hidden">
                                <img src="${imageUrl}" class="card-img-top" alt="${escapeHtml(item.title)}" style="height: 240px; width: 100%; object-fit: cover;" onerror="this.src='{{ asset('images/default-image.png') }}'">
                                <div class="position-absolute top-0 end-0 m-3">
                                    ${typeBadge}
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-2" style="color: #0E334C; line-height: 1.4;">
                                    ${escapeHtml(item.title).substring(0, 60)}${item.title.length > 60 ? '...' : ''}
                                </h5>
                                <p class="card-text text-muted mb-0">
                                    <i class="fas fa-calendar-alt me-2" style="color: #3988BD;"></i>
                                    ${eventDate}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal fade" id="eventModal${item.id}" tabindex="-1" aria-labelledby="eventModalLabel${item.id}" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 overflow-hidden">
                                <div class="modal-header" style="background: linear-gradient(135deg, #0E334C 0%, #1a4d6e 100%);">
                                    <h5 class="modal-title text-white fw-bold">
                                        <i class="fas ${item.type === 'event' ? 'fa-calendar-alt' : 'fa-users'} me-2"></i>
                                        ${escapeHtml(item.title)}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row g-4">
                                        <div class="col-lg-5">
                                            <div class="modal-image-container">
                                                <img src="${imageUrl}" class="modal-image" alt="${escapeHtml(item.title)}" onerror="this.src='{{ asset('images/default-image.png') }}'">
                                            </div>
                                            <div class="info-badge mt-3">
                                                <div class="info-badge-item">
                                                    <i class="fas ${item.type === 'event' ? 'fa-calendar-alt' : 'fa-users'}"></i>
                                                    <span><strong>Type:</strong> ${item.type.charAt(0).toUpperCase() + item.type.slice(1)}</span>
                                                </div>
                                                <div class="info-badge-item">
                                                    <i class="fas fa-calendar-day"></i>
                                                    <span><strong>Date:</strong> ${eventDate}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="description-wrapper-custom">
                                                <div class="description-title">
                                                    <i class="fas fa-align-left me-2" style="color: #3988BD;"></i>
                                                    Description
                                                </div>
                                                <div class="description-content">
                                                    ${escapeHtml(item.description).replace(/\n/g, '<br>')}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            eventsContainer.innerHTML = html;
            
            // Reinitialize modal triggers
            document.querySelectorAll('.event-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.closest('.badge')) {
                        e.stopPropagation();
                    }
                });
            });
        }
        
        // Update pagination links
        function updatePagination(data) {
            if (!paginationContainer) return;
            
            let paginationHtml = '<ul class="pagination">';
            
            // Previous button
            if (data.current_page > 1) {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">Previous</a></li>`;
            } else {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
            }
            
            // Page numbers
            for (let i = 1; i <= data.last_page; i++) {
                if (i === data.current_page) {
                    paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else {
                    paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
            }
            
            // Next button
            if (data.current_page < data.last_page) {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">Next</a></li>`;
            } else {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
            }
            
            paginationHtml += '</ul>';
            paginationContainer.innerHTML = paginationHtml;
            
            // Attach pagination click events
            document.querySelectorAll('#paginationContainer .page-link[data-page]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));
                    if (!isNaN(page)) {
                        currentPage = page;
                        fetchFilteredEvents(page);
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            });
        }
        
        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Search input with debounce
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                currentSearch = this.value;
                currentPage = 1;
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetchFilteredEvents(1);
                }, 500);
            });
        }
        
        // Filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                currentType = type;
                currentPage = 1;
                
                // Update button styles
                filterButtons.forEach(btn => {
                    const btnType = btn.getAttribute('data-type');
                    if (btnType === 'all') {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    } else if (btnType === 'event') {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    } else if (btnType === 'activity') {
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-outline-success');
                    }
                });
                
                if (type === 'all') {
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary');
                } else if (type === 'event') {
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary');
                } else if (type === 'activity') {
                    this.classList.remove('btn-outline-success');
                    this.classList.add('btn-success');
                }
                
                fetchFilteredEvents(1);
            });
        });
        
        // Smooth scroll for hero indicator
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