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
    }

    .iso-entry-image-wrapper {
        width: 100%;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
    }

    .iso-entry-content {
        padding: 2rem;
    }

    .iso-entry-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
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
    }

    .iso-intro-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .iso-intro-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
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

    @media (max-width: 768px) {
        .heFro-title {
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
    }
</style>

<!-- Hero Section -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">ISO Certificate</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Certifications and quality standards we have achieved</p>
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
        @if($isoIntro)
            <div class="iso-entries-divider">
                <span>Our Certifications</span>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                @foreach($isoEntries as $entry)
                    <div class="iso-entry-card">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="iso-entry-image-wrapper">
                                    @if($entry->image_url && !str_contains($entry->image_url, 'default-image.png'))
                                        <img src="{{ $entry->image_url }}" alt="{{ $entry->title }}" class="iso-entry-image">
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
</script>
@endsection