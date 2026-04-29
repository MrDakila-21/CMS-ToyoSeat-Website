{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Toyoseat - @yield('title', 'Home')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        }

        /* Full width navbar container */
        .navbar-custom {
            width: 100%;
            background: linear-gradient(90deg, #0E334C 12.02%, #3988BD 46.63%, #0E334C 100%);
            box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.3);
            position: relative;
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
            text-shadow: 0 0 4px rgba(255,255,255,0.5);
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
        }

        .dropdown-toggle-icon {
            font-size: 0.7rem;
            margin-left: 5px;
            display: inline-block;
        }

        .mobile-toggle {
            display: none;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
        }

        .mobile-toggle span {
            display: block;
            width: 25px;
            height: 2px;
            background: white;
            margin: 5px 0;
            transition: 0.2s;
        }

        @media (max-width: 1024px) {
            .nav-link-custom {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
                white-space: nowrap;
            }
            .company-name-main {
                font-size: 1rem;
            }
            .company-name-sub {
                font-size: 0.6rem;
            }
            .company-logo {
                height: 40px;
            }
        }

        @media (max-width: 900px) {
            .mobile-toggle {
                display: block;
            }
            .navbar-container {
                width: 95%;
            }
            .nav-menu {
                position: fixed;
                top: 0;
                left: -100%;
                width: 80%;
                max-width: 320px;
                height: 100vh;
                background: linear-gradient(145deg, #0E334C 0%, #1f6182 100%);
                flex-direction: column;
                align-items: flex-start;
                padding: 80px 1.5rem 2rem;
                gap: 0.5rem;
                transition: left 0.3s ease;
                box-shadow: 4px 0 20px rgba(0,0,0,0.3);
                z-index: 1050;
                overflow-y: auto;
                flex-wrap: nowrap;
            }
            .nav-menu.active {
                left: 0;
            }
            .nav-item {
                width: 100%;
            }
            .nav-link-custom {
                display: block;
                width: 100%;
                padding: 0.75rem 0;
                font-size: 1rem;
                white-space: normal;
            }
            .dropdown-menu-custom {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                background: rgba(0,0,0,0.2);
                box-shadow: none;
                padding-left: 1rem;
                margin-top: 0;
                display: none;
                width: 100%;
            }
            .nav-item-dropdown.open .dropdown-menu-custom {
                display: block;
            }
            .dropdown-toggle-icon {
                float: right;
            }
            .menu-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                display: none;
            }
            .menu-overlay.active {
                display: block;
            }
            .company-name-main {
                font-size: 0.9rem;
            }
        }

        .nav-link-custom.active {
            background: rgba(255,255,255,0.25);
            font-weight: 600;
        }

        /* ============ FOOTER STYLES - LEFT/RIGHT LAYOUT ============ */
        .footer-custom {
            background: linear-gradient(135deg, #0a1e2c 0%, #0E334C 100%);
            color: #ffffff;
            margin-top: 4rem;
            padding: 3rem 0 1.5rem;
        }

        .footer-container {
            max-width: 1400px;
            width: 90%;
            margin: 0 auto;
        }

        /* Top section: Left (brand) + Right (nav columns) */
        .footer-top {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        /* LEFT SIDE - Logo, Company Name, Tagline, Social Icons */
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
            font-size: 1.3rem;
            letter-spacing: 1px;
            color: white;
            line-height: 1.2;
        }

        .footer-brand-sub {
            font-weight: 500;
            font-size: 0.7rem;
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

        /* RIGHT SIDE - Navigation Columns */
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

        /* Bottom section: Left (Copyright) + Right (Credit) */
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
            .footer-section h4::after {
                left: 0;
            }
        }

        @media (max-width: 600px) {
            .footer-right {
                flex-direction: column;
                gap: 1.5rem;
            }
            .footer-section {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- FULL WIDTH NAVBAR --}}
<div class="navbar-custom">
    <div class="navbar-container">
        <a href="{{ url('/') }}" class="navbar-brand-custom">
            <img src="{{ asset('images/logo.svg') }}" 
                 alt="Toyoseat Logo" 
                 class="company-logo"
                 onerror="this.style.display='none';">
            <div class="company-name">
                <div class="company-name-main">TOYO SEAT</div>
                <div class="company-name-sub">PHILIPPINES CORPORATION</div>
            </div>
        </a>

        <button class="mobile-toggle" id="mobileToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="nav-menu" id="navMenu">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link-custom {{ request()->is('/') ? 'active' : '' }}">Home</a>
            </li>

            <li class="nav-item nav-item-dropdown" id="aboutDropdownLi">
                <a href="javascript:void(0)" class="nav-link-custom dropdown-toggle-main {{ request()->is('about*') ? 'active' : '' }}">
                    About Us
                    <span class="dropdown-toggle-icon">▼</span>
                </a>
                <ul class="dropdown-menu-custom">
                    <li><a href="{{ url('/about/overview') }}">Overview</a></li>
                    <li><a href="{{ url('/about/business-introduction') }}">Business introduction</a></li>
                    <li><a href="{{ url('/about/location') }}">Location</a></li>
                    <li><a href="{{ url('/about/history') }}">History</a></li>
                    <li><a href="{{ url('/about/iso-obtained') }}">ISO Obtained</a></li>
                    <li><a href="{{ url('/about/privacy-policy') }}">Privacy Policy</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ url('/recruitment') }}" class="nav-link-custom {{ request()->is('recruitment*') ? 'active' : '' }}">Recruitment information</a>
            </li>

            <li class="nav-item nav-item-dropdown" id="newsDropdownLi">
                <a href="javascript:void(0)" class="nav-link-custom dropdown-toggle-main {{ request()->is('news*') ? 'active' : '' }}">
                    News
                    <span class="dropdown-toggle-icon">▼</span>
                </a>
                <ul class="dropdown-menu-custom">
                    <li><a href="{{ url('/news/media-information') }}">Media Information</a></li>
                    <li><a href="{{ url('/news/announcements') }}">Announcements</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ url('/inquiry') }}" class="nav-link-custom {{ request()->is('inquiry*') ? 'active' : '' }}">Inquiry</a>
            </li>
        </ul>
    </div>
</div>

<div id="menuOverlay" class="menu-overlay"></div>

<main>
    @yield('content')
</main>

{{-- ============ FOOTER - LEFT/RIGHT LAYOUT ============ --}}
<footer class="footer-custom">
    <div class="footer-container">
        <!-- TOP SECTION: LEFT (Brand + Social) | RIGHT (Nav Columns) -->
        <div class="footer-top">
            <!-- LEFT SIDE -->
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
                    <a href="https://www.facebook.com/profile.php?id=100057821552844" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/@ToyoSeatPhilippinesCorporation" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" target="_blank" aria-label="Email"><i class="fas fa-envelope"></i></a>
                </div>
            </div>

            <!-- RIGHT SIDE - Navigation Columns -->
            <div class="footer-right">
                <div class="footer-section">
                    <h4>COMPANY</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/about/overview') }}">Overview</a></li>
                        <li><a href="{{ url('/about/business-introduction') }}">Business introduction</a></li>
                        <li><a href="{{ url('/about/location') }}">Location</a></li>
                        <li><a href="{{ url('/about/history') }}">History</a></li>
                        <li><a href="{{ url('/about/iso-obtained') }}">ISO Obtained</a></li>
                        <li><a href="{{ url('/about/privacy-policy') }}">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>RECRUITMENT</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/recruitment/information-top') }}">Information TOP</a></li>
                        <li><a href="{{ url('/recruitment/new-graduate') }}">New Graduate Recruitment</a></li>
                        <li><a href="{{ url('/recruitment/career') }}">Career Recruitment</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>NEWS & INQUIRY</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/news/media-information') }}">Media Information</a></li>
                        <li><a href="{{ url('/news/announcements') }}">Announcements</a></li>
                        <li><a href="{{ url('/inquiry') }}">Inquiry</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- BOTTOM SECTION: LEFT (Copyright) | RIGHT (Credit) -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                &copy; {{ date('Y') }} Toyo Seat Philippines Corporation. All Rights Reserved.
            </div>
            <div class="footer-credit">
                Made with passion by Constantino, Dakila and Maniaul.
            </div>
        </div>
    </div>
</footer>

<script>
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');
    const overlay = document.getElementById('menuOverlay');

    function closeMenu() {
        navMenu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    function openMenu() {
        navMenu.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (navMenu.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeMenu);
    }

    function handleMobileDropdowns() {
        const isMobile = window.innerWidth <= 900;
        const dropdownItems = document.querySelectorAll('.nav-item-dropdown');
        
        dropdownItems.forEach(item => {
            const toggleLink = item.querySelector('.dropdown-toggle-main');
            if (!toggleLink) return;
            
            if (isMobile) {
                const newToggle = toggleLink.cloneNode(true);
                toggleLink.parentNode.replaceChild(newToggle, toggleLink);
                
                newToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    const isOpen = item.classList.contains('open');
                    dropdownItems.forEach(other => {
                        if (other !== item) other.classList.remove('open');
                    });
                    if (!isOpen) {
                        item.classList.add('open');
                    } else {
                        item.classList.remove('open');
                    }
                });
            } else {
                item.classList.remove('open');
                const cleanLink = toggleLink.cloneNode(true);
                toggleLink.parentNode.replaceChild(cleanLink, toggleLink);
            }
        });
    }

    window.addEventListener('load', handleMobileDropdowns);
    window.addEventListener('resize', () => {
        handleMobileDropdowns();
        if (window.innerWidth > 900 && navMenu.classList.contains('active')) {
            closeMenu();
        }
    });
    
    document.querySelectorAll('.nav-link-custom, .dropdown-menu-custom a').forEach(link => {
        link.addEventListener('click', (e) => {
            if (window.innerWidth <= 900) {
                if (link.classList.contains('dropdown-toggle-main')) {
                    return;
                }
                if (!link.closest('.dropdown-menu-custom')) {
                    closeMenu();
                } else {
                    setTimeout(closeMenu, 150);
                }
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>