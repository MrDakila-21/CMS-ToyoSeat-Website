<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Toyoseat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Toyoseat Admin Panel</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="alert alert-success">
            <h4 class="alert-heading">Welcome, Admin!</h4>
            <p>You have successfully logged in to the admin panel.</p>
            <hr>
            <p class="mb-0">This is the admin dashboard. Your website's CRUD functionality will be implemented here later.</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <p>Content management will be added here in the next phase.</p>
                <a href="/" class="btn btn-primary">View Website</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dash.js') }}"></script>
    
    <script>
        // Initialize dashboard with dynamic content loading
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial content (home tab)
            loadContent('home');
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
                        initHomepageManagement();
                    }
                } else if (data.error) {
                    contentContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${data.error}
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
        
        // Tab switching logic
        document.querySelectorAll('[data-tab]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const mainTab = this.getAttribute('data-tab');
                const subTab = this.getAttribute('data-subtab');
                
                // Update active state on sidebar links
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
// try
