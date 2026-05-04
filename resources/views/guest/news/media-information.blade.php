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
                           style="border-left: none;">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Buttons -->
    <div class="d-flex justify-content-center gap-2 mb-5 flex-wrap">
        <button class="btn btn-outline-primary rounded-pill filter-btn active" data-type="all">
            <i class="fas fa-th-large me-1"></i> All
        </button>
        <button class="btn btn-outline-primary rounded-pill filter-btn" data-type="event">
            <i class="fas fa-calendar-alt me-1"></i> Events Only
        </button>
        <button class="btn btn-outline-success rounded-pill filter-btn" data-type="activity">
            <i class="fas fa-users me-1"></i> Activities Only
        </button>
    </div>
    
    <!-- Events Container -->
    <div class="row g-4" id="eventsContainer">
        @foreach($events as $item)
        <div class="col-md-6 col-lg-4 event-item" data-type="{{ $item->type }}" data-title="{{ strtolower($item->title) }}" data-description="{{ strtolower($item->description) }}">
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
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination" id="pagination">
                <!-- Pagination will be generated by JavaScript -->
            </ul>
        </nav>
    </div>
    
    <!-- No results message -->
    <div id="noResultsMessage" class="text-center py-5" style="display: none;">
        <div class="empty-state">
            <i class="fas fa-search fa-5x text-muted mb-4"></i>
            <h3 class="text-muted mb-3">No matching events or activities found</h3>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
            <button class="btn btn-primary rounded-pill px-4" onclick="resetFilters()">
                <i class="fas fa-redo me-2"></i> Reset Filters
            </button>
        </div>
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
    }
    
    .filter-btn.active {
        background: #3988BD;
        border-color: #3988BD;
        color: white;
    }
    
    .filter-btn:hover:not(.active) {
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
    
    /* Pagination styling */
    .pagination {
        gap: 5px;
    }
    
    .pagination .page-item .page-link {
        border-radius: 10px;
        padding: 8px 16px;
        color: #0E334C;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .pagination .page-item.active .page-link {
        background: #3988BD;
        border-color: #3988BD;
        color: white;
    }
    
    .pagination .page-item .page-link:hover {
        background: #3988BD;
        border-color: #3988BD;
        color: white;
        transform: translateY(-2px);
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
    
    @media (max-width: 768px) {
        .description-text {
            max-height: 250px;
        }
        
        .modal-body {
            padding: 1.5rem !important;
        }
        
        .filter-btn {
            padding: 6px 16px;
            font-size: 14px;
        }
        
        .pagination .page-item .page-link {
            padding: 6px 12px;
            font-size: 14px;
        }
    }
</style>

@push('scripts')
<script>
    let currentPage = 1;
    const itemsPerPage = 9;
    let filteredItems = [];
    let allItems = [];

    document.addEventListener('DOMContentLoaded', function() {
        // Get all event items
        allItems = Array.from(document.querySelectorAll('.event-item'));
        filteredItems = [...allItems];
        
        // Initialize pagination
        updatePagination();
        
        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const type = this.getAttribute('data-type');
                
                // Filter items by type
                if (type === 'all') {
                    filteredItems = [...allItems];
                } else {
                    filteredItems = allItems.filter(item => item.getAttribute('data-type') === type);
                }
                
                // Also apply search filter if there's search text
                applySearchFilter();
                
                currentPage = 1;
                updatePagination();
                displayItems();
            });
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                applySearchFilter();
                currentPage = 1;
                updatePagination();
                displayItems();
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
        
        // Initial display
        displayItems();
    });
    
    function applySearchFilter() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        
        if (searchTerm === '') {
            // Just use the type-filtered items
            const activeFilter = document.querySelector('.filter-btn.active');
            const type = activeFilter ? activeFilter.getAttribute('data-type') : 'all';
            
            if (type === 'all') {
                filteredItems = [...allItems];
            } else {
                filteredItems = allItems.filter(item => item.getAttribute('data-type') === type);
            }
        } else {
            // Apply search to currently filtered items
            const activeFilter = document.querySelector('.filter-btn.active');
            const type = activeFilter ? activeFilter.getAttribute('data-type') : 'all';
            
            let typeFiltered = [];
            if (type === 'all') {
                typeFiltered = [...allItems];
            } else {
                typeFiltered = allItems.filter(item => item.getAttribute('data-type') === type);
            }
            
            // Filter by search term
            filteredItems = typeFiltered.filter(item => {
                const title = item.getAttribute('data-title');
                const description = item.getAttribute('data-description');
                return title.includes(searchTerm) || description.includes(searchTerm);
            });
        }
    }
    
    function updatePagination() {
        const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
        const paginationElement = document.getElementById('pagination');
        const noResultsMessage = document.getElementById('noResultsMessage');
        
        if (filteredItems.length === 0) {
            if (paginationElement) paginationElement.innerHTML = '';
            if (noResultsMessage) noResultsMessage.style.display = 'block';
            return;
        }
        
        if (noResultsMessage) noResultsMessage.style.display = 'none';
        
        let paginationHtml = '';
        
        // Previous button
        paginationHtml += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            // Show limited page numbers with ellipsis
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHtml += `
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        // Next button
        paginationHtml += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
        
        if (paginationElement) paginationElement.innerHTML = paginationHtml;
    }
    
    function displayItems() {
        // Hide all items first
        allItems.forEach(item => item.style.display = 'none');
        
        // Calculate start and end index
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        
        // Show items for current page
        const itemsToShow = filteredItems.slice(start, end);
        itemsToShow.forEach(item => {
            item.style.display = 'block';
            // Add animation
            item.style.animation = 'none';
            setTimeout(() => {
                item.style.animation = 'fadeInUp 0.5s ease-out';
            }, 10);
        });
    }
    
    function changePage(page) {
        const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        updatePagination();
        displayItems();
        
        // Scroll to top of events container
        document.getElementById('eventsContainer').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
    
    function resetFilters() {
        // Reset filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            if (btn.getAttribute('data-type') === 'all') {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        // Reset search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) searchInput.value = '';
        
        // Reset filtered items
        filteredItems = [...allItems];
        currentPage = 1;
        updatePagination();
        displayItems();
    }
    
    // Make functions global for onclick handlers
    window.changePage = changePage;
    window.resetFilters = resetFilters;
</script>
@endpush
@endsection