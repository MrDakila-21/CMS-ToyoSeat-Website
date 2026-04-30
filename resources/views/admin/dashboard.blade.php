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
</body>
</html>
// try
