{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('tspc-icon.ico') }}">
    <title>Toyoseat - @yield('title', 'Home')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @yield('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* Full width navbar container - STICKY HEADER */
        .navbar-custom {
            width: 100%;
            background: linear-gradient(90deg, #0E334C 12.02%, #3988BD 46.63%, #0E334C 100%);
            box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1030;
            padding: 0.75rem 0;
        }

        .navbar-container {
            max-width: 1400px;
            width: 90%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .company-logo {
            height: 50px;
            width: auto;
            display: block;
        }

        .company-name {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            font-family: 'Cinzel', serif;
            font-style: italic;
        }

        .company-name-main {
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: 1px;
            color: white;
            margin: 0;
        }

        .company-name-sub {
            font-weight: 500;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-family: 'Playfair Display', serif;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }

        .nav-item {
            position: relative;
        }

        .nav-link-custom {
            color: white !important;
            font-weight: 500;
            padding: 0.6rem 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            border-radius: 30px;
            background: transparent;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .nav-link-custom:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-link-custom.active {
            background: rgba(255,255,255,0.25);
            font-weight: 600;
        }

        /* Active state for dropdown parent when any child is active */
        .nav-item-dropdown.active-dropdown > .dropdown-toggle-main {
            background: rgba(255,255,255,0.25);
            font-weight: 600;
        }

        .dropdown-menu-custom li a.active {
            background: rgba(128, 204, 255, 0.3);
            font-weight: 600;
            padding-left: 1.8rem;
            border-left: 3px solid #3988BD;
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            left: 0;
            background: linear-gradient(135deg, #0E334C 0%, #1a5a7e 100%);
            border-radius: 16px;
            min-width: 220px;
            padding: 0.6rem 0;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            box-shadow: 0 12px 25px rgba(0,0,0,0.25);
            list-style: none;
            z-index: 1000;
        }

        .nav-item-dropdown:hover .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu-custom li a {
            color: white;
            padding: 0.5rem 1.5rem;
            display: block;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .dropdown-menu-custom li a:hover {
            background: rgba(255, 255, 255, 0.2);
            padding-left: 1.8rem;
        }

        .dropdown-toggle-icon {
            font-size: 0.7rem;
            margin-left: 5px;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .nav-item-dropdown.open .dropdown-toggle-icon {
            transform: rotate(180deg);
        }

        .mobile-toggle {
            display: none;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            z-index: 1060;
            position: relative;
        }

        .mobile-toggle span {
            display: block;
            width: 25px;
            height: 2px;
            background: white;
            margin: 5px 0;
            transition: 0.2s;
        }

        /* MOBILE STYLES - FIXED */
        @media (max-width: 900px) {
            .mobile-toggle {
                display: block;
            }
            
            .nav-menu {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: #0E334C;
                flex-direction: column;
                padding: 80px 20px 20px;
                transition: right 0.3s ease;
                z-index: 99999;
                overflow-y: auto;
            }
            
            .nav-menu.active {
                right: 0;
            }
            
            .nav-item {
                width: 100%;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                flex-shrink: 0;
            }
            
            .nav-link-custom {
                display: block;
                width: 100%;
                padding: 15px 0;
                font-size: 16px;
                white-space: normal;
                text-align: left;
                border-radius: 0;
            }
            
            /* Mobile active states */
            .nav-link-custom.active {
                background: rgba(255,255,255,0.2);
                border-left: 3px solid #3988BD;
                padding-left: 12px;
            }
            
            .dropdown-menu-custom li a.active {
                background: rgba(128, 204, 255, 0.2);
                border-left: 3px solid #3988BD;
                padding-left: 17px;
            }
            
            .dropdown-menu-custom {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                background: rgba(0,0,0,0.2);
                box-shadow: none;
                padding: 0;
                margin: 0;
                display: none;
                width: 100%;
                max-height: none;
                overflow: visible;
            }
            
            .nav-item-dropdown.open .dropdown-menu-custom {
                display: block;
            }
            
            .dropdown-menu-custom li a {
                padding: 12px 0 12px 20px;
                white-space: normal;
                word-wrap: break-word;
            }
            
            .dropdown-toggle-icon {
                float: right;
                margin-top: 3px;
            }
            
            /* Ensure menu doesn't overflow */
            .nav-menu {
                overflow-y: auto;
                overflow-x: hidden;
            }
        }

        .section4 {
            border-bottom: 2px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 0;
            position: relative;
        }

        /* FOOTER STYLES */
        .footer-custom {
            background: linear-gradient(135deg, #0a1e2c 0%, #0E334C 100%);
            color: #ffffff;
            margin-top: 0;
            padding: 3rem 0 1.5rem;
        }

        .footer-container {
            max-width: 1400px;
            width: 90%;
            margin: 0 auto;
        }

        .footer-top {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .footer-left {
            flex: 1.2;
            min-width: 280px;
        }

        .footer-logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .footer-logo-img {
            height: 50px;
            width: auto;
        }

        .footer-brand-name {
            font-weight: 800;
            font-family: 'Cinzel', serif;
            font-style: italic;
            font-size: 1.3rem;
            letter-spacing: 1px;
            color: white;
            line-height: 1.2;
        }

        .footer-brand-sub {
            font-weight: 500;
            font-size: 0.7rem;
            font-family: 'Playfair Display', serif;
            font-style: italic;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-tagline {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.5;
            margin-bottom: 1.2rem;
            max-width: 350px;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .footer-social a:hover {
            background: #3988BD;
            transform: translateY(-3px);
        }

        .footer-right {
            flex: 2;
            display: flex;
            justify-content: flex-end;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .footer-section {
            min-width: 140px;
        }

        .footer-section h4 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: #ffffff;
            position: relative;
            padding-bottom: 0.6rem;
        }

        .footer-section h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 35px;
            height: 2px;
            background: #3988BD;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.6rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-links a:hover {
            color: #ffffff;
            transform: translateX(5px);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
        }

        .footer-copyright {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-credit {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 900px) {
            .footer-top {
                flex-direction: column;
            }
            .footer-right {
                justify-content: flex-start;
                gap: 1.5rem;
            }
            .footer-left {
                text-align: left;
            }
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="navbar-custom">
    <div class="navbar-container">
        <a href="{{ route('home') }}" class="navbar-brand-custom">
            <img src="{{ asset('images/logo.svg') }}" 
                 alt="Toyoseat Logo" 
                 class="company-logo"
                 onerror="this.style.display='none';">
            <div class="company-name">
                <div class="company-name-main">TOYO SEAT</div>
                <div class="company-name-sub">PHILIPPINES CORPORATION</div>
            </div>
        </a>

        <button class="mobile-toggle" id="mobileToggleBtn">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="nav-menu" id="mobileMenu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            </li>

            <li class="nav-item nav-item-dropdown {{ request()->routeIs('guest.about.*') ? 'active-dropdown' : '' }}">
                <a href="#" class="nav-link-custom dropdown-toggle-main {{ request()->routeIs('guest.about.*') ? 'active' : '' }}">
                    About Us
                    <span class="dropdown-toggle-icon">▼</span>
                </a>
                <ul class="dropdown-menu-custom">
                    <li><a href="{{ route('guest.about.overview') }}" class="{{ request()->routeIs('guest.about.overview') ? 'active' : '' }}">Overview</a></li>
                    <li><a href="{{ route('guest.about.business-introduction') }}" class="{{ request()->routeIs('guest.about.business-introduction') ? 'active' : '' }}">Business introduction</a></li>
                    <li><a href="{{ route('guest.about.location') }}" class="{{ request()->routeIs('guest.about.location') ? 'active' : '' }}">Location</a></li>
                    <li><a href="{{ route('guest.about.history') }}" class="{{ request()->routeIs('guest.about.history') ? 'active' : '' }}">History</a></li>
                    <li><a href="{{ route('guest.about.iso-obtained') }}" class="{{ request()->routeIs('guest.about.iso-obtained') ? 'active' : '' }}">ISO Certificate</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('guest.recruitment.information') }}" class="nav-link-custom {{ request()->routeIs('guest.recruitment.*') ? 'active' : '' }}">Recruitment</a>
            </li>

            <li class="nav-item nav-item-dropdown {{ request()->routeIs('guest.news.*') ? 'active-dropdown' : '' }}">
                <a href="#" class="nav-link-custom dropdown-toggle-main {{ request()->routeIs('guest.news.*') ? 'active' : '' }}">
                    News
                    <span class="dropdown-toggle-icon">▼</span>
                </a>
                <ul class="dropdown-menu-custom">
                    <li><a href="{{ route('guest.news.media-information') }}" class="{{ request()->routeIs('guest.news.media-information') ? 'active' : '' }}">Events & Activities</a></li>
                    <li><a href="{{ route('guest.news.announcements') }}" class="{{ request()->routeIs('guest.news.announcements') ? 'active' : '' }}">Announcements</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('guest.inquiry.index') }}" class="nav-link-custom {{ request()->routeIs('guest.inquiry.*') ? 'active' : '' }}">Contact Us</a>
            </li>


            {{-- ADD ADMIN DASHBOARD LINK FOR AUTHENTICATED USERS --}}
    @auth
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link-custom {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}" style="background: rgba(128, 204, 255, 0.2); border-left: 3px solid #80CCFF;">
                <i class="fas fa-tachometer-alt me-1"></i> Admin Dashboard
            </a>
        </li>
    @endauth
        </ul>
    </div>
</div>

<main>
    @yield('content')
</main>

<footer class="footer-custom">
    <div class="footer-container">
        <div class="footer-top">
            <div class="footer-left">
                <div class="footer-logo-area">
                    <img src="{{ asset('images/logo.svg') }}" 
                         alt="Toyoseat Logo" 
                         class="footer-logo-img"
                         onerror="this.style.display='none';">
                    <div>
                        <div class="footer-brand-name">TOYO SEAT</div>
                        <div class="footer-brand-sub">PHILIPPINES CORPORATION</div>
                    </div>
                </div>
                <div class="footer-tagline">
                    Toyo Seat is committed to creating innovative seating solutions that enhance comfort, safety, and sustainability for a better tomorrow.
                </div>
                <div class="footer-social">
                    <a href="https://www.facebook.com/profile.php?id=100057821552844" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/@ToyoSeatPhilippinesCorporation" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a href="{{ route('guest.inquiry.index') }}"><i class="fas fa-envelope"></i></a>
                </div>
            </div>

            <div class="footer-right">
                <div class="footer-section">
                    <h4>COMPANY</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('guest.about.overview') }}">Overview</a></li>
                        <li><a href="{{ route('guest.about.business-introduction') }}">Business introduction</a></li>
                        <li><a href="{{ route('guest.about.location') }}">Location</a></li>
                        <li><a href="{{ route('guest.about.history') }}">History</a></li>
                        <li><a href="{{ route('guest.about.iso-obtained') }}">ISO Certificate</a></li>
                        
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>RECRUITMENT</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('guest.recruitment.information') }}">Recruitment Information</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>NEWS & INQUIRY</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('guest.news.media-information') }}">Events & Activities</a></li>
                        <li><a href="{{ route('guest.news.announcements') }}">Announcements</a></li>
                        <li><a href="{{ route('guest.inquiry.index') }}">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-copyright">
                &copy; {{ date('Y') }} Toyo Seat Philippines Corporation. All Rights Reserved.
            </div>
            <div class="footer-credit">
                • Joey Manarin • Rizza Constantino • Earl Dakila • Patrick Maniaul •
            </div>
        </div>
    </div>
</footer>

<script>
    (function() {
        var toggleBtn = document.getElementById('mobileToggleBtn');
        var mobileMenu = document.getElementById('mobileMenu');
        
        if (toggleBtn && mobileMenu) {
            // Toggle menu when button clicked
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMenu.classList.toggle('active');
            });
            
            // Close menu ONLY when clicking on regular navigation links (NOT dropdown toggles)
            var allLinks = mobileMenu.querySelectorAll('a');
            allLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    // Check if this is a dropdown toggle (has dropdown-toggle-main class)
                    var isDropdownToggle = this.classList.contains('dropdown-toggle-main');
                    
                    // If it's NOT a dropdown toggle, close the menu
                    if (!isDropdownToggle) {
                        mobileMenu.classList.remove('active');
                    }
                });
            });
        }
        
        // Handle dropdown toggles on mobile - CLOSE OTHER DROPDOWNS
        var dropdownToggles = document.querySelectorAll('.dropdown-toggle-main');
        dropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 900) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var parentLi = this.closest('.nav-item-dropdown');
                    if (parentLi) {
                        // Check if this dropdown is already open
                        var isOpen = parentLi.classList.contains('open');
                        
                        // Close ALL dropdowns first
                        var allDropdowns = document.querySelectorAll('.nav-item-dropdown');
                        allDropdowns.forEach(function(dropdown) {
                            dropdown.classList.remove('open');
                        });
                        
                        // If this dropdown wasn't open, open it (toggle functionality)
                        if (!isOpen) {
                            parentLi.classList.add('open');
                        }
                    }
                }
            });
        });
        
        // Auto-open dropdown parent on mobile if a child is active (only one at a time)
        if (window.innerWidth <= 900) {
            var activeDropdowns = document.querySelectorAll('.nav-item-dropdown.active-dropdown');
            // Close all first, then open the active one
            var allDropdowns = document.querySelectorAll('.nav-item-dropdown');
            allDropdowns.forEach(function(dropdown) {
                dropdown.classList.remove('open');
            });
            activeDropdowns.forEach(function(dropdown) {
                dropdown.classList.add('open');
            });
        }
        
        // Handle window resize to reset dropdown states
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) {
                var allDropdowns = document.querySelectorAll('.nav-item-dropdown');
                allDropdowns.forEach(function(dropdown) {
                    dropdown.classList.remove('open');
                });
            } else {
                // On mobile, re-open the active dropdown if any
                var activeDropdowns = document.querySelectorAll('.nav-item-dropdown.active-dropdown');
                activeDropdowns.forEach(function(dropdown) {
                    dropdown.classList.add('open');
                });
            }
        });
    })();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>