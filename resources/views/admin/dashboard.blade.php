<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Toyoseat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Toast message styles matching login page */
        .login-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        }
        
        .login-toast-content {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            border-radius: 8px;
        }
        
        .login-toast.success-toast .login-toast-content {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        
        .login-toast.error-toast .login-toast-content {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        
        .login-toast .login-toast-content i {
            font-size: 24px;
        }
        
        .login-toast.success-toast i {
            color: #28a745;
        }
        
        .login-toast.error-toast i {
            color: #dc3545;
        }
        
        .login-toast.hide {
            animation: slideOut 0.3s ease-in forwards;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Navbar Styles matching app.blade */
        .navbar-custom {
            background: linear-gradient(90deg, #0E334C 12.02%, #3988BD 46.63%, #0E334C 100%);
            box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.3);
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
        }

        /* Dashboard Layout */
        .dashboard-wrapper {
            display: flex;
            min-height: calc(100vh - 80px);
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0E334C 0%, #1a5a7e 100%);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .sidebar-header small {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 5px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .sidebar-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .sidebar-link span {
            flex: 1;
        }

        .sidebar-link .chevron-icon {
            width: auto;
            margin-right: 0;
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.1);
            padding-left: 25px;
        }

        .sidebar-link.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid #80CCFF;
        }

        /* Dropdown Submenu */
        .sidebar-dropdown {
            list-style: none;
            padding-left: 45px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sidebar-dropdown.open {
            max-height: 500px;
        }

        .sidebar-dropdown li {
            margin-bottom: 5px;
        }

        .sidebar-dropdown a {
            display: block;
            padding: 8px 12px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            border-radius: 6px;
            cursor: pointer;
        }

        .sidebar-dropdown a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 16px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background: #f5f7fa;
            padding: 20px;
            overflow-y: auto;
        }

        /* Content Panels */
        .content-panel {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .content-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Updated Alert - Figma colors */
        .alert-success {
            background: linear-gradient(135deg, #0E334C 0%, #015A96 100%);
            border: none;
            color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .alert-success .alert-heading {
            color: #80CCFF;
            font-weight: 600;
        }

        .alert-success hr {
            background-color: rgba(128, 204, 255, 0.3);
        }

        /* Updated Card - Figma colors */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .card-header {
            background: linear-gradient(90deg, #0E334C 12.02%, #3988BD 46.63%, #0E334C 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            border: none;
            padding: 15px 20px;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            color: #FFFFFF;
        }

        /* Updated Button - Figma colors */
        .btn-primary {
            background: linear-gradient(135deg, #015A96, #0E334C);
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0E334C, #015A96);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(1, 90, 150, 0.3);
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Modal footer button colors */
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 8px;
        }

        /* Body background accent */
        body {
            background: #f5f7fa;
        }

        /* Mobile responsive sidebar */
        @media (max-width: 768px) {
            .dashboard-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                position: static;
            }
            .sidebar-link {
                padding: 10px 15px;
            }
            .sidebar-dropdown {
                padding-left: 35px;
            }
            .navbar-container {
                width: 95%;
            }
            .company-logo {
                height: 40px;
            }
            .company-name-main {
                font-size: 1rem;
            }
            .company-name-sub {
                font-size: 0.6rem;
            }
        }

        /* Content management card styles */
        .content-card {
            margin-bottom: 20px;
        }

        .content-card .card-body {
            padding: 20px;
        }

        .placeholder-content {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 8px;
            color: #6c757d;
        }

        .placeholder-content i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #3988BD;
        }

        /* Logout button styling */
        .btn-logout {
            background: rgba(220, 53, 69, 0.9);
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #dc3545;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
    </style>

    <!-- Add Google Fonts to match Figma -->
    <link href="https://fonts.googleapis.com/css2?family=Castoro:ital@0;1&family=Hind:wght@300;400;500;600;700&family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                    <a class="sidebar-link active" data-tab="home">
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
                        <li><a data-tab="about-overview">Overview</a></li>
                        <li><a data-tab="about-business">Business Introduction</a></li>
                        <li><a data-tab="about-location">Location</a></li>
                        <li><a data-tab="about-history">History</a></li>
                        <li><a data-tab="about-iso">ISO Obtained</a></li>
                        <li><a data-tab="about-privacy">Privacy Policy</a></li>
                    </ul>
                </li>

                <!-- Recruitment Tab -->
                <li class="sidebar-item">
                    <a class="sidebar-link" data-tab="recruitment">
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
                        <li><a data-tab="news-media">Media Information</a></li>
                        <li><a data-tab="news-announcements">Announcements</a></li>
                    </ul>
                </li>

                <!-- Inquiry Tab -->
                <li class="sidebar-item">
                    <a class="sidebar-link" data-tab="inquiry">
                        <i class="fas fa-envelope"></i>
                        <span>Inquiry</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Home Panel (Existing Content) -->
            <div id="home-panel" class="content-panel active">
                <div class="alert alert-success">
                    <h4 class="alert-heading">Welcome, Admin!</h4>
                    <p>You have successfully logged in to the admin panel.</p>
                    <hr>
                    <p class="mb-0">This is the admin dashboard. Use the sidebar to manage content for your website.</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <p>Select a section from the sidebar to start managing content.</p>
                        <a href="/" class="btn btn-primary">View Website</a>
                    </div>
                </div>
            </div>

            <!-- About Us Panels -->
            <div id="about-overview-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Manage Overview Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Overview Content Management</h4>
                            <p>Form for editing the Overview page content will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Edit Content (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="about-business-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Manage Business Introduction Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Business Introduction Content Management</h4>
                            <p>Form for editing the Business Introduction page content will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Edit Content (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="about-location-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-map-marker-alt me-2"></i>Manage Location Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Location Content Management</h4>
                            <p>Form for editing the Location page content will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Edit Content (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="about-history-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-history me-2"></i>Manage History Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>History Content Management</h4>
                            <p>Form for editing the History page content will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Edit Content (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="about-iso-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-certificate me-2"></i>Manage ISO Obtained Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>ISO Obtained Content Management</h4>
                            <p>Form for editing the ISO Obtained page content will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Edit Content (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="about-privacy-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-shield-alt me-2"></i>Manage Privacy Policy Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Privacy Policy Content Management</h4>
                            <p>Form for editing the Privacy Policy page content will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Edit Content (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recruitment Panel -->
            <div id="recruitment-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-briefcase me-2"></i>Manage Recruitment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Recruitment Information Management</h4>
                            <p>Form for managing job postings and recruitment content will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Manage Jobs (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Panels -->
            <div id="news-media-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-photo-video me-2"></i>Manage Media Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Media Information Management</h4>
                            <p>Form for managing media releases and press information will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Manage Media (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="news-announcements-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-bullhorn me-2"></i>Manage Announcements</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Announcements Management</h4>
                            <p>Form for creating and managing announcements will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>Manage Announcements (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inquiry Panel -->
            <div id="inquiry-panel" class="content-panel">
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-envelope me-2"></i>Manage Inquiries</h5>
                    </div>
                    <div class="card-body">
                        <div class="placeholder-content">
                            <i class="fas fa-edit"></i>
                            <h4>Inquiry Management</h4>
                            <p>Form for viewing and managing customer inquiries will be placed here.</p>
                            <button class="btn btn-primary mt-3" disabled>View Inquiries (Coming Soon)</button>
                        </div>
                    </div>
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
    
    <script>
        // Handle success toast auto-hide
        var successToast = document.getElementById('dashboard-success-toast');
        if (successToast) {
            setTimeout(function() {
                successToast.classList.add('hide');
            }, 5000);
            setTimeout(function() {
                successToast.remove();
            }, 5600);
        }
        
        // Sidebar dropdown toggle functionality
        document.querySelectorAll('.dropdown-toggle-main').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
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

        // Tab switching functionality
        function switchTab(tabId) {
            // Hide all panels
            document.querySelectorAll('.content-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            
            // Show selected panel
            const selectedPanel = document.getElementById(tabId + '-panel');
            if (selectedPanel) {
                selectedPanel.classList.add('active');
            }
            
            // Update active state on sidebar links
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Remove active from dropdown items
            document.querySelectorAll('.sidebar-dropdown a').forEach(link => {
                link.classList.remove('active');
            });
            
            // Add active to clicked tab
            const activeLink = document.querySelector(`[data-tab="${tabId}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }

        // Add click handlers to all sidebar links
        document.querySelectorAll('[data-tab]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
            });
        });

        // Make sidebar main links also work
        document.querySelectorAll('.sidebar-link[data-tab]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
            });
        });

        // Disable browser back/forward cache (bfcache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                // Page was loaded from bfcache (back/forward cache)
                // Check if user is not authenticated (you can add a meta tag or data attribute)
                fetch('/admin/check-auth', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.authenticated) {
                        window.location.href = '/admin/login';
                    }
                })
                .catch(() => {
                    window.location.href = '/admin/login';
                });
            }
        });
        
        // Prevent back button from showing cached pages
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
            // Check auth status when back button is pressed
            fetch('/admin/check-auth', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.authenticated) {
                    window.location.href = '/admin/login';
                }
            })
            .catch(() => {
                window.location.href = '/admin/login';
            });
        };
    </script>
</body>
</html>