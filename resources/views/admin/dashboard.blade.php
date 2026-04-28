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
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">
                Logout
            </button>
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
    
    <!-- Disable forward/back navigation after logout -->
    <script>
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