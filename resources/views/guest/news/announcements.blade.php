{{-- resources/views/guest/news/announcements.blade.php --}}
@extends('layouts.app')

@section('title', 'Announcements - News')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/guest/announcements.css') }}">
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Announcements</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Stay updated with our latest news and announcements</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Discover More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-4 py-md-5">
    <!-- Search Bar -->
    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" 
               id="searchInput" 
               class="search-input" 
               placeholder="Search announcements by title or description..."
               autocomplete="off">
        <i class="fas fa-times search-clear" id="clearSearch"></i>
    </div>

    <!-- Announcements Container -->
    <div id="announcementsContainer">
        @if(isset($announcements) && $announcements->isNotEmpty())
            <div class="row g-3 g-md-4">
                @foreach($announcements as $announcement)
                @php
                    $imageUrl = $announcement->image_url;
                    $description = strip_tags($announcement->description);
                    $wordCount = str_word_count($description);
                    $needsReadMore = $wordCount > 50;
                    $shortDescription = $needsReadMore ? Str::limit($description, 150) : $description;
                @endphp
                <div class="col-12 col-md-6">
                    <div class="announcement-card">
                        <div class="announcement-image-wrapper">
                            @if($imageUrl && $imageUrl != asset('images/default-image.png'))
                                <img src="{{ $imageUrl }}" alt="{{ $announcement->title }}" class="announcement-image">
                            @else
                                <div class="announcement-image" style="display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-newspaper" style="font-size: 48px; color: #ccc;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="announcement-content">
                            <div class="announcement-date">
                                <i class="far fa-calendar-alt"></i>
                                {{ date('F j, Y', strtotime($announcement->date)) }}
                            </div>
                            <h3 class="announcement-title">{{ $announcement->title }}</h3>
                            <div class="announcement-description">
                                {!! nl2br(e($shortDescription)) !!}
                                @if($needsReadMore)
                                    <span class="ellipsis">...</span>
                                @endif
                            </div>
                            @if($needsReadMore)
                                <button class="read-more-btn" data-id="{{ $announcement->id }}" data-title="{{ e($announcement->title) }}" data-date="{{ $announcement->date }}" data-description="{{ e($announcement->description) }}" data-image="{{ $imageUrl }}">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="pagination-container">
                {{ $announcements->appends(['search' => request('search')])->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>No Announcements Found</h3>
                <p>There are no announcements available at the moment. Please check back later.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div id="announcementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle"></h2>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <img id="modalImage" class="modal-image" src="" alt="">
            <div class="modal-date" id="modalDate"></div>
            <div class="modal-description" id="modalDescription"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let searchTimeout;
    let currentPage = 1;
    let isLoading = false;

    // Search functionality with debounce
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        
        if (searchTerm.length > 0) {
            $('#clearSearch').show();
        } else {
            $('#clearSearch').hide();
        }
        
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadAnnouncements(searchTerm, currentPage);
        }, 500);
    });

    // Clear search
    $('#clearSearch').on('click', function() {
        $('#searchInput').val('').trigger('input');
        $(this).hide();
    });

    // Handle pagination clicks using event delegation
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        if (isLoading) return;
        
        const url = $(this).attr('href');
        if (url && url !== '#') {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const page = urlParams.get('page');
            const searchTerm = $('#searchInput').val();
            if (page && page !== currentPage) {
                currentPage = page;
                loadAnnouncements(searchTerm, currentPage);
            }
        }
    });

    // Load announcements via AJAX
    function loadAnnouncements(search = '', page = 1) {
        if (isLoading) return;
        isLoading = true;
        
        $('#announcementsContainer').html('<div class="loading-spinner"><div class="spinner"></div></div>');
        
        $.ajax({
            url: "{{ route('guest.news.announcements') }}",
            type: 'GET',
            data: {
                search: search,
                page: page
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Extract only the announcements container content
                const tempDiv = $('<div>').html(response);
                const newContent = tempDiv.find('#announcementsContainer').html();
                if (newContent) {
                    $('#announcementsContainer').html(newContent);
                } else {
                    $('#announcementsContainer').html(response);
                }
                // Scroll to top of announcements section
                $('html, body').animate({
                    scrollTop: $('#announcementsContainer').offset().top - 100
                }, 300);
                isLoading = false;
            },
            error: function(xhr) {
                console.error('Error loading announcements:', xhr);
                $('#announcementsContainer').html('<div class="empty-state"><div class="empty-icon"><i class="fas fa-exclamation-triangle"></i></div><h3>Error Loading Announcements</h3><p>Please try again later.</p></div>');
                isLoading = false;
            }
        });
    }

    // Read more functionality - using data attributes
    $(document).on('click', '.read-more-btn', function() {
        const title = $(this).data('title');
        const date = $(this).data('date');
        const description = $(this).data('description');
        const imageUrl = $(this).data('image');
        
        // Format the date
        const formattedDate = new Date(date).toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Set modal content
        $('#modalTitle').text(title);
        $('#modalDate').html('<i class="far fa-calendar-alt"></i> ' + formattedDate);
        $('#modalDescription').html(description.replace(/\n/g, '<br>'));
        
        // Handle image
        if (imageUrl && imageUrl !== '{{ asset("images/default-image.png") }}' && !imageUrl.includes('default-image.png')) {
            $('#modalImage').attr('src', imageUrl).show();
        } else {
            $('#modalImage').hide();
        }
        
        // Show modal
        $('#announcementModal').addClass('active');
    });

    // Modal close functionality
    $('.modal-close, .modal').on('click', function(e) {
        if (e.target === this) {
            $('#announcementModal').removeClass('active');
        }
    });

    // Close modal with escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#announcementModal').hasClass('active')) {
            $('#announcementModal').removeClass('active');
        }
    });

    // Smooth scroll for hero indicator
    $('.hero-scroll-indicator').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.search-container').offset().top - 50
        }, 800);
    });
});
</script>
@endsection