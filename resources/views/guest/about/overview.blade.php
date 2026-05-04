@extends('layouts.app')

@section('title', 'Overview - About Us')

@section('content')
@php
    $content = App\Models\OverviewContent::getContent();
@endphp

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Company Overview</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
        <p class="lead text-secondary">Learn more about Toyo Seat Philippines Corporation</p>
    </div>
    
    <!-- Business Principles Section - Corporate Style -->
    @if($content->business_principles && count($content->business_principles) > 0)
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h2 class="section-title" style="color: #0E334C;">Our Business Principles</h2>
                <div class="section-line"></div>
                <p class="text-muted">The foundation of our corporate philosophy and operational excellence</p>
            </div>
            <div class="row justify-content-center">
                @foreach($content->business_principles as $index => $principle)
                <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                    <div class="principle-card h-100">
                        <div class="principle-icon-wrapper">
                            @php
                                $icons = ['briefcase', 'chart-line', 'handshake', 'globe', 'users', 'medal'];
                                $icon = $icons[$index % count($icons)];
                            @endphp
                            <i class="fas fa-{{ $icon }} principle-icon"></i>
                        </div>
                        <h4 class="principle-title">{{ $principle['title'] }}</h4>
                        <p class="principle-description">{{ $principle['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    
    <!-- Message from President Section - Corporate Executive Style -->
    @if($content->president_message)
    <div class="row mb-5">
        <div class="col-12">
            <div class="executive-section">
                <div class="executive-badge">Executive Leadership</div>
                <div class="executive-card">
                    <div class="row g-0 align-items-center">
                        @if($content->president_image)
                        <div class="col-md-4">
                            <div class="executive-image-wrapper">
                                <img src="{{ Storage::url($content->president_image) }}" 
                                     alt="{{ $content->president_name }}" 
                                     class="executive-image">
                                <div class="executive-overlay"></div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="executive-content">
                        @else
                        <div class="col-12">
                            <div class="executive-content text-center">
                        @endif
                                <div class="executive-quote">
                                    <i class="fas fa-quote-left"></i>
                                    <p class="executive-message">{{ $content->president_message }}</p>
                                </div>
                                <div class="executive-signature">
                                    <h3 class="executive-name">{{ $content->president_name }}</h3>
                                    <p class="executive-title">{{ $content->president_title }}</p>
                                    <div class="signature-line"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Company Profile Section - Corporate Data Sheet -->
    <div class="row">
        <div class="col-12">
            <div class="corporate-profile">
                <div class="profile-header">
                    <h2 class="section-title">Corporate Profile</h2>
                    <div class="section-line"></div>
                </div>
                
                <div class="profile-grid">
                    @if($content->company_profile_image)
                    <div class="profile-image-section">
                        <div class="image-container">
                            <img src="{{ Storage::url($content->company_profile_image) }}" 
                                 alt="{{ $content->company_name }}" 
                                 class="corporate-image">
                        </div>
                    </div>
                    @endif
                    
                    <div class="profile-data-section">
                        <h3 class="corporate-name">{{ $content->company_name }}</h3>
                        
                        <div class="data-grid">
                            <!-- Hardcoded Categories -->
                            @if($content->established_date)
                            <div class="data-row">
                                <div class="data-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Established</span>
                                </div>
                                <div class="data-value">{{ $content->established_date }}</div>
                            </div>
                            @endif
                            
                            @if($content->capital)
                            <div class="data-row">
                                <div class="data-label">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Capital</span>
                                </div>
                                <div class="data-value">{{ $content->capital }}</div>
                            </div>
                            @endif
                            
                            @if($content->president_representative)
                            <div class="data-row">
                                <div class="data-label">
                                    <i class="fas fa-user-tie"></i>
                                    <span>President & Representative</span>
                                </div>
                                <div class="data-value">{{ $content->president_representative }}</div>
                            </div>
                            @endif
                            
                            @if($content->business_description)
                            <div class="data-row">
                                <div class="data-label">
                                    <i class="fas fa-industry"></i>
                                    <span>Primary Business</span>
                                </div>
                                <div class="data-value">{{ $content->business_description }}</div>
                            </div>
                            @endif
                            
                            @if($content->employees)
                            <div class="data-row">
                                <div class="data-label">
                                    <i class="fas fa-users"></i>
                                    <span>Total Employees</span>
                                </div>
                                <div class="data-value">{{ number_format($content->employees) }}</div>
                            </div>
                            @endif
                            
                            <!-- Dynamic Categories from Database -->
                            @if($content->dynamic_categories && count($content->dynamic_categories) > 0)
                                @foreach($content->dynamic_categories as $key => $value)
                                    @if(!in_array($key, ['established_date', 'capital', 'president_representative', 'business_description', 'employees']) && !empty($value))
                                    <div class="data-row">
                                        <div class="data-label">
                                            <i class="fas fa-tag"></i>
                                            <span>{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                        </div>
                                        <div class="data-value">{{ $value }}</div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        
                        @if($content->company_profile)
                        <div class="company-mission">
                            <div class="mission-badge">Overview</div>
                            <p class="mission-text">{{ $content->company_profile }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Auto-expanding textarea styles */
.auto-expand {
    overflow: hidden;
    resize: vertical;
    min-height: 80px;
    transition: height 0.1s ease;
}

/* Rest of your existing CSS remains the same */
:root {
    --primary-dark: #0E334C;
    --primary: #3988BD;
    --primary-light: #5BA3D4;
    --secondary: #2C3E50;
    --accent: #E74C3C;
    --gray-light: #F8F9FA;
    --gray-border: #E9ECEF;
    --text-dark: #2C3E50;
    --text-muted: #6C757D;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 1rem;
    letter-spacing: -0.5px;
}

.section-line {
    width: 70px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    margin: 0 auto 1.5rem auto;
}

/* Business Principles Cards */
.principle-card {
    background: white;
    padding: 2rem 1.5rem;
    border-radius: 8px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid var(--gray-border);
    position: relative;
    overflow: hidden;
}

.principle-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.principle-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: transparent;
}

.principle-card:hover::before {
    transform: scaleX(1);
}

.principle-icon-wrapper {
    width: 70px;
    height: 70px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.principle-icon {
    font-size: 2rem;
    color: white;
}

.principle-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}

.principle-description {
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.5;
    margin: 0;
}

/* Executive Section */
.executive-section {
    margin: 3rem 0;
}

.executive-badge {
    display: inline-block;
    background: var(--primary);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 1rem;
    border-radius: 20px;
    margin-bottom: 1.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.executive-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    border: 1px solid var(--gray-border);
}

.executive-image-wrapper {
    position: relative;
    height: 100%;
    min-height: 350px;
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
}

.executive-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.9;
}

.executive-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
}

.executive-content {
    padding: 2.5rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.executive-quote {
    margin-bottom: 2rem;
}

.executive-quote i {
    font-size: 2.5rem;
    color: var(--primary);
    opacity: 0.3;
    margin-bottom: 1rem;
}

.executive-message {
    font-size: 1.25rem;
    line-height: 1.6;
    color: var(--text-dark);
    font-style: italic;
    margin: 0;
}

.executive-signature {
    text-align: right;
}

.executive-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 0.25rem;
}

.executive-title {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.signature-line {
    width: 50px;
    height: 2px;
    background: var(--primary);
    margin-top: 1rem;
    margin-left: auto;
}

/* Corporate Profile */
.corporate-profile {
    margin: 3rem 0;
}

.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 2rem;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid var(--gray-border);
}

.profile-image-section {
    background: var(--gray-light);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.image-container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.corporate-image {
    max-width: 100%;
    max-height: 350px;
    object-fit: contain;
}

.profile-data-section {
    padding: 2rem;
    background: white;
}

.corporate-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--primary);
    display: inline-block;
}

.data-grid {
    margin-bottom: 2rem;
}

.data-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.75rem 0;
    border-bottom: 1px dashed var(--gray-border);
}

.data-row:last-child {
    border-bottom: none;
}

.data-label {
    flex: 0 0 40%;
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
    flex: 0 0 55%;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text-dark);
    text-align: right;
}

.company-mission {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--gray-border);
}

.mission-badge {
    display: inline-block;
    background: var(--gray-light);
    color: var(--primary);
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    margin-bottom: 1rem;
    letter-spacing: 1px;
}

.mission-text {
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--text-dark);
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .profile-image-section {
        order: 1;
        min-height: 250px;
    }
    
    .profile-data-section {
        order: 2;
    }
    
    .data-row {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .data-label {
        flex: auto;
    }
    
    .data-value {
        flex: auto;
        text-align: left;
        padding-left: 2rem;
    }
    
    .executive-content {
        padding: 1.5rem;
    }
    
    .executive-message {
        font-size: 1rem;
    }
    
    .executive-signature {
        text-align: left;
    }
    
    .signature-line {
        margin-left: 0;
    }
    
    .principle-card {
        padding: 1.5rem;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .profile-grid {
        gap: 1rem;
    }
    
    .data-label {
        flex: 0 0 45%;
    }
    
    .data-value {
        flex: 0 0 50%;
    }
}
</style>
@endsection