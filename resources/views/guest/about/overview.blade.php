@extends('layouts.app')

@section('title', 'Overview - About Us')

@section('content')
@php
    $content = App\Models\OverviewContent::getContent();
@endphp

<!-- Hero Section - Modern Gradient with Subtle Animation -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Company Overview</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Discover the story, values, and vision driving Toyo Seat Philippines Corporation forward</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Discover More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Business Principles Section - Enhanced Visual Cards -->
    @if($content->business_principles && count($content->business_principles) > 0)
    <div class="row mb-5">
        <div class="col-12">
            <div class="section-header text-center mb-4 fade-in-up">
                <span class="section-tag">
                    <i class="fas fa-gem me-1"></i> Core Foundation
                </span>
                <h2 class="section-title">Our Business Principles</h2>
                <div class="section-line"></div>
                <p class="section-subtitle">The guiding philosophies that shape our corporate culture and drive excellence</p>
            </div>
            <div class="row justify-content-center g-4">
                @foreach($content->business_principles as $index => $principle)
                <div class="col-md-6 col-lg-4 d-flex fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="principle-card h-100">
                        <div class="principle-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <!-- ICON REMOVED -->
                        <h4 class="principle-title">{{ $principle['title'] }}</h4>
                       <p class="principle-description" style="white-space: pre-wrap;">{{ $principle['description'] }}</p>
                        <div class="principle-hover-effect"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    
    <!-- Message from President Section - Premium Design -->
    @if($content->president_message)
    <div class="row mb-5 fade-in-up">
        <div class="col-12">
            <div class="executive-section">
                <div class="executive-badge">
                    <i class="fas fa-crown me-2"></i>Leadership Perspective
                </div>
                <div class="executive-card">
                    <div class="row g-0 align-items-stretch">
                        @if($content->president_image)
                        <div class="col-md-5 col-lg-4">
                            <div class="executive-image-wrapper">
                                <div class="executive-image-overlay"></div>
                                <img src="{{ $content->president_image_url }}"
                                     alt="{{ $content->president_name }}" 
                                     class="executive-image"
                                     loading="lazy">
                               
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-8">
                            <div class="executive-content">
                        @else
                        <div class="col-12">
                            <div class="executive-content text-center">
                        @endif
                                <div class="executive-quote">
                                    <i class="fas fa-quote-left quote-icon"></i>
                                    <p class="executive-message">{{ $content->president_message }}</p>
                                </div>
                                <div class="executive-signature">
                                    <div class="executive-name-wrapper">
                                        <h3 class="executive-name">{{ $content->president_name }}</h3>
                                        <div class="executive-name-line"></div>
                                    </div>
                                    <p class="executive-title">{{ $content->president_title }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Company Profile Section - Compact & Proportionate Layout -->
    <div class="row fade-in-up">
        <div class="col-12">
            <div class="corporate-profile">
                <div class="profile-header">
                    <span class="section-tag">
                        <i class="fas fa-building me-1"></i> Corporate Identity
                    </span>
                    <h2 class="section-title">Corporate Profile</h2>
                    <div class="section-line"></div>
                </div>
                
                <div class="profile-grid">
                    @if($content->company_profile_image)
                    <div class="profile-image-section">
                        <div class="image-container">
                            <div class="image-overlay"></div>
                           <img src="{{ $content->company_profile_image_url }}?t={{ time() }}" 
                                 alt="{{ $content->company_name }}" 
                                 class="corporate-image"
                                 loading="lazy">
                            <div class="image-caption">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Est. {{ $content->established_date ?? '1988' }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="profile-data-section">
                        <div class="corporate-header">
                            <h3 class="corporate-name">{{ $content->company_name }}</h3>
                            <div class="corporate-tagline">Excellence in Automotive Innovation</div>
                        </div>
                        
                        <div class="stats-grid">
                            @if($content->established_date)
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-label">Established</div>
                                    <div class="stat-value">{{ $content->established_date }}</div>
                                </div>
                            </div>
                            @endif
                            
                            @if($content->employees)
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-label">Team Members</div>
                                    <div class="stat-value">{{ number_format($content->employees) }}+</div>
                                </div>
                            </div>
                            @endif
                            
                            @if($content->capital)
                            <div class="stat-card stat-card-full">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-label">Investment Capital</div>
                                    <div class="stat-value">{{ $content->capital }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="data-grid modern-grid">
                            @if($content->president_representative)
                            <div class="data-row">
                                <div class="data-label">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Leadership</span>
                                </div>
                                <div class="data-value">{{ $content->president_representative }}</div>
                            </div>
                            @endif
                            
                            @if($content->business_description)
                            <div class="data-row">
                                <div class="data-label">
                                    <i class="fas fa-industry"></i>
                                    <span>Core Business</span>
                                </div>
                                <div class="data-value">{{ $content->business_description }}</div>
                            </div>
                            @endif
                            
                            @if($content->dynamic_categories && count($content->dynamic_categories) > 0)
                                @foreach($content->dynamic_categories as $key => $value)
                                    @if(!in_array($key, ['established_date', 'capital', 'president_representative', 'business_description', 'employees']) && !empty($value))
                                        @php
                                            $iconMap = [
                                                'location' => 'fa-map-marker-alt',
                                                'website' => 'fa-globe',
                                                'email' => 'fa-envelope',
                                                'phone' => 'fa-phone',
                                                'certification' => 'fa-certificate',
                                                'partners' => 'fa-handshake',
                                                'awards' => 'fa-trophy'
                                            ];
                                            $metadata = $content->category_metadata[$key] ?? [
                                                'label' => ucfirst(str_replace('_', ' ', $key)), 
                                                'icon' => $iconMap[$key] ?? 'fa-tag'
                                            ];
                                        @endphp
                                        <div class="data-row">
                                            <div class="data-label">
                                                <i class="fas {{ $metadata['icon'] }}"></i>
                                                <span>{{ $metadata['label'] }}</span>
                                            </div>
                                            <div class="data-value">{{ $value }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        
                        @if($content->company_profile)
                        <div class="company-mission">
                            <div class="mission-badge">
                                <i class="fas fa-flag-checkered me-2"></i>Our Story
                            </div>
                            <p class="mission-text">{{ $content->company_profile }}</p>
                            <div class="mission-decoration"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    padding: 2rem 0 0.5rem 0; /* REDUCED from 3rem 0 3rem 0 */
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
    padding: 0.5rem 0; /* REDUCED from 2rem 0 */
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(12px);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.4rem 1.5rem;
    border-radius: 50px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    border: 1px solid rgba(255, 255, 255, 0.25);
    margin-bottom: 1.5rem;
    transition: var(--transition);
}

.hero-badge:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.4);
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    color: white;
    margin-top: 1rem; /* Added to reduce space above title */
    margin-bottom: 0.75rem; /* REDUCED from 1rem */
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
    margin: 1rem auto; /* REDUCED from 1.5rem auto */
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
    line-height: 1.5; /* REDUCED from 1.6 */
}

.hero-scroll-indicator {
    margin-top: 1.5rem; /* REDUCED from 2rem */
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

/* Section Header */
.section-header {
    position: relative;
}

.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--primary-lighter);
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.35rem 1.25rem;
    border-radius: 30px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--primary-dark);
    margin-bottom: 0.5rem; /* REDUCED from 0.75rem */
    letter-spacing: -0.02em;
}

.section-subtitle {
    color: var(--text-muted);
    font-size: 1rem;
    max-width: 600px;
    margin: 0 auto;
}

.section-line {
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    margin: 1rem auto 1.5rem auto; /* REDUCED from 1.25rem auto 1.75rem auto */
    border-radius: 3px;
    position: relative;
}

.section-line::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    background: var(--primary-light);
    border-radius: 50%;
    opacity: 0.5;
}

/* Business Principles Cards - Enhanced with larger text and no icons */
.principle-card {
    background: white;
    padding: 2rem 1.5rem;
    border-radius: 20px;
    text-align: center;
    transition: var(--transition);
    border: 1px solid var(--gray-border);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.principle-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    transform: scaleX(0);
    transition: transform 0.4s ease;
    transform-origin: left;
}

.principle-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: transparent;
}

.principle-card:hover::before {
    transform: scaleX(1);
}

.principle-number {
    position: absolute;
    top: 1rem;
    right: 1.25rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--primary-lighter);
    letter-spacing: 1px;
    font-family: monospace;
}

/* ICON WRAPPER COMPLETELY REMOVED - No styles needed */

.principle-title {
    font-size: 1.35rem; /* INCREASED from 1.2rem */
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 0.75rem;
    margin-top: 0; /* Added to remove extra space where icon was */
}

.principle-description {
    font-size: 0.95rem; /* INCREASED from 0.9rem */
    color: var(--text-muted);
    line-height: 1.6;
    margin: 0;
}

/* Executive Section - Refined */
.executive-section {
    margin: 3rem 0;
}

.executive-badge {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    border-radius: 30px;
    margin-bottom: 1.5rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: var(--shadow-sm);
}

.executive-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
}

.executive-card:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-4px);
}

.executive-image-wrapper {
    position: relative;
    height: 100%;
    min-height: 400px;
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
    overflow: hidden;
}

.executive-image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(10, 43, 62, 0.4), rgba(26, 109, 143, 0.2));
    z-index: 1;
}

.executive-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 20%;
    transition: transform 0.6s ease;
}

.executive-image-wrapper:hover .executive-image {
    transform: scale(1.08);
}

.executive-social {
    position: absolute;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 2;
}

.social-link {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    transition: var(--transition);
    opacity: 0;
    transform: translateY(20px);
    text-decoration: none;
}

.executive-image-wrapper:hover .social-link {
    opacity: 1;
    transform: translateY(0);
}

.social-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-3px);
}

.executive-content {
    padding: 3rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: linear-gradient(135deg, white, var(--gray-light));
}

.executive-quote {
    margin-bottom: 2rem;
    position: relative;
}

.quote-icon {
    font-size: 3rem;
    color: var(--primary);
    opacity: 0.15;
    margin-bottom: 1rem;
}

.executive-message {
    font-size: 1.25rem;
    line-height: 1.7;
    color: var(--text-dark);
    font-style: italic;
    margin: 0;
    font-weight: 500;
}

.executive-signature {
    text-align: right;
}

.executive-name-wrapper {
    position: relative;
    display: inline-block;
}

.executive-name {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--primary-dark);
    margin-bottom: 0.25rem;
    letter-spacing: -0.02em;
}

.executive-name-line {
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), transparent);
    margin-top: 0.5rem;
}

.executive-title {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin: 0.5rem 0 0;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

/* Corporate Profile - More compact and proportionate */
.corporate-profile {
    margin: 2rem 0; /* REDUCED from 3rem 0 */
}

.profile-header {
    text-align: center;
    margin-bottom: 2rem; /* REDUCED from 2.5rem */
}

.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 2rem;
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
}

.profile-grid:hover {
    box-shadow: var(--shadow-xl);
}

.profile-image-section {
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    min-height: 450px; /* REDUCED from 500px */
    overflow: hidden;
    position: relative;
}

.image-container {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(10, 43, 62, 0.3), rgba(26, 109, 143, 0.15));
    z-index: 1;
}

.corporate-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.6s ease;
}

.image-container:hover .corporate-image {
    transform: scale(1.08);
}

.image-caption {
    position: absolute;
    bottom: 1.5rem;
    left: 1.5rem;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(8px);
    color: white;
    padding: 0.35rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    z-index: 2;
    font-weight: 500;
}

.profile-data-section {
    padding: 2rem; /* REDUCED from 2.5rem */
    background: white;
}

.corporate-header {
    margin-bottom: 1.5rem; /* REDUCED from 2rem */
    border-left: 4px solid var(--primary);
    padding-left: 1rem;
}

.corporate-name {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--primary-dark);
    margin-bottom: 0.25rem;
}

.corporate-tagline {
    font-size: 0.85rem;
    color: var(--text-muted);
    letter-spacing: 0.5px;
}

/* Stats Grid - Improved */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem; /* REDUCED from 2rem */
}

.stat-card {
    background: var(--gray-light);
    border-radius: 14px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: var(--transition);
}

.stat-card:hover {
    background: var(--primary-lighter);
    transform: translateY(-3px);
}

.stat-card-full {
    grid-column: span 2;
}

.stat-icon {
    width: 44px;
    height: 44px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.2rem;
    box-shadow: var(--shadow-sm);
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary-dark);
}

/* Data Grid - Clean Design */
.data-grid {
    margin-bottom: 1.5rem; /* REDUCED from 2rem */
}

.data-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.7rem 0; /* REDUCED from 0.875rem 0 */
    border-bottom: 1px solid var(--gray-border);
    transition: var(--transition);
}

.data-row:hover {
    background: var(--gray-light);
    margin: 0 -0.5rem;
    padding: 0.7rem 0.5rem; /* MATCH reduced padding */
    border-radius: 10px;
}

.data-row:last-child {
    border-bottom: none;
}

.data-label {
    flex: 0 0 35%;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-label i {
    width: 20px;
    color: var(--primary);
    font-size: 0.9rem;
}

.data-value {
    flex: 0 0 62%;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text-dark);
    text-align: right;
    word-wrap: break-word;
    word-break: break-word;
}

/* Company Mission - Enhanced */
.company-mission {
    margin-top: 1.5rem; /* REDUCED from 2rem */
    padding-top: 1.5rem; /* REDUCED from 2rem */
    border-top: 2px solid var(--gray-border);
    position: relative;
}

.mission-badge {
    display: inline-flex;
    align-items: center;
    background: var(--accent-light);
    color: var(--accent);
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.35rem 1rem;
    border-radius: 30px;
    margin-bottom: 1rem;
    letter-spacing: 0.5px;
}

.mission-text {
    font-size: 0.95rem;
    line-height: 1.6; /* SLIGHTLY REDUCED from 1.7 for compactness */
    color: var(--text-dark);
    margin: 0;
}

.mission-decoration {
    position: absolute;
    bottom: -0.5rem;
    right: 0;
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--primary-light));
    border-radius: 3px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .profile-image-section {
        min-height: 400px; /* ADJUSTED for compactness */
    }
    
    .executive-message {
        font-size: 1.1rem;
    }
}

@media (max-width: 992px) {
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .profile-grid {
        grid-template-columns: 1fr 1.2fr;
        gap: 1rem;
    }
    
    .profile-image-section {
        min-height: 380px; /* ADJUSTED for tablet */
    }
    
    .profile-data-section {
        padding: 1.75rem; /* ADJUSTED for tablet */
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card-full {
        grid-column: span 1;
    }
    
    .data-label {
        flex: 0 0 40%;
    }
    
    .data-value {
        flex: 0 0 57%;
    }
    
    .executive-message {
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .hero-section-wrapper {
        padding: 1.5rem 0 1rem 0; /* FURTHER reduced for mobile */
    }
    
    .hero-title {
        font-size: 1.8rem;
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
        margin-top: 1rem; /* FURTHER reduced */
    }
    
    .profile-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .profile-image-section {
        order: 1;
        min-height: 300px; /* ADJUSTED for mobile */
    }
    
    .corporate-image {
        height: 300px;
        object-fit: cover;
    }
    
    .image-caption {
        bottom: 1rem;
        left: 1rem;
    }
    
    .profile-data-section {
        order: 2;
        padding: 1.25rem; /* ADJUSTED for mobile */
    }
    
    .data-row {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .data-label {
        flex: auto;
        width: 100%;
    }
    
    .data-value {
        flex: auto;
        text-align: left;
        padding-left: 1.75rem;
        width: 100%;
    }
    
    .executive-content {
        padding: 1.5rem; /* REDUCED for mobile */
    }
    
    .executive-message {
        font-size: 0.9rem;
    }
    
    .executive-signature {
        text-align: left;
    }
    
    .executive-name-line {
        margin-left: 0;
    }
    
    .principle-card {
        padding: 1.5rem;
    }
    
    .principle-title {
        font-size: 1.2rem; /* Slightly smaller on mobile but still larger than before */
    }
    
    .principle-description {
        font-size: 0.9rem;
    }
    
    .executive-image-wrapper {
        min-height: 300px; /* REDUCED for mobile */
    }
    
    .executive-image {
        height: 300px;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
}

@media (max-width: 576px) {
    .hero-section-wrapper {
        padding: 1rem 0 0.75rem 0; /* MINIMAL padding for smallest screens */
    }
    
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 0.85rem;
    }
    
    .hero-badge {
        font-size: 0.65rem;
        padding: 0.3rem 1rem;
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
    
    .profile-image-section {
        min-height: 240px; /* ADJUSTED for smallest screens */
    }
    
    .corporate-image {
        height: 240px;
    }
    
    .profile-data-section {
        padding: 1rem;
    }
    
    .executive-image-wrapper {
        min-height: 260px;
    }
    
    .executive-image {
        height: 260px;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .principle-title {
        font-size: 1.1rem;
    }
    
    .executive-message {
        font-size: 0.85rem;
    }
    
    .corporate-name {
        font-size: 1.3rem;
    }
    
    .stat-card {
        padding: 0.75rem;
    }
}

/* Tablet Landscape */
@media (min-width: 769px) and (max-width: 1024px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 0.95rem;
    }
    
    .profile-grid {
        gap: 1rem;
    }
    
    .profile-image-section {
        min-height: 360px; /* ADJUSTED for tablet landscape */
    }
    
    .corporate-image {
        height: 100%;
        object-fit: cover;
    }
    
    .executive-image-wrapper {
        min-height: 340px; /* ADJUSTED for tablet landscape */
    }
    
    .executive-message {
        font-size: 1rem;
    }
}

/* Print Styles */
@media print {
    .hero-section-wrapper {
        background: #0E334C;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        padding: 0.5rem 0;
    }
    
    .hero-badge, .hero-scroll-indicator, .executive-badge, .social-link, .image-caption, .hero-particles {
        display: none;
    }
    
    .hero-title {
        font-size: 1.5rem;
        color: white;
        -webkit-text-fill-color: white;
    }
    
    .principle-card, .executive-card, .profile-grid {
        box-shadow: none;
        border: 1px solid #ddd;
        page-break-inside: avoid;
    }
    
    .corporate-image, .executive-image {
        max-width: 100%;
        height: auto;
    }
    
    .stat-card, .data-row {
        break-inside: avoid;
    }
}

/* Smooth Scroll */
html {
    scroll-behavior: smooth;
}
</style>

<!-- Font Awesome 6 -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@endsection