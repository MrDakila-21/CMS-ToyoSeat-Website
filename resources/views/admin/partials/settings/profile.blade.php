<div class="settings-container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Account Profile
                    </h5>
                    <button type="button" id="editButton" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </button>
                </div>
                <div class="card-body">
                    <form id="settingsForm">
                        @csrf
                        
                        <!-- Display Name (Always updatable) -->
                        <div class="mb-4">
                            <label for="display_name" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Display Name
                            </label>
                            <input type="text" class="form-control form-control-lg" style="font-size: medium;" id="display_name" name="display_name" 
                                   value="{{ $user->display_name }}" disabled>
                            <small class="text-muted">This name appears at the top of your admin panel</small>
                        </div>
                        
                        <!-- Login Username -->
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>Login Username
                            </label>
                            <input type="text" class="form-control form-control-lg" style="font-size: medium;"id="username" name="username" 
                                   value="{{ $user->name }}" disabled>
                            <small class="text-muted">You'll use this to log in to your account</small>
                        </div>
                        
                        <!-- Password Change Section (Optional) -->
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="changePasswordCheckbox" disabled>
                                <label class="form-check-label fw-bold" for="changePasswordCheckbox">
                                    <i class="fas fa-key me-2"></i>I want to change my password
                                </label>
                            </div>
                            
                            <div id="passwordFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" style="font-size: medium;" id="new_password" name="new_password" disabled>
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" style="font-size: medium;"  id="confirm_password" name="confirm_password" disabled>
                                    <small class="text-muted">Type your new password again to confirm</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current Password (Required for ANY change) -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-bold">
                                <i class="fas fa-lock me-2"></i>Current Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control form-control-lg" style="font-size: medium;" id="current_password" name="current_password" required disabled>
                            <small class="text-muted">Required to confirm any changes to your account</small>
                        </div>
                        
                        <!-- Account Information (Read Only) -->
                        <div class="alert alert-info mt-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Your Account Details:</strong><br>
                                    Account Type: <span class="badge {{ $user->account_type === 'superadmin' ? 'bg-danger' : 'bg-info' }}">{{ ucfirst($user->account_type) }}</span><br>
                                    Status: <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" disabled>
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-container .page-title {
    font-size: 1.5rem;
    margin-bottom: 0;
}

.settings-container .card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 10px;
}

.settings-container .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0 !important;
}

/* Style the edit button to match user management */
.settings-container .card-header .btn-primary {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.settings-container .card-header .btn-primary i {
    margin-right: 0.5rem;
}

/* Cancel button style matching user management */
.settings-container .card-header .btn-secondary {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.settings-container .form-control-lg {
    border-radius: 8px;
}

.settings-container .btn-lg {
    border-radius: 8px;
    padding: 12px;
}

.settings-container .alert {
    border-radius: 8px;
}

.settings-container .form-check-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.settings-container .form-check-label {
    cursor: pointer;
}

/* Remove horizontal space constraints */
.settings-container .row {
    margin-left: 0;
    margin-right: 0;
}

.settings-container .col-md-8 {
    width: 100%;
    padding-left: 0;
    padding-right: 0;
}

.settings-container .col-md-8.mx-auto {
    margin-left: 0;
    margin-right: 0;
}

/* Make card full width */
.settings-container .card {
    width: 100%;
}

@media (min-width: 768px) {
    .settings-container .col-md-8 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

<script>
let isEditMode = false;

// Edit button functionality
document.getElementById('editButton').addEventListener('click', function() {
    if (!isEditMode) {
        // Enter edit mode
        isEditMode = true;
        
        // Enable all form fields
        document.getElementById('display_name').disabled = false;
        document.getElementById('username').disabled = false;
        document.getElementById('changePasswordCheckbox').disabled = false;
        document.getElementById('current_password').disabled = false;
        
        // Enable password fields if they are visible
        if (document.getElementById('passwordFields').style.display === 'block') {
            document.getElementById('new_password').disabled = false;
            document.getElementById('confirm_password').disabled = false;
        }
        
        // Enable save button
        document.querySelector('#settingsForm button[type="submit"]').disabled = false;
        
        // Change edit button appearance (like in user management)
        this.innerHTML = '<i class="fas fa-times me-2"></i>Cancel';
        this.classList.remove('btn-primary');
        this.classList.add('btn-secondary');
        
        // Focus on display name
        document.getElementById('display_name').focus();
    } else {
        // Cancel edit mode
        cancelEditMode();
    }
});

// Cancel edit mode
function cancelEditMode() {
    isEditMode = false;
    
    // Reset form to original values
    document.getElementById('display_name').value = "{{ $user->display_name }}";
    document.getElementById('username').value = "{{ $user->name }}";
    document.getElementById('current_password').value = '';
    document.getElementById('changePasswordCheckbox').checked = false;
    document.getElementById('passwordFields').style.display = 'none';
    document.getElementById('new_password').value = '';
    document.getElementById('confirm_password').value = '';
    
    // Disable all form fields
    document.getElementById('display_name').disabled = true;
    document.getElementById('username').disabled = true;
    document.getElementById('changePasswordCheckbox').disabled = true;
    document.getElementById('current_password').disabled = true;
    document.getElementById('new_password').disabled = true;
    document.getElementById('confirm_password').disabled = true;
    
    // Disable save button
    document.querySelector('#settingsForm button[type="submit"]').disabled = true;
    
    // Reset edit button to original style (matching user management)
    const editBtn = document.getElementById('editButton');
    editBtn.innerHTML = '<i class="fas fa-edit me-2"></i>Edit';
    editBtn.classList.remove('btn-secondary');
    editBtn.classList.add('btn-primary');
}

// Show/hide password fields when checkbox is toggled
document.getElementById('changePasswordCheckbox').addEventListener('change', function() {
    const passwordFields = document.getElementById('passwordFields');
    if (this.checked) {
        passwordFields.style.display = 'block';
        if (isEditMode) {
            document.getElementById('new_password').disabled = false;
            document.getElementById('confirm_password').disabled = false;
            document.getElementById('new_password').required = true;
            document.getElementById('confirm_password').required = true;
        }
    } else {
        passwordFields.style.display = 'none';
        document.getElementById('new_password').disabled = true;
        document.getElementById('confirm_password').disabled = true;
        document.getElementById('new_password').value = '';
        document.getElementById('confirm_password').value = '';
        document.getElementById('new_password').required = false;
        document.getElementById('confirm_password').required = false;
    }
});

document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!isEditMode) {
        showToast('info', 'Please click Edit button to make changes');
        return;
    }
    
    const changePassword = document.getElementById('changePasswordCheckbox').checked;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const currentPassword = document.getElementById('current_password').value;
    const newUsername = document.getElementById('username').value;
    const newDisplayName = document.getElementById('display_name').value;
    const currentUsername = "{{ $user->name }}";
    const currentDisplayName = "{{ $user->display_name }}";
    
    // Check if there are any actual changes
    const hasDisplayNameChange = newDisplayName !== currentDisplayName;
    const hasUsernameChange = newUsername !== currentUsername;
    const hasPasswordChange = changePassword && newPassword !== '';
    
    if (!hasDisplayNameChange && !hasUsernameChange && !hasPasswordChange) {
        showToast('info', 'No changes were made to your account');
        cancelEditMode();
        return;
    }
    
    // Check if password is being changed but same as current
    if (hasPasswordChange && newPassword === currentPassword) {
        showToast('error', 'New password cannot be the same as your current password');
        return;
    }
    
    // Validate password if being changed
    if (hasPasswordChange) {
        if (newPassword !== confirmPassword) {
            showToast('error', 'New password and confirmation do not match');
            return;
        }
        
        if (newPassword.length < 6) {
            showToast('error', 'New password must be at least 6 characters');
            return;
        }
    }
    
    // Check if username is being changed but same as current
    if (hasUsernameChange && newUsername === currentUsername) {
        showToast('error', 'New username is the same as your current username');
        return;
    }
    
    // Check if display name is being changed but same as current
    if (hasDisplayNameChange && newDisplayName === currentDisplayName) {
        showToast('error', 'New display name is the same as your current display name');
        return;
    }
    
    // Require current password for any change
    if (!currentPassword) {
        showToast('error', 'Current password is required to make any changes');
        return;
    }
    
    // Prepare form data
    const formData = {
        display_name: newDisplayName,
        username: newUsername,
        current_password: currentPassword,
        _token: document.querySelector('meta[name="csrf-token"]').content
    };
    
    if (hasPasswordChange) {
        formData.new_password = newPassword;
        formData.confirm_password = confirmPassword;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#settingsForm button[type="submit"]');
    const editBtn = document.getElementById('editButton');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;
    editBtn.disabled = true;
    
    // Send update request
    fetch('/admin/settings/update', {
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
            
            // Update display name in header (always update if changed)
            if (data.user && data.user.display_name) {
                const headerWelcome = document.querySelector('.navbar-container span:first-child');
                if (headerWelcome) {
                    const currentText = headerWelcome.innerHTML;
                    const newText = currentText.replace(/Welcome, .+!/, `Welcome, ${data.user.display_name}!`);
                    headerWelcome.innerHTML = newText;
                }
            }
            
            // Logout if username OR password was changed
            if (data.username_changed || data.password_changed) {
                let message = '';
                if (data.username_changed && data.password_changed) {
                    message = 'Username and password changed!';
                } else if (data.username_changed) {
                    message = 'Username changed!';
                } else if (data.password_changed) {
                    message = 'Password changed!';
                }
                showToast('info', message + ' You will be logged out in 3 seconds. Please login again.');
                setTimeout(() => {
                    fetch('/admin/logout', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    }).then(() => {
                        window.location.href = '/admin/login';
                    });
                }, 3000);
            } else {
                // Successfully saved changes without logout - exit edit mode
                cancelEditMode();
                
                // Update the current values in the form for next edit
                if (data.user) {
                    document.getElementById('username').value = data.user.name;
                    document.getElementById('display_name').value = data.user.display_name;
                }
            }
        } else {
            if (data.errors) {
                let errorMsg = '';
                for (let field in data.errors) {
                    errorMsg += data.errors[field].join(', ') + '\n';
                }
                showToast('error', errorMsg);
            } else {
                showToast('error', data.message || 'Failed to update settings');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred while saving your changes');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = !isEditMode;
        editBtn.disabled = false;
    });
});

// Helper function to show toast messages
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `floating-toast ${type}-toast`;
    toast.innerHTML = `
        <div class="floating-toast-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'info' ? 'fa-info-circle' : 'fa-exclamation-circle'} me-2"></i>
            <span>${message.replace(/\n/g, '<br>')}</span>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 5000);
    }, 5000);
}
</script>