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