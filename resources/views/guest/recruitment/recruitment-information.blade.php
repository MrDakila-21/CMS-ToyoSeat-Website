@extends('layouts.app')

@section('title', 'Recruitment Information')

@section('content')
<!-- Hero Section - Matching Overview Module Design -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Recruitment Information</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Join our team and build your future with Toyo Seat Philippines Corporation</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">View Opportunities</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Recruitment Listings -->
    @if(isset($recruitments) && count($recruitments) > 0)
        <div class="recruitment-list fade-in-up">
            @foreach($recruitments as $index => $post)
            <div class="recruitment-item">
                <div class="recruitment-header">
                    <div class="recruitment-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="recruitment-info">
                        <h3 class="recruitment-title">{{ $post->title }}</h3>
                        <div class="recruitment-date">
                            <i class="far fa-calendar-alt"></i> Posted: {{ $post->created_at->format('F d, Y') }}
                        </div>
                    </div>
                </div>
                <div class="recruitment-body">
                    <div class="recruitment-description" id="desc-{{ $post->id }}">
                        @php
                            $description = $post->description;
                            $wordCount = str_word_count(strip_tags($description));
                            $isLongDescription = $wordCount > 70;
                        @endphp
                        
                        @if($isLongDescription)
                            <span class="short-description">
                                {!! nl2br(e(\Illuminate\Support\Str::words($description, 70, ''))) !!}
                            </span>
                            <span class="full-description" style="display: none;">
                                {!! nl2br(e($description)) !!}
                            </span>
                            <button class="read-more-btn" data-id="{{ $post->id }}">
                                <span class="read-more-text">Read More</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        @else
                            {!! nl2br(e($description)) !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="recruitment-divider"></div>
            @endforeach
        </div>
    @else
        <div class="empty-state fade-in-up">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h4 class="empty-state-title">No Job Openings Available</h4>
            <p class="empty-state-message">Please check back later for new opportunities.</p>
        </div>
    @endif
</div>

<style>
/* CSS Variables - Matching Overview Module */
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
    --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

/* Hero Section - Matching Overview */
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

/* Recruitment Listings - Clean Layout */
.recruitment-list {
    max-width: 1000px;
    margin: 0 auto;
}

.recruitment-item {
    padding: 1.5rem 0;
}

.recruitment-header {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.recruitment-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-lighter), #FFFFFF);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.2rem;
    flex-shrink: 0;
    border: 1px solid rgba(26, 109, 143, 0.2);
}

.recruitment-info {
    flex: 1;
}

.recruitment-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.recruitment-date {
    font-size: 0.8rem;
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.recruitment-body {
    margin-top: 0.5rem;
    padding-left: 4rem;
}

.recruitment-description {
    color: #555;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Read More Button */
.read-more-btn {
    background: none;
    border: none;
    color: var(--primary);
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0.5rem 0 0 0;
    margin-top: 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    transition: var(--transition);
}

.read-more-btn:hover {
    color: var(--primary-dark);
    gap: 0.5rem;
}

.read-more-btn i {
    font-size: 0.7rem;
    transition: transform 0.3s ease;
}

.read-more-btn.expanded i {
    transform: rotate(180deg);
}

/* Divider - Full width line */
.recruitment-divider {
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    border: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gray-border), var(--gray-border), transparent);
}

.recruitment-list .recruitment-divider:last-child {
    display: none;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-border);
    max-width: 500px;
    margin: 0 auto;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: var(--gray-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem auto;
    color: var(--text-muted);
    font-size: 2rem;
}

.empty-state-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.empty-state-message {
    color: var(--text-muted);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 0.95rem;
        padding: 0 1rem;
    }
    
    .recruitment-item {
        padding: 1rem 0;
    }
    
    .recruitment-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .recruitment-body {
        padding-left: 0;
    }
    
    .recruitment-title {
        font-size: 1.1rem;
    }
}
</style>

<script>
    // Scroll indicator functionality
    document.querySelector('.hero-scroll-indicator')?.addEventListener('click', function() {
        const nextSection = document.querySelector('.recruitment-list');
        if (nextSection) {
            nextSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
    
    // Read More functionality
    document.querySelectorAll('.read-more-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const shortDesc = this.parentElement.querySelector('.short-description');
            const fullDesc = this.parentElement.querySelector('.full-description');
            const isExpanded = this.classList.contains('expanded');
            
            if (!isExpanded) {
                shortDesc.style.display = 'none';
                fullDesc.style.display = 'inline';
                this.classList.add('expanded');
                this.querySelector('.read-more-text').textContent = 'Read Less';
            } else {
                shortDesc.style.display = 'inline';
                fullDesc.style.display = 'none';
                this.classList.remove('expanded');
                this.querySelector('.read-more-text').textContent = 'Read More';
            }
        });
    });
</script>
@endsection