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
                                <img src="{{ $imageUrl }}" alt="{{ $announcement->title }}" class="announcement-image clickable-image" data-full-image="{{ $imageUrl }}" data-image-title="{{ $announcement->title }}" style="cursor: pointer;">
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

<!-- Announcement Detail Modal -->
<div id="announcementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle"></h2>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-image-container position-relative">
                <img id="modalImage" class="modal-image clickable-preview-image" src="" alt="" style="cursor: pointer; width: 100%; max-height: 400px; object-fit: cover; display: block; border-radius: 8px;">
                <div class="image-expand-icon" style="position: absolute; bottom: 12px; right: 12px; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); padding: 8px 12px; border-radius: 25px; color: white; font-size: 14px; cursor: pointer; transition: all 0.2s ease; z-index: 10;">
                    <i class="fas fa-expand-alt me-1"></i> Expand
                </div>
            </div>
            <div class="modal-date" id="modalDate"></div>
            <div class="modal-description" id="modalDescription"></div>
        </div>
    </div>
</div>

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let searchTimeout;
    let currentPage = 1;
    let isLoading = false;
    
    // Zoom variables
    let currentZoom = 1;
    let isPanning = false;
    let startX = 0;
    let startY = 0;
    let translateX = 0;
    let translateY = 0;
    
    // Create fullscreen image preview modal
    function createImagePreviewModal() {
        // Check if modal already exists
        if ($('#imagePreviewModal').length) {
            return;
        }
        
        // Create a standalone fullscreen modal (not using Bootstrap modal to ensure full control)
const modalHTML = `
    <div id="imagePreviewModal" style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        z-index: 10000;
        overflow: hidden;
    ">

        <!-- Top Blur Overlay -->
        <div style="
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 75px;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,0.9),
                rgba(0,0,0,0.45),
                transparent
            );
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 10000;
            pointer-events: none;
        "></div>

        <!-- Bottom Blur Overlay -->
        <div style="
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 75px;
            background: linear-gradient(
                to top,
                rgba(0,0,0,0.9),
                rgba(0,0,0,0.45),
                transparent
            );
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 10000;
            pointer-events: none;
        "></div>

        <!-- Top Controls -->
        <div style="
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            z-index: 10001;
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div class="zoom-controls">
                <button type="button"
                    class="btn btn-light btn-sm rounded-circle me-2"
                    id="zoomOutBtn"
                    title="Zoom Out">
                    <i class="fas fa-search-minus"></i>
                </button>

                <span class="text-white mx-2" id="zoomLevel">
                    100%
                </span>

                <button type="button"
                    class="btn btn-light btn-sm rounded-circle ms-2"
                    id="zoomInBtn"
                    title="Zoom In">
                    <i class="fas fa-search-plus"></i>
                </button>

                <button type="button"
                    class="btn btn-light btn-sm rounded-circle ms-2"
                    id="resetZoomBtn"
                    title="Reset Zoom">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>

            <button id="closePreviewBtn" style="
                background: rgba(255,255,255,0.2);
                border: none;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                cursor: pointer;
                color: white;
                font-size: 24px;
                backdrop-filter: blur(8px);
            ">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Image Container -->
        <div id="fullscreenImageContainer" style="
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            cursor: grab;
            overflow: hidden;
        ">
            <img
                id="fullscreenPreviewImage"
                src=""
                alt="Preview"
                style="
                    max-width: 90%;
                    max-height: 90vh;
                    object-fit: contain;
                    transition: transform 0.2s ease;
                    user-select: none;
                "
            >
        </div>

        <!-- Bottom Controls -->
        <div style="
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 10001;
        ">
            <button
                type="button"
                class="btn btn-light rounded-pill"
                id="downloadImageBtn"
                style="
                    backdrop-filter: blur(8px);
                    background: rgba(255,255,255,0.9);
                "
            >
                <i class="fas fa-download me-2"></i>
                Download
            </button>
        </div>
    </div>
`;
        
        $('body').append(modalHTML);
        
        // Get elements
        const modal = document.getElementById('imagePreviewModal');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const resetZoomBtn = document.getElementById('resetZoomBtn');
        const closeBtn = document.getElementById('closePreviewBtn');
        const downloadBtn = document.getElementById('downloadFullscreenBtn');
        const previewImage = document.getElementById('fullscreenPreviewImage');
        const imageContainer = document.getElementById('fullscreenImageContainer');
        const zoomLevel = document.getElementById('zoomLevel');
        
        // Zoom functions
        function zoomIn() {
            if (currentZoom < 3) {
                currentZoom += 0.25;
                updateZoom();
            }
        }
        
        function zoomOut() {
            if (currentZoom > 0.5) {
                currentZoom -= 0.25;
                updateZoom();
            }
        }
        
        function resetZoom() {
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            updateZoom();
            updateTransform();
        }
        
        function updateZoom() {
            if (zoomLevel) {
                zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
            }
            updateTransform();
        }
        
        function updateTransform() {
            if (previewImage) {
                previewImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
            }
        }
        
        // Pan functionality
        function startPan(e) {
            if (currentZoom > 1) {
                isPanning = true;
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                startX = clientX - translateX;
                startY = clientY - translateY;
                imageContainer.style.cursor = 'grabbing';
                e.preventDefault();
            }
        }
        
        function pan(e) {
            if (isPanning && currentZoom > 1) {
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                translateX = clientX - startX;
                translateY = clientY - startY;
                
                // Limit panning
                const maxTranslateX = (previewImage.clientWidth * currentZoom - previewImage.clientWidth) / 2;
                const maxTranslateY = (previewImage.clientHeight * currentZoom - previewImage.clientHeight) / 2;
                
                translateX = Math.min(Math.max(translateX, -maxTranslateX), maxTranslateX);
                translateY = Math.min(Math.max(translateY, -maxTranslateY), maxTranslateY);
                
                updateTransform();
                e.preventDefault();
            }
        }
        
        function stopPan() {
            isPanning = false;
            imageContainer.style.cursor = 'grab';
        }
        
        function handleWheelZoom(e) {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.1 : 0.1;
            const newZoom = currentZoom + delta;
            
            if (newZoom >= 0.5 && newZoom <= 3) {
                currentZoom = newZoom;
                updateZoom();
            }
        }
        
        // Add event listeners
        if (zoomInBtn) zoomInBtn.addEventListener('click', zoomIn);
        if (zoomOutBtn) zoomOutBtn.addEventListener('click', zoomOut);
        if (resetZoomBtn) resetZoomBtn.addEventListener('click', resetZoom);
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
                resetZoom();
            });
        }
        
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                if (previewImage && previewImage.src) {
                    const link = document.createElement('a');
                    link.href = previewImage.src;
                    link.download = 'announcement-image.jpg';
                    link.click();
                }
            });
        }
        
        // Pan events
        if (imageContainer) {
            imageContainer.addEventListener('mousedown', startPan);
            window.addEventListener('mousemove', pan);
            window.addEventListener('mouseup', stopPan);
            imageContainer.addEventListener('wheel', handleWheelZoom);
            
            // Touch events
            imageContainer.addEventListener('touchstart', startPan);
            window.addEventListener('touchmove', pan);
            window.addEventListener('touchend', stopPan);
        }
        
        // Close on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                resetZoom();
            }
        });
        
        // Close on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
                resetZoom();
            }
        });
    }
    
    // Function to show fullscreen image preview
    function showImagePreview(imageUrl, title = 'Image Preview') {
        createImagePreviewModal();
        
        const modal = document.getElementById('imagePreviewModal');
        const previewImg = document.getElementById('fullscreenPreviewImage');
        
        if (previewImg) {
            previewImg.src = imageUrl;
            previewImg.alt = title;
            
            // Reset zoom when loading new image
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            updateTransform();
        }
        
        if (modal) {
            modal.style.display = 'flex';
            modal.style.flexDirection = 'column';
        }
    }
    
    // Helper function to update transform
    function updateTransform() {
        const previewImage = document.getElementById('fullscreenPreviewImage');
        if (previewImage) {
            previewImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
        }
    }

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
                
                // Reattach image click handlers
                attachImageClickHandlers();
                
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
    
    // Function to attach image click handlers
    function attachImageClickHandlers() {
        // Handle clickable images in announcement cards
        $('.clickable-image').off('click').on('click', function(e) {
            e.stopPropagation();
            const imageUrl = $(this).data('full-image') || $(this).attr('src');
            const imageTitle = $(this).data('image-title') || 'Announcement Image';
            if (imageUrl && !imageUrl.includes('default-image.png')) {
                showImagePreview(imageUrl, imageTitle);
            }
        });
        
        // Handle expand icon in modal
        $('.image-expand-icon').off('click').on('click', function(e) {
            e.stopPropagation();
            const modalImage = $('#modalImage');
            const imageUrl = modalImage.attr('src');
            const imageTitle = $('#modalTitle').text() || 'Announcement Image';
            if (imageUrl && imageUrl !== '') {
                showImagePreview(imageUrl, imageTitle);
            }
        });
        
        // Handle clickable preview image in modal
        $('.clickable-preview-image').off('click').on('click', function(e) {
            e.stopPropagation();
            const imageUrl = $(this).attr('src');
            const imageTitle = $('#modalTitle').text() || 'Announcement Image';
            if (imageUrl && imageUrl !== '') {
                showImagePreview(imageUrl, imageTitle);
            }
        });
    }

    // Read more functionality - using data attributes
    $(document).on('click', '.read-more-btn', function() {
        const title = $(this).data('title');
        const date = $(this).data('date');
        const description = $(this).data('description');
        let imageUrl = $(this).data('image');
        
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
            $('.modal-image-container').show();
            $('.image-expand-icon').show();
        } else {
            $('#modalImage').hide();
            $('.modal-image-container').hide();
            $('.image-expand-icon').hide();
        }
        
        // Show modal
        $('#announcementModal').addClass('active');
        
        // Reattach image click handlers for modal
        setTimeout(function() {
            attachImageClickHandlers();
        }, 100);
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
    
    // Initial attachment of image click handlers
    attachImageClickHandlers();
});
</script>

<!-- Bootstrap CSS (if not already included) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection