@extends('layouts.app')

@section('title', 'Events & Activities - News')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Events & Activities</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
        <p class="text-muted">Stay updated with our latest events and activities</p>
    </div>
    
    @if(isset($events) && $events->count() > 0)
    <!-- Search and Filter Section -->
    <form method="GET" action="{{ route('guest.news.media-information') }}" id="filterForm">
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="search-box">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search"
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
            <a href="{{ route('guest.news.media-information', array_merge(request()->except('type'), ['type' => 'all', 'page' => 1])) }}" 
               class="btn {{ request('type', 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill filter-btn">
                <i class="fas fa-th-large me-1"></i> All
            </a>
            <a href="{{ route('guest.news.media-information', array_merge(request()->except('type'), ['type' => 'event', 'page' => 1])) }}" 
               class="btn {{ request('type') == 'event' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill filter-btn">
                <i class="fas fa-calendar-alt me-1"></i> Events Only
            </a>
            <a href="{{ route('guest.news.media-information', array_merge(request()->except('type'), ['type' => 'activity', 'page' => 1])) }}" 
               class="btn {{ request('type') == 'activity' ? 'btn-success' : 'btn-outline-success' }} rounded-pill filter-btn">
                <i class="fas fa-users me-1"></i> Activities Only
            </a>
        </div>
    </form>
    
    <!-- Events Container -->
    <div class="row g-4" id="eventsContainer">
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
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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
                            <div class="col-md-6">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" class="img-fluid rounded-3 shadow-sm" alt="{{ $item->title }}" style="width: 100%; max-height: 300px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-image.png') }}" class="img-fluid rounded-3 shadow-sm" alt="Default Image" style="width: 100%; max-height: 300px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                        <span class="badge {{ $item->type === 'event' ? 'bg-primary' : 'bg-success' }} px-3 py-2 rounded-pill">
                                            <i class="fas {{ $item->type === 'event' ? 'fa-calendar-alt' : 'fa-users' }} me-1"></i>
                                            {{ ucfirst($item->type) }}
                                        </span>
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-calendar-day me-1" style="color: #3988BD;"></i>
                                            {{ $item->event_date instanceof \Carbon\Carbon ? $item->event_date->format('F d, Y') : \Carbon\Carbon::parse($item->event_date)->format('F d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="description-wrapper">
                                    <h6 class="fw-bold mb-3" style="color: #0E334C;">
                                        <i class="fas fa-align-left me-2" style="color: #3988BD;"></i>
                                        Description
                                    </h6>
                                    <div class="description-text" style="line-height: 1.8; color: #555; text-align: justify;">
                                        {!! nl2br(e($item->description)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Laravel Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $events->appends(request()->query())->links() }}
    </div>
    
    @else
    <div class="text-center py-5">
        <div class="empty-state">
            <i class="fas fa-calendar-times fa-5x text-muted mb-4"></i>
            <h3 class="text-muted mb-3">No Events or Activities Available</h3>
            <p class="text-muted mb-4">Check back later for updates on our upcoming events and activities.</p>
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-home me-2"></i> Back to Home
            </a>
        </div>
    </div>
    @endif
</div>

<style>
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
    
    .description-text {
        max-height: 350px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .description-text::-webkit-scrollbar {
        width: 6px;
    }
    
    .description-text::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .description-text::-webkit-scrollbar-thumb {
        background: #3988BD;
        border-radius: 10px;
    }
    
    .description-text::-webkit-scrollbar-thumb:hover {
        background: #0E334C;
    }
    
    .empty-state {
        padding: 80px 20px;
        background: #f8f9fa;
        border-radius: 20px;
    }
    
    .modal-content {
        border-radius: 20px !important;
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
    padding: 4px 10px; /* smaller */
    font-size: 12px;
    min-width: 32px;
    height: 32px;
    line-height: 20px;
    border-radius: 6px !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Active */
.pagination .page-item.active .page-link {
    background-color: #3988BD;
    border-color: #3988BD;
    color: #fff;
}

/* Hover */
.pagination .page-item .page-link:hover {
    background-color: #f1f5f9;
    color: #3988BD;
}

/* Disabled */
.pagination .page-item.disabled .page-link {
    font-size: 11px;
    opacity: 0.6;
}

/* Prev/Next smaller */
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
    @keyframes fadeInUp {
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
        animation: fadeInUp 0.5s ease-out;
        animation-fill-mode: both;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .description-text {
            max-height: 250px;
        }
        
        .modal-body {
            padding: 1.5rem !important;
        }
        
        .filter-btn {
            padding: 6px 16px;
            font-size: 13px;
        }
        
        .filter-btn i {
            font-size: 12px;
        }
        
        /* Smaller pagination on mobile */
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

@push('scripts')
<script>
    // Auto-submit form on search input (with debounce)
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    }
    
    // Prevent modal from triggering when clicking on badges
    document.querySelectorAll('.event-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.badge')) {
                e.stopPropagation();
            }
        });
    });
</script>
@endpush
@endsection