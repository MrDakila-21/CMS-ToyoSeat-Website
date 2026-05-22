@extends('layouts.app')

@section('title', 'Location - About Us')

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

    /* Location specific styles - Enhanced Responsive */
.info-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    height: 100%;
    transition: var(--transition);
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(0,0,0,0.03);
}
    
    .info-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }
    
    /* Title Header with Icon Beside */
    .info-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .info-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #0E334C 0%, #3988BD 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        flex-shrink: 0;
    }
    
    .info-card:hover .info-icon {
        transform: scale(1.05) rotate(5deg);
    }
    
    .info-icon i {
        font-size: 26px;
        color: white;
    }
    
    .info-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: -0.01em;
    }
    
    .address-detail {
        margin-bottom: 25px;
        padding-left: 20px;
        border-left: 4px solid var(--primary);
        background: var(--gray-light);
        padding: 15px 20px;
        border-radius: 12px;
        transition: var(--transition);
    }
    
    .address-detail:hover {
        background: white;
        box-shadow: var(--shadow-sm);
    }
    
    .address-detail p {
        margin-bottom: 8px;
        color: var(--text-dark);
        line-height: 1.6;
    }
    
    /* Contact Items Grid - New Responsive Layout */
    .contact-grid {
        display: grid;
        gap: 12px;
        margin-top: 20px;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 15px;
        border-radius: 12px;
        margin-top: 10px;
        transition: var(--transition);
        background: white;
        border: 1px solid var(--gray-border);
    }
    
    .contact-item:hover {
        transform: translateX(8px);
        background: var(--primary-lighter);
        border-color: var(--primary-light);
        box-shadow: var(--shadow-sm);
    }
    
    .contact-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, rgba(57, 136, 189, 0.1) 0%, rgba(57, 136, 189, 0.2) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 20px;
        transition: var(--transition);
        flex-shrink: 0;
    }
    
    .contact-item:hover .contact-icon {
        background: var(--primary);
        color: white;
        transform: scale(1.1);
    }
    
    .contact-content {
        flex: 1;
        min-width: 0;
    }
    
    .contact-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .contact-value {
        font-size: 1rem;
        color: var(--text-dark);
        font-weight: 500;
        text-decoration: none;
        word-break: break-word;
        display: inline-block;
    }
    
    .contact-value:hover {
        color: var(--primary);
    }
    
    /* Working Hours - Enhanced with multi-section support */
    .working-hours-section {
        margin-top: 25px;
    }
    
    .working-hours {
        background: linear-gradient(135deg, var(--gray-light) 0%, white 100%);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid var(--gray-border);
        transition: var(--transition);
    }
    
    .working-hours:last-child {
        margin-bottom: 0;
    }
    
    .working-hours:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
        transform: translateX(5px);
    }
    
    .working-hours-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--primary-light);
    }
    
    .working-hours-header i {
        font-size: 24px;
        color: var(--primary);
    }
    
    .working-hours-header h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
    }
    
    .hour-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray-border);
        transition: var(--transition);
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .hour-item:last-child {
        border-bottom: none;
    }
    
    .hour-item:hover {
        padding-left: 10px;
        background: rgba(57, 136, 189, 0.05);
        border-radius: 8px;
    }
    
    .hour-day {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.95rem;
    }
    
    .hour-time {
        color: var(--primary);
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    /* Multiple Sections Styling */
    .working-hours-subtitle {
        font-size: 0.85rem;
        color: var(--primary-light);
        margin-top: 5px;
        font-style: italic;
    }
    
    .map-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        border: 1px solid rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
    }
        
    .map-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }
    
    .map-header {
        padding: 20px 25px;
        background: linear-gradient(135deg, var(--gray-light) 0%, white 100%);
        border-bottom: 1px solid var(--gray-border);
        flex-shrink: 0;
    }
    
    .map-header-content {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .map-header-content i {
        font-size: 28px;
        color: var(--primary);
    }
    
    .map-header-content h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
    }
    
    .map-header-content small {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    
    /* Fixed Map Container - Proper fit */
    .map-container {
        position: relative;
        width: 100%;
        background: #f5f5f5;
        flex: 1;
        min-height: 400px;
    }
    
    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
    
    /* For Google Maps API */
    #map {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .map-placeholder {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: var(--text-muted);
        text-align: center;
        padding: 20px;
        flex: 1;
    }
    
    .map-placeholder i {
        font-size: 60px;
        margin-bottom: 20px;
        color: var(--primary);
        opacity: 0.5;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(14, 51, 76, 0.1) 0%, rgba(57, 136, 189, 0.1) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
    }
    
    .empty-icon i {
        font-size: 50px;
        color: var(--primary);
    }
    
    /* Responsive Design - Enhanced */
    @media (max-width: 992px) {
        .info-card, .map-card {
          
        }
        
        .info-title {
            font-size: 1.3rem;
        }
        
        .info-icon {
            width: 50px;
            height: 50px;
        }
        
        .info-icon i {
            font-size: 24px;
        }
        
        .contact-item {
            padding: 10px 12px;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .map-container {
            min-height: 350px;
        }
        
        .map-placeholder {
            min-height: 350px;
        }
    }
    
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
        
        .info-card {
            padding: 20px;
        }
        
        .info-header {
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .info-icon {
            width: 45px;
            height: 45px;
        }
        
        .info-icon i {
            font-size: 22px;
        }
        
        .info-title {
            font-size: 1.2rem;
        }
        
        .address-detail {
            padding: 12px 15px;
        }
        
        .contact-grid {
            gap: 10px;
        }
        
        .contact-item {
            padding: 10px;
        }
        
        .contact-icon {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
        
        .contact-label {
            font-size: 0.7rem;
        }
        
        .contact-value {
            font-size: 0.9rem;
        }
        
        .map-container {
            min-height: 300px;
        }
        
        .map-placeholder {
            min-height: 300px;
        }
        
        .map-header {
            padding: 15px 20px;
        }
        
        .map-header-content h3 {
            font-size: 1.1rem;
        }
        
        .working-hours {
            padding: 15px;
        }
        
        .hour-day, .hour-time {
            font-size: 0.85rem;
        }
        
        .working-hours-header h4 {
            font-size: 1rem;
        }
        
        .working-hours-header i {
            font-size: 18px;
        }
    }
    
    @media (max-width: 576px) {
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
        
        .info-card, .map-card {
            border-radius: 16px;
        }
        
        .info-card {
            padding: 16px;
        }
        
        .info-header {
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
        }
        
        .info-icon i {
            font-size: 18px;
        }
        
        .info-title {
            font-size: 1.1rem;
        }
        
        .address-detail {
            padding: 10px 12px;
            margin-bottom: 20px;
        }
        
        .address-detail p {
            font-size: 0.85rem;
            margin-bottom: 5px;
        }
        
        .contact-item {
            padding: 8px 10px;
            gap: 10px;
        }
        
        .contact-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
        
        .contact-label {
            font-size: 0.65rem;
        }
        
        .contact-value {
            font-size: 0.85rem;
        }
        
        .working-hours-header i {
            font-size: 18px;
        }
        
        .working-hours-header h4 {
            font-size: 1rem;
        }
        
        .hour-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .hour-day {
            font-size: 0.85rem;
        }
        
        .hour-time {
            font-size: 0.8rem;
        }
        
        .map-header {
            padding: 12px 15px;
        }
        
        .map-header-content {
            gap: 10px;
        }
        
        .map-header-content i {
            font-size: 20px;
        }
        
        .map-header-content h3 {
            font-size: 1rem;
        }
        
        .map-header-content small {
            font-size: 0.7rem;
        }
        
        .map-container {
            min-height: 250px;
        }
        
        .map-placeholder {
            min-height: 250px;
        }
        
        .empty-state {
            padding: 40px 20px;
        }
        
        .empty-icon {
            width: 70px;
            height: 70px;
        }
        
        .empty-icon i {
            font-size: 35px;
        }
    }
    
    /* Print Styles */
    @media print {
        .hero-section-wrapper,
        .hero-scroll-indicator,
        .btn {
            display: none;
        }
        
        .info-card, .map-card {
            box-shadow: none;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }
        
        .map-container {
            min-height: 300px;
        }
    }
    
    /* Smooth Loading */
    .container {
        opacity: 0;
        animation: fadeInUp 0.8s ease-out 0.1s forwards;
    }
</style>

<!-- Hero Section - Modern Gradient with Subtle Animation -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Our Location</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Visit us at our headquarters or get in touch through the details below</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Discover More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="container pt-5 mt-3 mb-5">
    @php
        // Ensure default values if location is null
        $defaultCity = 'Santa Rosa';
        $defaultProvince = 'Laguna';
        $defaultPostalCode = '4026';
        $defaultCountry = 'Philippines';
        
        // Function to convert 24-hour time to 12-hour format with AM/PM
        function formatTimeTo12Hour($time) {
            if (empty($time) || $time == 'Closed') {
                return $time;
            }
            
            // Check if time contains a range
            if (strpos($time, '-') !== false) {
                $times = explode('-', $time);
                $startTime = trim($times[0]);
                $endTime = trim($times[1]);
                return formatSingleTime($startTime) . ' - ' . formatSingleTime($endTime);
            }
            
            return formatSingleTime($time);
        }
        
        function formatSingleTime($time) {
            if (empty($time) || $time == 'Closed') {
                return $time;
            }
            
            // Check if already has AM/PM
            if (preg_match('/(am|pm)/i', $time)) {
                return $time;
            }
            
            // Try to parse 24-hour format (HH:MM)
            if (preg_match('/(\d{1,2}):(\d{2})/', $time, $matches)) {
                $hour = intval($matches[1]);
                $minute = $matches[2];
                $ampm = $hour >= 12 ? 'PM' : 'AM';
                $hour12 = $hour % 12;
                if ($hour12 == 0) $hour12 = 12;
                return sprintf("%d:%s %s", $hour12, $minute, $ampm);
            }
            
            return $time;
        }
        
        // Function to parse working hours with custom titles
        function parseWorkingHoursWithTitles($workingHoursText) {
            if (empty($workingHoursText)) {
                return [];
            }
            
            $sections = [];
            $lines = explode("\n", $workingHoursText);
            
            foreach ($lines as $line) {
                if (trim($line)) {
                    // Check for custom title format: "Title|Day Range: Time"
                    if (strpos($line, '|') !== false) {
                        $parts = explode('|', $line, 2);
                        $title = trim($parts[0]);
                        $rest = trim($parts[1]);
                        
                        if (strpos($rest, ':') !== false) {
                            $timeParts = explode(':', $rest, 2);
                            $dayRange = trim($timeParts[0]);
                            $timeRange = trim($timeParts[1]);
                            
                            $sections[] = [
                                'title' => $title,
                                'day_range' => $dayRange,
                                'time' => $timeRange
                            ];
                        }
                    } 
                    // Standard format: "Day Range: Time"
                    else if (strpos($line, ':') !== false) {
                        $parts = explode(':', $line, 2);
                        $sections[] = [
                            'title' => null,
                            'day_range' => trim($parts[0]),
                            'time' => trim($parts[1])
                        ];
                    }
                }
            }
            
            return $sections;
        }
    @endphp

    @isset($location)
    <div class="row g-4 fade-in-up" style="animation-delay: 0.2s;">
        <!-- Address & Contact Information -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="info-title">Company Headquarters</h3>
                </div>
                
                <div class="address-detail">
                    <p class="mb-2">{{ $location->address_line1 ?? 'Address information coming soon' }}</p>
                    @if(!empty($location->address_line2))
                        <p class="mb-2">{{ $location->address_line2 }}</p>
                    @endif
                    <p class="mb-2">
                        {{ $location->city ?? $defaultCity }}
                        @if(!empty($location->state)), {{ $location->state }}@else, {{ $defaultProvince }}@endif
                        @if(!empty($location->postal_code)) {{ $location->postal_code }}@else {{ $defaultPostalCode }}@endif
                    </p>
                    <p class="mb-0">{{ $location->country ?? $defaultCountry }}</p>
                </div>
                
                @if(!empty($location->phone) || !empty($location->telephone) || !empty($location->email))
                <div class="contact-grid">
                    @if(!empty($location->phone))
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-content">
                            <div class="contact-label">Phone Number</div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $location->phone) }}" class="contact-value">
                                {{ $location->phone }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(!empty($location->telephone))
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-content">
                            <div class="contact-label">Telephone Number</div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $location->telephone) }}" class="contact-value">
                                {{ $location->telephone }}
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if(!empty($location->email))
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-content">
                            <div class="contact-label">Email Address</div>
                            <a href="mailto:{{ $location->email }}" class="contact-value">
                                {{ $location->email }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                <!-- Working Hours with Custom Title Support -->
                @if(!empty($location->working_hours))
                    @php
                        $workingHoursSections = parseWorkingHoursWithTitles($location->working_hours);
                    @endphp
                    
                    @if(count($workingHoursSections) > 0)
                        <div class="working-hours-section">
                            @foreach($workingHoursSections as $section)
                            <div class="working-hours">
                                <div class="working-hours-header">
                                    @if(!empty($section['title']))
                                        <i class="fas fa-tag"></i>
                                        <h4>{{ $section['title'] }}</h4>
                                    @else
                                        <i class="fas fa-clock"></i>
                                        <h4>Operating Hours</h4>
                                    @endif
                                </div>
                                <div class="hour-item">
                                    <span class="hour-day">{{ $section['day_range'] }}</span>
                                    <span class="hour-time">{{ formatTimeTo12Hour($section['time']) }}</span>
                                </div>
                                @if(!empty($section['title']))
                                    
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
        
        <!-- Map Section - Fixed -->
        <div class="col-lg-6">
            <div class="map-card">
                <div class="map-header">
                    <div class="map-header-content">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Find Us Here</h3>
                            <small>Get directions to our location</small>
                        </div>
                    </div>
                </div>
                
                <div class="map-container">
                    @if(!empty($location->google_maps_embed))
                        {!! $location->google_maps_embed !!}
                    @elseif(!empty($location->latitude) && !empty($location->longitude))
                        <div id="map"></div>
                    @else
                        <div class="map-placeholder">
                            <i class="fas fa-map-marked-alt"></i>
                            <p class="text-center mb-0 px-4">Interactive map will be available soon. Please check back later.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @else
    <!-- Empty State with Default Values -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="empty-state fade-in-up">
                <div class="empty-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="h4 mb-3" style="color: var(--primary-dark);">Location Information</h3>
                <p class="text-muted mb-4">Company location details will be available soon.</p>
                <div class="alert alert-info" style="background: rgba(57, 136, 189, 0.1); border: none; border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i> 
                    Our headquarters is located in {{ $defaultCity }}, {{ $defaultProvince }}, {{ $defaultCountry }} (Postal Code: {{ $defaultPostalCode }})
                </div>
            </div>
        </div>
    </div>
    @endisset
</div>

<!-- Font Awesome 6 -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

<!-- Smooth Scroll for Hero Indicator -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

@isset($location)
    @if(!empty($location->latitude) && !empty($location->longitude) && empty($location->google_maps_embed))
    @section('scripts')
    <script>
        function initMap() {
            const location = { lat: {{ $location->latitude }}, lng: {{ $location->longitude }} };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: location,
                styles: [
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [{"color": "#ffffff"}, {"lightness": 20}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
                    }
                ],
                disableDefaultUI: false,
                zoomControl: true,
                mapTypeControl: false,
                streetViewControl: true,
                fullscreenControl: true
            });
            
            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "{{ addslashes($location->address_line1 ?? 'Company Location') }}",
                animation: google.maps.Animation.DROP,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
            
            // Optional: Add an info window
            const infoWindow = new google.maps.InfoWindow({
                content: '<div style="padding: 10px;"><strong>{{ addslashes($location->address_line1 ?? 'Company Location') }}</strong><br>{{ addslashes($location->city ?? 'Santa Rosa') }}, {{ addslashes($location->country ?? 'Philippines') }}</div>'
            });
            
            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', 'YOUR_API_KEY') }}&callback=initMap" async defer></script>
    @endsection
    @endif
@endisset