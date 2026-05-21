<div class="users-management-container">
    <!-- Users Table with Integrated Filters -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>System Users
            </h5>
            <div class="d-flex gap-2">
                <select id="statusFilter" class="form-select form-select-sm" style="width: 130px;">
                    <option value="all">All Status</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>
                <select id="typeFilter" class="form-select form-select-sm" style="width: 150px;">
                    <option value="all">All Account Types</option>
                    <option value="admin">Admin Only</option>
                    <option value="superadmin">Super Admin Only</option>
                </select>
                <button id="applyFilters" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search"></i>
                </button>
                   <button type="button" class="btn btn-primary" onclick="showAddUserModal()">
            <i class="fas fa-user-plus me-2"></i>Add New User
        </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Username</th>
                            <th>Display Name</th>
                            <th style="width: 120px;">Account Type</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 150px;">Created At</th>
                            <th style="width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        @include('admin.partials.users.user_rows', ['users' => $users])
                    </tbody>
                </table>
            </div>
            <div id="pagination-links" class="mt-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="text-muted">Used for login (unique)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="display_name" name="display_name" required>
                        <small class="text-muted">Name shown in the interface</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted" id="password-hint">Leave blank to keep current password (for edit)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="account_type" class="form-label">Account Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="account_type" name="account_type" required>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.users-management-container .page-title {
    font-size: 1.5rem;
    margin-bottom: 0;
}

.users-management-container .table td {
    vertical-align: middle;
}

.users-management-container .badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

.users-management-container .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    width: 32px;
    margin: 0 2px;
}

.users-management-container .btn-sm i {
    margin: 0;
}

.users-management-container .card-header .form-select-sm,
.users-management-container .card-header .btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.users-management-container .card-header .btn-sm {
    width: auto;
    padding: 0.375rem 0.75rem;
}

.gap-2 {
    gap: 0.5rem;
}
</style>

<script>
// Global variables
let currentEditUserId = null;

// Show Add User Modal
function showAddUserModal() {
    currentEditUserId = null;
    document.getElementById('userForm').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('password').required = true;
    document.getElementById('password').value = '';
    document.getElementById('password-hint').innerHTML = '<span class="text-warning">Required for new user</span>';
    document.getElementById('userModalLabel').innerText = 'Add New User';
    
    // Initialize modal
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

// Edit User
function editUser(userId) {
    currentEditUserId = userId;
    
    // Fetch user data
    fetch(`/admin/users/${userId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.user;
            document.getElementById('user_id').value = user.id;
            document.getElementById('name').value = user.name;
            document.getElementById('display_name').value = user.display_name;
            document.getElementById('account_type').value = user.account_type;
            document.getElementById('is_active').value = user.is_active ? '1' : '0';
            document.getElementById('password').value = '';
            document.getElementById('password').required = false;
            document.getElementById('password-hint').innerHTML = 'Leave blank to keep current password';
            document.getElementById('userModalLabel').innerText = 'Edit User';
            
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        } else {
            showToast('error', data.message || 'Failed to load user data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred while loading user data');
    });
}

// Delete User
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                // Reload page with current filters
                applyFilters();
            } else {
                showToast('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred while deleting the user');
        });
    }
}

// Toggle User Status
function toggleUserStatus(userId) {
    fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            // Reload page with current filters
            applyFilters();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred while toggling user status');
    });
}

// Apply filters and reload table
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    const page = document.querySelector('[name="page"]')?.value || 1;
    
    // Show loading state
    const tbody = document.getElementById('users-table-body');
    tbody.innerHTML = '<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>';
    
    fetch(`/admin/users/filter?status=${status}&type=${type}&page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update table body
            tbody.innerHTML = data.html;
            // Update pagination
            document.getElementById('pagination-links').innerHTML = data.pagination;
            // Update URL without reload
            const url = new URL(window.location.href);
            url.searchParams.set('status', status);
            url.searchParams.set('type', type);
            window.history.pushState({}, '', url);
        } else {
            showToast('error', 'Failed to load users');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred while loading users');
    });
}

// Handle form submission
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('user_id').value;
    const isEdit = userId !== '';
    const url = isEdit ? `/admin/users/${userId}` : '/admin/users';
    
    const formData = {
        name: document.getElementById('name').value,
        display_name: document.getElementById('display_name').value,
        account_type: document.getElementById('account_type').value,
        is_active: parseInt(document.getElementById('is_active').value),
        _token: document.querySelector('meta[name="csrf-token"]').content,
        _method: isEdit ? 'PUT' : 'POST'
    };
    
    const password = document.getElementById('password').value;
    if (password) {
        formData.password = password;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
            modal.hide();
            // Reload with current filters
            applyFilters();
        } else {
            if (data.errors) {
                let errorMsg = 'Validation errors:\n';
                for (let field in data.errors) {
                    errorMsg += `- ${data.errors[field].join(', ')}\n`;
                }
                showToast('error', errorMsg);
            } else {
                showToast('error', data.message || 'Failed to save user');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred while saving the user');
    });
});

// Helper function to show toast messages
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `floating-toast ${type}-toast`;
    toast.innerHTML = `
        <div class="floating-toast-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Event listeners
document.getElementById('applyFilters').addEventListener('click', function() {
    applyFilters();
});

// Auto-apply filters when dropdown changes
document.getElementById('statusFilter').addEventListener('change', function() {
    applyFilters();
});
document.getElementById('typeFilter').addEventListener('change', function() {
    applyFilters();
});

// Load filters from URL on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('status');
    const typeParam = urlParams.get('type');
    
    if (statusParam && ['all', 'active', 'inactive'].includes(statusParam)) {
        document.getElementById('statusFilter').value = statusParam;
    }
    if (typeParam && ['all', 'admin', 'superadmin'].includes(typeParam)) {
        document.getElementById('typeFilter').value = typeParam;
    }
    
    // Apply filters if they exist in URL
    if (statusParam || typeParam) {
        applyFilters();
    }
});
</script>