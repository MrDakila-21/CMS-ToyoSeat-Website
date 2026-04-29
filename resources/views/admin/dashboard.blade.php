<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin Dashboard - Toyoseat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dash.css') }}">
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
            <!-- Home Panel with Image Management -->
            <div id="home-panel" class="content-panel active">
                <div class="alert alert-success">
                    <h4 class="alert-heading">Welcome, Admin!</h4>
                    <p>You have successfully logged in to the admin panel.</p>
                    <hr>
                    <p class="mb-0">Manage your homepage background image below.</p>
                </div>
                
                <!-- Homepage Background Image Management Card -->
                <div class="card content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-image me-2"></i>Homepage Background Image</h5>
                    </div>
                    <div class="card-body">
                        <form id="homepageImageForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Current Image Preview -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Current Background Image</label>
                                        <div id="currentImagePreview" class="border rounded p-2 text-center" style="min-height: 200px; background-color: #f8f9fa;">
                                            <img id="previewImg" src="" alt="Current Background" style="max-width: 100%; max-height: 200px; display: none;">
                                            <div id="noImagePlaceholder" class="text-muted py-5">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <p>No background image uploaded yet</p>
                                                <small>Default GIF will be shown on website</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <!-- Upload New Image -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Upload New Background Image</label>
                                        <input type="file" class="form-control" id="backgroundImage" name="background_image" accept="image/jpeg,image/png,image/gif,image/webp">
                                        <div class="form-text mt-2">
                                            <i class="fas fa-info-circle"></i> Accepted formats: JPG, PNG, GIF, WEBP. Max size: 5MB
                                        </div>
                                    </div>
                                    
                                    <!-- New Image Preview with Remove Button -->
                                    <div class="mb-3" id="newImagePreviewContainer" style="display: none;">
                                        <label class="form-label fw-bold">New Image Preview</label>
                                        <div class="border rounded p-2 text-center" style="min-height: 150px; background-color: #f8f9fa; position: relative;">
                                            <img id="newPreviewImg" src="" alt="New Image Preview" style="max-width: 100%; max-height: 150px;">
                                            <button type="button" id="removeNewImageBtn" class="btn btn-sm btn-danger mt-2" style="position: absolute; top: 5px; right: 5px;">
                                                <i class="fas fa-times"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                                            <i class="fas fa-upload me-1"></i> Upload/Update Background Image
                                        </button>
                                        <button type="button" class="btn btn-danger ms-2" id="removeImageBtn">
                                            <i class="fas fa-trash-alt me-1"></i> Remove Background Image
                                        </button>
                                        <a href="{{ url('/') }}" class="btn btn-secondary ms-2" target="_blank">
                                            <i class="fas fa-eye me-1"></i> Preview Website
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Loading Modal -->
                <div class="modal fade" id="uploadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center py-4">
                                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h5>Processing...</h5>
                                <p class="text-muted mb-0">Please wait while your request is being processed.</p>
                            </div>
                        </div>
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
    <script src="{{ asset('js/dash.js') }}"></script>
    
    <!-- Add JavaScript to prevent back button access after logout -->
    <script>
        // Prevent back button access to dashboard after logout
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
                        // If not authenticated, redirect to login
                        window.location.href = '/admin/login';
                    }
                })
                .catch(error => {
                    console.error('Auth check failed:', error);
                });
            }
            
            // Check auth every 5 seconds
            setInterval(checkAuthStatus, 5000);
            
            // Check auth when page becomes visible (user returns to tab)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkAuthStatus();
                }
            });
        })();
    </script>
</body>
</html>