@extends('layouts.app')

@section('title', 'ISO Obtained - About Us')

@section('content')
<style>
    /* CSS Variables - Enhanced Color Scheme */
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

    /* ISO Entry Cards */
    .iso-entry-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .iso-entry-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .iso-entry-image {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: contain;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: block;
        transition: transform 0.5s ease;
        cursor: pointer;
    }

    .iso-entry-image-wrapper {
        width: 100%;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        overflow: hidden;
        position: relative;
        cursor: pointer;
    }

    /* Expand icon overlay */
    .image-expand-overlay {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(5px);
        padding: 8px 12px;
        border-radius: 25px;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .iso-entry-image-wrapper:hover .image-expand-overlay {
        opacity: 1;
    }

    /* Subtle image overlay effect */
    .iso-entry-image-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(26, 109, 143, 0.1), transparent);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .iso-entry-card:hover .iso-entry-image-wrapper::after {
        opacity: 1;
    }

    .iso-entry-content {
        padding: 2rem;
        position: relative;
    }

    /* Decorative accent line in content area */
    .iso-entry-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 2rem;
        right: 2rem;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), transparent);
        transform: scaleX(0);
        transition: transform 0.4s ease;
        transform-origin: left;
    }

    .iso-entry-card:hover .iso-entry-content::before {
        transform: scaleX(1);
    }

    .iso-entry-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    /* Subtle title underline on hover */
    .iso-entry-title::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.3s ease;
    }

    .iso-entry-card:hover .iso-entry-title::after {
        width: 100%;
    }

    .iso-entry-description {
        color: var(--text-muted);
        line-height: 1.8;
        font-size: 1rem;
    }

    /* ISO Intro Section - text only, no image */
    .iso-intro-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        padding: 2rem;
        position: relative;
    }

    .iso-intro-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    /* Decorative corner accent for intro card */
    .iso-intro-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-light) 0%, transparent 100%);
        opacity: 0.1;
        border-radius: 0 0 60px 0;
        pointer-events: none;
    }

    .iso-intro-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    .iso-intro-description {
        color: var(--text-muted);
        line-height: 1.8;
        font-size: 1rem;
    }

    .iso-entries-section {
        margin-top: 3rem;
    }

    .iso-entries-divider {
        text-align: center;
        font-size: 2.0rem;
        color: var(--text-muted);
        margin: 2rem 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--primary-light);
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Description truncation and see more button */
    .iso-entry-description-wrapper {
        position: relative;
    }

    .iso-entry-description-text {
        display: block;
        line-height: 1.8;
        transition: all 0.3s ease;
        overflow: hidden; /* JS will set max-height dynamically */
    }

    .iso-entry-description-text.expanded {
        max-height: none;
        overflow: visible;
    }

    .see-more-btn {
        background: none;
        border: none;
        color: var(--primary);
        font-weight: 600;
        cursor: pointer;
        padding: 0.5rem 0;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        margin-top: 0.75rem;
    }

    .see-more-btn:hover {
        color: var(--primary-dark);
        transform: translateX(2px);
    }

    .see-more-btn i {
        transition: transform 0.3s ease;
    }

    .see-more-btn.expanded i {
        transform: rotate(180deg);
    }

    /* Subtle fade-in animation for cards */
    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .iso-entry-card {
        animation: cardFadeIn 0.6s ease-out forwards;
        opacity: 0;
    }

    .iso-entry-card:nth-child(1) { animation-delay: 0.05s; }
    .iso-entry-card:nth-child(2) { animation-delay: 0.1s; }
    .iso-entry-card:nth-child(3) { animation-delay: 0.15s; }
    .iso-entry-card:nth-child(4) { animation-delay: 0.2s; }
    .iso-entry-card:nth-child(5) { animation-delay: 0.25s; }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .iso-intro-card {
            padding: 1.5rem;
        }

        .iso-intro-title {
            font-size: 1.3rem;
        }

        .iso-entry-image-wrapper {
            min-height: 200px;
        }

        .iso-entry-content {
            padding: 1.5rem;
        }
        
        .image-expand-overlay {
            padding: 6px 10px;
            font-size: 12px;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Certifications</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Industry-leading certifications that guarantee quality and compliance.</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Learn More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    @php
        $isoIntro = App\Models\IsoObtained::where('is_active', true)
            ->where('is_intro', true)
            ->where('status', 'published')
            ->first();
        $isoEntries = App\Models\IsoObtained::where('is_active', true)
            ->where('is_intro', false)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp

    <!-- ISO Introduction Section (at top, no image) -->
    @if($isoIntro)
        <div class="row">
            <div class="col-12">
                <div class="iso-intro-card">
                    <h2 class="iso-intro-title">{{ $isoIntro->title }}</h2>
                    <div class="iso-intro-description">
                        {!! nl2br(e($isoIntro->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ISO Entries Section (regular entries with images) -->
    @if($isoEntries->isNotEmpty())
        <div class="row">
            <div class="col-12">
                @foreach($isoEntries as $entry)
                    <div class="iso-entry-card">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="iso-entry-image-wrapper clickable-image" data-full-image="{{ $entry->image_url }}" data-image-title="{{ $entry->title }}">
                                @if($entry->image && !str_contains($entry->image_url, 'default-image.png'))
                                    <img src="{{ $entry->image_url }}" alt="{{ $entry->title }}" class="iso-entry-image">
                                    <div class="image-expand-overlay">
                                        <i class="fas fa-expand-alt me-1"></i> Expand
                                    </div>
                                @else
                                    <i class="fas fa-certificate" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="iso-entry-content">
                                    <h2 class="iso-entry-title">{{ $entry->title }}</h2>
                                    <div class="iso-entry-description-wrapper">
                                        <div class="iso-entry-description-text" data-entry-id="{{ $entry->id }}">
                                            {!! nl2br(e($entry->description)) !!}
                                        </div>
                                            <button class="see-more-btn d-none" onclick="toggleDescription(this, {{ $entry->id }})">
                                                <span>See more</span>
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif(!$isoIntro)
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h3>No ISO Entries Available</h3>
            <p>We're currently updating our ISO obtained information. Please check back later.</p>
        </div>
    @endif
</div>

<!-- Fullscreen Image Preview Modal -->
<div id="imagePreviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #000; z-index: 10000; overflow: hidden;">
    <!-- Top Blur Overlay -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 75px; background: linear-gradient(to bottom, rgba(0,0,0,0.9), rgba(0,0,0,0.45), transparent); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 10000; pointer-events: none;"></div>

    <!-- Bottom Blur Overlay -->
    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 75px; background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.45), transparent); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 10000; pointer-events: none;"></div>

    <!-- Top Controls -->
    <div style="position: absolute; top: 20px; left: 20px; right: 20px; z-index: 10001; display: flex; justify-content: space-between; align-items: center;">
        <div class="zoom-controls">
            <button type="button" class="btn btn-light btn-sm rounded-circle me-2" id="zoomOutBtn" title="Zoom Out" style="width: 40px; height: 40px;">
                <i class="fas fa-search-minus"></i>
            </button>
            <span class="text-white mx-2" id="zoomLevel" style="font-size: 14px; background: rgba(0,0,0,0.5); padding: 5px 10px; border-radius: 20px;">100%</span>
            <button type="button" class="btn btn-light btn-sm rounded-circle ms-2" id="zoomInBtn" title="Zoom In" style="width: 40px; height: 40px;">
                <i class="fas fa-search-plus"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm rounded-circle ms-2" id="resetZoomBtn" title="Reset Zoom" style="width: 40px; height: 40px;">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
        <button id="closePreviewBtn" style="background: rgba(255,255,255,0.2); border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; color: white; font-size: 24px; backdrop-filter: blur(8px);">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Image Container -->
    <div id="fullscreenImageContainer" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; cursor: grab; overflow: hidden;">
        <img id="fullscreenPreviewImage" src="" alt="Preview" style="max-width: 90%; max-height: 90vh; object-fit: contain; transition: transform 0.2s ease; user-select: none;">
    </div>

    <!-- Bottom Controls -->
    <div style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; z-index: 10001;">
        <button type="button" class="btn btn-light rounded-pill" id="downloadImageBtn" style="backdrop-filter: blur(8px); background: rgba(255,255,255,0.9); padding: 10px 20px;">
            <i class="fas fa-download me-2"></i>
            Download
        </button>
    </div>
</div>

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Smooth scroll indicator
    document.querySelector('.hero-scroll-indicator')?.addEventListener('click', function() {
        const target = document.querySelector('.iso-intro-card') || document.querySelector('.iso-entry-card');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    // Toggle description expansion and sync heights
    function syncDescriptionHeights() {
        document.querySelectorAll('.iso-entry-card').forEach(card => {
            const imgWrap = card.querySelector('.iso-entry-image-wrapper');
            const descText = card.querySelector('.iso-entry-description-text');
            const title = card.querySelector('.iso-entry-title');
            const content = card.querySelector('.iso-entry-content');
            const btn = card.querySelector('.see-more-btn');
            if (!imgWrap || !descText || !title || !content) return;

            // Image wrapper height (works for images or icon placeholders)
            const imgH = imgWrap.clientHeight;
            const contentStyle = window.getComputedStyle(content);
            const padTop = parseFloat(contentStyle.paddingTop) || 0;
            const padBottom = parseFloat(contentStyle.paddingBottom) || 0;
            const titleH = title.clientHeight || 0;
            const buffer = 12; // small buffer for spacing
            const available = Math.max(0, Math.floor(imgH - padTop - padBottom - titleH - buffer));

            // If currently expanded, leave it expanded
            if (descText.classList.contains('expanded')) {
                if (btn) btn.classList.remove('d-none');
                descText.style.maxHeight = null;
                descText.style.overflow = 'visible';
                return;
            }

            // Collapse: set max-height to available space
            descText.style.maxHeight = available + 'px';
            descText.style.overflow = 'hidden';

            // Decide whether to show See More button based on actual content height
            // Use scrollHeight which is the real rendered height of content
            // If scrollHeight is within tolerance of available, treat as equal and hide button
            const tolerance = 4; // pixels
            const contentHeight = descText.scrollHeight;
            const diff = Math.abs(contentHeight - available);
            const needsToggle = contentHeight > available + tolerance;

            if (btn) {
                if (needsToggle) {
                    btn.classList.remove('d-none');
                    btn.querySelector('span').textContent = 'See more';
                    btn.classList.remove('expanded');
                } else {
                    // Hide button when content fits the image (or is effectively equal)
                    btn.classList.add('d-none');
                    // Ensure content isn't accidentally cut off due to tiny rounding
                    if (diff <= tolerance) {
                        descText.style.maxHeight = contentHeight + 'px';
                        descText.style.overflow = 'hidden';
                    }
                }
            }
        });
    }

    function toggleDescription(button, entryId) {
        const descElement = document.querySelector(`[data-entry-id="${entryId}"]`);
        if (!descElement) return;

        const isExpanded = descElement.classList.contains('expanded');

        if (isExpanded) {
            descElement.classList.remove('expanded');
            button.classList.remove('expanded');
            button.querySelector('span').textContent = 'See more';
            // restore collapsed max-height
            syncDescriptionHeights();
        } else {
            descElement.classList.add('expanded');
            button.classList.add('expanded');
            button.querySelector('span').textContent = 'See less';
            // expand fully
            descElement.style.maxHeight = null;
            descElement.style.overflow = 'visible';
            // ensure button visible and rotated
            button.classList.add('expanded');
        }
    }

    // Run on load and resize; also after images load
    window.addEventListener('load', function() {
        syncDescriptionHeights();
    });

    let _isoResizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(_isoResizeTimeout);
        _isoResizeTimeout = setTimeout(syncDescriptionHeights, 150);
    });

    // Re-sync after each image loads
    document.querySelectorAll('.iso-entry-image').forEach(img => {
        if (!img.complete) {
            img.addEventListener('load', function() {
                syncDescriptionHeights();
            });
        }
    });

    // Fullscreen Image Preview Functionality
    $(document).ready(function() {
        let currentZoom = 1;
        let isPanning = false;
        let startX = 0;
        let startY = 0;
        let translateX = 0;
        let translateY = 0;
        
        // Get modal elements
        const modal = document.getElementById('imagePreviewModal');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const resetZoomBtn = document.getElementById('resetZoomBtn');
        const closeBtn = document.getElementById('closePreviewBtn');
        const downloadBtn = document.getElementById('downloadImageBtn');
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
                if (imageContainer) imageContainer.style.cursor = 'grabbing';
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
                if (previewImage) {
                    const maxTranslateX = (previewImage.clientWidth * currentZoom - previewImage.clientWidth) / 2;
                    const maxTranslateY = (previewImage.clientHeight * currentZoom - previewImage.clientHeight) / 2;
                    
                    translateX = Math.min(Math.max(translateX, -maxTranslateX), maxTranslateX);
                    translateY = Math.min(Math.max(translateY, -maxTranslateY), maxTranslateY);
                }
                
                updateTransform();
                e.preventDefault();
            }
        }
        
        function stopPan() {
            isPanning = false;
            if (imageContainer) imageContainer.style.cursor = 'grab';
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
        
        // Function to show fullscreen image preview
        function showImagePreview(imageUrl, title = 'Image Preview') {
            if (previewImage) {
                previewImage.src = imageUrl;
                previewImage.alt = title;
                
                // Reset zoom when loading new image
                currentZoom = 1;
                translateX = 0;
                translateY = 0;
                updateZoom();
            }
            
            if (modal) {
                modal.style.display = 'flex';
                modal.style.flexDirection = 'column';
            }
        }
        
        // Add event listeners for zoom controls
        if (zoomInBtn) zoomInBtn.addEventListener('click', zoomIn);
        if (zoomOutBtn) zoomOutBtn.addEventListener('click', zoomOut);
        if (resetZoomBtn) resetZoomBtn.addEventListener('click', resetZoom);
        
        // Close modal
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                if (modal) modal.style.display = 'none';
                resetZoom();
            });
        }
        
        // Download image
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                if (previewImage && previewImage.src) {
                    const link = document.createElement('a');
                    link.href = previewImage.src;
                    link.download = 'iso-certificate-image.jpg';
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
            
            // Touch events for mobile
            imageContainer.addEventListener('touchstart', startPan);
            window.addEventListener('touchmove', pan);
            window.addEventListener('touchend', stopPan);
        }
        
        // Close on background click
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    resetZoom();
                }
            });
        }
        
        // Close on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
                modal.style.display = 'none';
                resetZoom();
            }
        });
        
        // Handle image click events for ISO entries
        function attachImageClickHandlers() {
            // Handle click on image wrapper
            $('.clickable-image').off('click').on('click', function(e) {
                e.stopPropagation();
                const imageUrl = $(this).data('full-image');
                const imageTitle = $(this).data('image-title') || 'ISO Certificate Image';
                if (imageUrl && !imageUrl.includes('default-image.png')) {
                    showImagePreview(imageUrl, imageTitle);
                }
            });
            
            // Handle click on expand overlay
            $('.image-expand-overlay').off('click').on('click', function(e) {
                e.stopPropagation();
                const parent = $(this).closest('.clickable-image');
                const imageUrl = parent.data('full-image');
                const imageTitle = parent.data('image-title') || 'ISO Certificate Image';
                if (imageUrl && !imageUrl.includes('default-image.png')) {
                    showImagePreview(imageUrl, imageTitle);
                }
            });
        }
        
        // Initial attachment of image click handlers
        attachImageClickHandlers();
        
        // Re-attach handlers after any dynamic content changes (if needed)
        // Since content is static here, this is mainly for completeness
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        attachImageClickHandlers();
                    }
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });
        }
    });
</script>

<!-- Bootstrap CSS (optional, for button styling) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection