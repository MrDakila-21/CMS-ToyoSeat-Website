<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Toyoseat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dash.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Castoro:ital@0;1&family=Hind:wght@300;400;500;600;700&family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Additional floating toast styles if not in dash.css */
        .floating-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 250px;
            max-width: 350px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        }

        .floating-toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 6px;
        }

        .floating-toast.success-toast .floating-toast-content {
            background-color: #d4edda;
            border-left: 3px solid #28a745;
            color: #155724;
        }

        .floating-toast.error-toast .floating-toast-content {
            background-color: #f8d7da;
            border-left: 3px solid #dc3545;
            color: #721c24;
        }

        .floating-toast.warning-toast .floating-toast-content {
            background-color: #fff3cd;
            border-left: 3px solid #ffc107;
            color: #856404;
        }

        .floating-toast.info-toast .floating-toast-content {
            background-color: #d1ecf1;
            border-left: 3px solid #17a2b8;
            color: #0c5460;
        }

        .floating-toast .floating-toast-content i {
            font-size: 18px;
        }

        .floating-toast .floating-toast-content span {
            font-size: 13px;
            line-height: 1.4;
        }

        .floating-toast.hide {
            animation: slideOut 0.3s ease-in forwards;
        }

        /* Active subtab styling */
        .sidebar-dropdown a.active {
            background: rgba(128, 204, 255, 0.2);
            color: #80CCFF !important;
            font-weight: 500;
            border-left: 3px solid #80CCFF;
            padding-left: 16px;
        }

        /* Ensure dropdown stays open when subtab is active */
        .sidebar-dropdown.open {
            max-height: 500px;
        }

        /* Parent active state styling when dropdown has active child */
        .sidebar-link.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid #80CCFF;
        }
    </style>
</head>
<body>
    <!-- Flash Messages -->
    @if(session('success'))
        <div id="dashboard-success-toast" class="login-toast success-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="login-toast-content">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div id="dashboard-error-toast" class="login-toast error-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="login-toast-content">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('warning'))
        <div id="dashboard-warning-toast" class="login-toast warning-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="login-toast-content">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('info'))
        <div id="dashboard-info-toast" class="login-toast info-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="login-toast-content">
                <i class="fas fa-info-circle"></i>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- Navbar with Logo matching app.blade -->
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
            <div>
                <span class="text-white me-3" style="opacity: 0.9;">
                    <i class="fas fa-user-shield me-1"></i>
                    Admin Panel
                </span>
                <button type="button" class="btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </div>
        </div>
    </div>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h5><i class="fas fa-tachometer-alt me-2"></i>Content Management</h5>
                <small>Manage website content</small>
            </div>
            <ul class="sidebar-menu">
                <!-- Home Tab -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ $tab === 'home' ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard', ['tab' => 'home']) }}">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>

                <!-- About Us Dropdown -->
                <li class="sidebar-item">
                    <a class="sidebar-link dropdown-toggle-main {{ in_array($tab, ['about']) ? 'active' : '' }}" 
                       data-dropdown="aboutDropdown">
                        <i class="fas fa-info-circle"></i>
                        <span>About Us</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </a>
                    <ul class="sidebar-dropdown {{ in_array($tab, ['about']) ? 'open' : '' }}" id="aboutDropdown">
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'about', 'subtab' => 'overview']) }}" class="{{ $tab === 'about' && isset($subtab) && $subtab === 'overview' ? 'active' : '' }}">Overview</a></li>
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'about', 'subtab' => 'business']) }}" class="{{ $tab === 'about' && isset($subtab) && $subtab === 'business' ? 'active' : '' }}">Business Introduction</a></li>
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'about', 'subtab' => 'location']) }}" class="{{ $tab === 'about' && isset($subtab) && $subtab === 'location' ? 'active' : '' }}">Location</a></li>
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'about', 'subtab' => 'history']) }}" class="{{ $tab === 'about' && isset($subtab) && $subtab === 'history' ? 'active' : '' }}">History</a></li>
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'about', 'subtab' => 'iso']) }}" class="{{ $tab === 'about' && isset($subtab) && $subtab === 'iso' ? 'active' : '' }}">ISO Obtained</a></li>
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'about', 'subtab' => 'privacy']) }}" class="{{ $tab === 'about' && isset($subtab) && $subtab === 'privacy' ? 'active' : '' }}">Privacy Policy</a></li>
                    </ul>
                </li>

                <!-- Recruitment Tab -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ $tab === 'recruitment' ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard', ['tab' => 'recruitment']) }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Recruitment Information</span>
                    </a>
                </li>

                <!-- News Dropdown -->
                <li class="sidebar-item">
                    <a class="sidebar-link dropdown-toggle-main {{ in_array($tab, ['news']) ? 'active' : '' }}" 
                       data-dropdown="newsDropdown">
                        <i class="fas fa-newspaper"></i>
                        <span>News</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </a>
                    <ul class="sidebar-dropdown {{ in_array($tab, ['news']) ? 'open' : '' }}" id="newsDropdown">
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media']) }}" class="{{ $tab === 'news' && isset($subtab) && $subtab === 'media' ? 'active' : '' }}">Media Information</a></li>
                        <li><a href="{{ route('admin.dashboard', ['tab' => 'news', 'subtab' => 'announcements']) }}" class="{{ $tab === 'news' && isset($subtab) && $subtab === 'announcements' ? 'active' : '' }}">Announcements</a></li>
                    </ul>
                </li>

                <!-- Inquiry Tab -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ $tab === 'inquiry' ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard', ['tab' => 'inquiry']) }}">
                        <i class="fas fa-envelope"></i>
                        <span>Inquiry</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="content-panel active">
                @if($tab === 'home')
                    @include('admin.partials.home')
                @elseif($tab === 'about')
                    @include("admin.partials.about.{$subtab}")
                @elseif($tab === 'recruitment')
                    @include('admin.partials.recruitment')
                @elseif($tab === 'news')
                    @include("admin.partials.news.{$subtab}", [
                        'events' => $events ?? null, 
                        'announcements' => $announcements ?? null
                    ])
                @elseif($tab === 'inquiry')
                    @include('admin.partials.inquiry')
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Content not found.
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout from the admin panel?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize sidebar dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Setup dropdown toggles
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle-main');
            
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dropdownId = this.getAttribute('data-dropdown');
                    const dropdown = document.getElementById(dropdownId);
                    const chevron = this.querySelector('.chevron-icon');
                    
                    if (dropdown) {
                        dropdown.classList.toggle('open');
                        if (chevron) {
                            chevron.style.transform = dropdown.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0)';
                        }
                    }
                });
            });
            
            // Auto-hide toast messages after 5 seconds
            const toasts = document.querySelectorAll('.login-toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        if (toast.parentNode) toast.remove();
                    }, 300);
                }, 5000);
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert:not(.alert-info)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('fade');
                    setTimeout(() => {
                        if (alert.parentNode) alert.remove();
                    }, 500);
                }, 5000);
            });
            
            // If we're on media tab, initialize media management
            @if($tab === 'news' && $subtab === 'media')
                setTimeout(function() {
                    if (typeof window.initMediaManagement === 'function') {
                        window.initMediaManagement();
                    }
                }, 100);
            @endif
        });
        
        // Prevent back button access after logout
        (function() {
            // Push a new state to prevent back button from accessing protected pages
            history.pushState(null, null, location.href);
            
            window.addEventListener('popstate', function(event) {
                // Check if we're still authenticated
                fetch('/admin/check-auth', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    },
                    cache: 'no-store'
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.authenticated) {
                        window.location.replace('/admin/login');
                    } else {
                        // Push state again to prevent back navigation
                        history.pushState(null, null, location.href);
                    }
                })
                .catch(() => {
                    window.location.replace('/admin/login');
                });
            });
        })();
    </script>
</body>
</html>