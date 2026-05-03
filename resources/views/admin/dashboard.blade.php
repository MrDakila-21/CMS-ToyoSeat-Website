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
        /* Add loading indicator styles */
        .content-loading {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 12px;
        }
        .content-loading .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3988BD;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Active state styling */
        .sidebar-link.active, .sidebar-dropdown a.active {
            background: linear-gradient(90deg, #3988BD 0%, #2c6d99 100%);
            color: white;
            border-left: 4px solid #ffd700;
        }
        
        .sidebar-dropdown a.active {
            background: #2c6d99;
        }
    </style>
</head>
<body>
    @if(session('success'))
        <div id="dashboard-success-toast" class="login-toast success-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="login-toast-content">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
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
                    <a class="sidebar-link" data-tab="home" data-main-tab="home" href="/admin/dashboard?tab=home">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>

                <!-- About Us Dropdown -->
                <li class="sidebar-item">
                    <a class="sidebar-link dropdown-toggle-main" data-dropdown="aboutDropdown">
                        <i class="fas fa-info-circle"></i>
                        <span>About Us</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </a>
                    <ul class="sidebar-dropdown" id="aboutDropdown">
                        <li><a data-tab="about" data-subtab="overview" href="/admin/dashboard?tab=about&subtab=overview">Overview</a></li>
                        <li><a data-tab="about" data-subtab="business" href="/admin/dashboard?tab=about&subtab=business">Business Introduction</a></li>
                        <li><a data-tab="about" data-subtab="location" href="/admin/dashboard?tab=about&subtab=location">Location</a></li>
                        <li><a data-tab="about" data-subtab="history" href="/admin/dashboard?tab=about&subtab=history">History</a></li>
                        <li><a data-tab="about" data-subtab="iso" href="/admin/dashboard?tab=about&subtab=iso">ISO Obtained</a></li>
                        <li><a data-tab="about" data-subtab="privacy" href="/admin/dashboard?tab=about&subtab=privacy">Privacy Policy</a></li>
                    </ul>
                </li>

                <!-- Recruitment Tab -->
                <li class="sidebar-item">
                    <a class="sidebar-link" data-tab="recruitment" data-main-tab="recruitment" href="/admin/dashboard?tab=recruitment">
                        <i class="fas fa-briefcase"></i>
                        <span>Recruitment Information</span>
                    </a>
                </li>

                <!-- News Dropdown -->
                <li class="sidebar-item">
                    <a class="sidebar-link dropdown-toggle-main" data-dropdown="newsDropdown">
                        <i class="fas fa-newspaper"></i>
                        <span>News</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </a>
                    <ul class="sidebar-dropdown" id="newsDropdown">
                        <li><a data-tab="news" data-subtab="media" href="/admin/dashboard?tab=news&subtab=media">Media Information</a></li>
                        <li><a data-tab="news" data-subtab="announcements" href="/admin/dashboard?tab=news&subtab=announcements">Announcements</a></li>
                    </ul>
                </li>

                <!-- Inquiry Tab -->
                <li class="sidebar-item">
                    <a class="sidebar-link" data-tab="inquiry" data-main-tab="inquiry" href="/admin/dashboard?tab=inquiry">
                        <i class="fas fa-envelope"></i>
                        <span>Inquiry</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <div id="dynamic-content" class="content-panel active">
                <!-- Content will be loaded dynamically here -->
                <div class="content-loading">
                    <div class="spinner"></div>
                    <p>Loading content...</p>
                </div>
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
                    <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dash.js') }}"></script>
    
    <script>
        // Get current tab and subtab from URL
        function getCurrentTabFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return {
                tab: urlParams.get('tab') || 'home',
                subtab: urlParams.get('subtab') || null
            };
        }
        
        // Update active state based on current URL
        function updateActiveState(tab, subtab) {
            // Remove all active classes
            document.querySelectorAll('.sidebar-link, .sidebar-dropdown a').forEach(el => {
                el.classList.remove('active');
            });
            
            // Find and activate the correct link
            if (tab === 'about' && subtab) {
                const link = document.querySelector(`.sidebar-dropdown a[data-tab="about"][data-subtab="${subtab}"]`);
                if (link) link.classList.add('active');
                // Also highlight the parent dropdown toggle
                const parentToggle = document.querySelector('.dropdown-toggle-main[data-dropdown="aboutDropdown"]');
                if (parentToggle) parentToggle.classList.add('active');
            } else if (tab === 'news' && subtab) {
                const link = document.querySelector(`.sidebar-dropdown a[data-tab="news"][data-subtab="${subtab}"]`);
                if (link) link.classList.add('active');
                const parentToggle = document.querySelector('.dropdown-toggle-main[data-dropdown="newsDropdown"]');
                if (parentToggle) parentToggle.classList.add('active');
            } else {
                const link = document.querySelector(`.sidebar-link[data-tab="${tab}"]`);
                if (link) link.classList.add('active');
            }
        }
        
        // Initialize dashboard with dynamic content loading
        document.addEventListener('DOMContentLoaded', function() {
            const { tab, subtab } = getCurrentTabFromUrl();
            updateActiveState(tab, subtab);
            loadContent(tab, subtab);
            
            // Update URL when content changes without page reload
            window.updateUrl = function(tab, subtab) {
                let url = `/admin/dashboard?tab=${tab}`;
                if (subtab) {
                    url += `&subtab=${subtab}`;
                }
                window.history.pushState({ tab, subtab }, '', url);
                updateActiveState(tab, subtab);
            };
        });
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            const { tab, subtab } = getCurrentTabFromUrl();
            updateActiveState(tab, subtab);
            loadContent(tab, subtab);
        });
        
        // Function to load content via AJAX
        function loadContent(mainTab, subTab = null) {
            const contentContainer = document.getElementById('dynamic-content');
            
            // Show loading indicator
            contentContainer.innerHTML = `
                <div class="content-loading">
                    <div class="spinner"></div>
                    <p>Loading content...</p>
                </div>
            `;
            
            // Build URL with parameters
            let url = `/admin/load-content?tab=${mainTab}`;
            if (subTab) {
                url += `&subtab=${subTab}`;
            }
            
            // Fetch content
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contentContainer.innerHTML = data.html;
                    // Re-initialize any tab-specific JavaScript
                    if (mainTab === 'home') {
                        if (typeof initHomepageManagement === 'function') {
                            initHomepageManagement();
                        }
                    }
                    if (mainTab === 'news' && subTab === 'media' && typeof window.initMediaManagement === 'function') {
                        window.initMediaManagement();
                    }
                } else if (data.error) {
                    contentContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${escapeHtml(data.error)}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading content:', error);
                contentContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Failed to load content. Please try again.
                    </div>
                `;
            });
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Expose loader for other scripts
        window.loadContent = loadContent;
        
        // Tab switching logic with URL updates
        document.querySelectorAll('[data-tab]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const mainTab = this.getAttribute('data-tab');
                const subTab = this.getAttribute('data-subtab');
                
                // Update URL without page reload
                if (typeof window.updateUrl === 'function') {
                    window.updateUrl(mainTab, subTab);
                }
                
                // Update active state
                document.querySelectorAll('.sidebar-link, .sidebar-dropdown a').forEach(l => {
                    l.classList.remove('active');
                });
                this.classList.add('active');
                
                // Load content
                loadContent(mainTab, subTab);
            });
        });
        
        // Prevent back button access after logout
        (function() {
            // Check authentication status periodically
            function checkAuthStatus() {
                fetch('/admin/check-auth', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.authenticated) {
                        window.location.href = '/admin/login';
                    }
                })
                .catch(error => {
                    console.error('Auth check failed:', error);
                });
            }
            
            setInterval(checkAuthStatus, 5000);
            
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkAuthStatus();
                }
            });
        })();
    </script>
</body>
</html>