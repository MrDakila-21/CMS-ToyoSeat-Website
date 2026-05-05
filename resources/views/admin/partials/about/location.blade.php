<div class="card content-card">
    <div class="card-header">
        <h5><i class="fas fa-map-marker-alt me-2"></i>Manage Location Content</h5>
    </div>
    <div class="card-body">
        <div id="locationForm">
            <form id="locationManagementForm">
                @csrf
                <input type="hidden" id="location_id" name="location_id">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="address_line1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" required disabled>
                        <div class="invalid-feedback" id="address_line1_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="address_line2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2" disabled>
                        <div class="invalid-feedback" id="address_line2_error"></div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="city" name="city" required disabled>
                        <div class="invalid-feedback" id="city_error"></div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="state" class="form-label">State/Province</label>
                        <input type="text" class="form-control" id="state" name="state" disabled>
                        <div class="invalid-feedback" id="state_error"></div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" disabled>
                        <div class="invalid-feedback" id="postal_code_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="country" name="country" required disabled>
                        <div class="invalid-feedback" id="country_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="+1 234 567 8900" disabled>
                        <div class="invalid-feedback" id="phone_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="company@example.com" disabled>
                        <div class="invalid-feedback" id="email_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitude (for Google Maps)</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="e.g., 40.7128" disabled>
                        <div class="invalid-feedback" id="latitude_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitude (for Google Maps)</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="e.g., -74.0060" disabled>
                        <div class="invalid-feedback" id="longitude_error"></div>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="google_maps_embed" class="form-label">Google Maps Embed Code</label>
                        <textarea class="form-control" id="google_maps_embed" name="google_maps_embed" rows="3" placeholder="Paste the Google Maps embed iframe code here..." disabled></textarea>
                        <small class="text-muted">Optional: You can either use coordinates above or paste an embed code here</small>
                        <div class="invalid-feedback" id="google_maps_embed_error"></div>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="working_hours" class="form-label">Working Hours</label>
                        <textarea class="form-control" id="working_hours" name="working_hours" rows="4" placeholder="Monday - Friday: 9:00 AM - 6:00 PM&#10;Saturday: 10:00 AM - 2:00 PM&#10;Sunday: Closed" disabled></textarea>
                        <div class="invalid-feedback" id="working_hours_error"></div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" id="editBtn">
                        <i class="fas fa-edit me-1"></i> Edit Location
                    </button>
                    <button type="button" class="btn btn-success" id="saveBtn" style="display: none;">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-danger" id="deleteBtn" style="display: none;">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancelBtn" style="display: none;">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Toast Container -->
<div id="customToast" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;">
    <div style="background: white; border-radius: 8px; padding: 15px 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 300px; display: flex; align-items: center; gap: 12px; border-left: 4px solid;">
        <i class="fas" id="toastIcon"></i>
        <span id="toastMessageText" style="flex: 1; font-size: 14px;"></span>
        <button type="button" onclick="closeToast()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999; padding: 0; line-height: 1;">&times;</button>
    </div>
</div>

<!-- Load jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Then load Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Toast functions
let toastTimeout;
let isProcessing = false; // Prevent multiple submissions
let originalFormData = {}; // Store original data for cancel functionality

function showToast(message, isError = false) {
    const toast = document.getElementById('customToast');
    if (!toast) {
        console.error('Toast element not found');
        alert(message); // Fallback
        return;
    }
    
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessageText');
    const toastContent = toast.querySelector('div');
    
    if (!toastIcon || !toastMessage || !toastContent) {
        console.error('Toast elements not found');
        alert(message);
        return;
    }
    
    // Clear existing timeout
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }
    
    // Set style based on type
    if (isError) {
        toastContent.style.borderLeftColor = '#dc3545';
        toastIcon.className = 'fas fa-exclamation-circle';
        toastIcon.style.color = '#dc3545';
    } else {
        toastContent.style.borderLeftColor = '#28a745';
        toastIcon.className = 'fas fa-check-circle';
        toastIcon.style.color = '#28a745';
    }
    
    // Set message
    toastMessage.textContent = message;
    
    // Show toast
    toast.style.display = 'block';
    
    // Auto hide after 3 seconds
    toastTimeout = setTimeout(() => {
        closeToast();
    }, 3000);
}

function closeToast() {
    const toast = document.getElementById('customToast');
    if (toast) {
        toast.style.display = 'none';
    }
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }
}

// Enable/Disable form fields
function setFormFieldsEnabled(enabled) {
    const inputs = $('#locationManagementForm').find('input, textarea, select');
    inputs.prop('disabled', !enabled);
}

// Clear validation errors
function clearErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

// Display validation errors
function displayErrors(errors) {
    for (let field in errors) {
        const errorField = $(`#${field}`);
        const errorDiv = $(`#${field}_error`);
        if (errorField.length) {
            errorField.addClass('is-invalid');
            errorDiv.text(errors[field][0]);
        }
    }
}

// Capture current form data before editing
function captureOriginalData() {
    originalFormData = {
        address_line1: $('#address_line1').val(),
        address_line2: $('#address_line2').val(),
        city: $('#city').val(),
        state: $('#state').val(),
        postal_code: $('#postal_code').val(),
        country: $('#country').val(),
        phone: $('#phone').val(),
        email: $('#email').val(),
        latitude: $('#latitude').val(),
        longitude: $('#longitude').val(),
        google_maps_embed: $('#google_maps_embed').val(),
        working_hours: $('#working_hours').val()
    };
}

// Restore original data when cancelling
function restoreOriginalData() {
    if (Object.keys(originalFormData).length > 0) {
        $('#address_line1').val(originalFormData.address_line1);
        $('#address_line2').val(originalFormData.address_line2);
        $('#city').val(originalFormData.city);
        $('#state').val(originalFormData.state);
        $('#postal_code').val(originalFormData.postal_code);
        $('#country').val(originalFormData.country);
        $('#phone').val(originalFormData.phone);
        $('#email').val(originalFormData.email);
        $('#latitude').val(originalFormData.latitude);
        $('#longitude').val(originalFormData.longitude);
        $('#google_maps_embed').val(originalFormData.google_maps_embed);
        $('#working_hours').val(originalFormData.working_hours);
    }
}

// Reset form to view mode
function resetToViewMode(resetData = false) {
    setFormFieldsEnabled(false);
    $('#editBtn').show();
    $('#saveBtn').hide();
    $('#cancelBtn').hide();
    clearErrors();
    isProcessing = false;
    
    // Reset save button to original state
    const saveBtn = $('#saveBtn');
    saveBtn.prop('disabled', false);
    saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
    
    // Reset delete button to original state
    const deleteBtn = $('#deleteBtn');
    deleteBtn.prop('disabled', false);
    deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
    
    // Reset original data if requested
    if (resetData) {
        originalFormData = {};
    }
}

// Load existing location data
function loadLocationData() {
    if (isProcessing) return;
    
    $.ajax({
        url: '/admin/location/get',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.location) {
                const loc = response.location;
                $('#location_id').val(loc.id);
                $('#address_line1').val(loc.address_line1 || '');
                $('#address_line2').val(loc.address_line2 || '');
                $('#city').val(loc.city || '');
                $('#state').val(loc.state || '');
                $('#postal_code').val(loc.postal_code || '');
                $('#country').val(loc.country || '');
                $('#phone').val(loc.phone || '');
                $('#email').val(loc.email || '');
                $('#latitude').val(loc.latitude || '');
                $('#longitude').val(loc.longitude || '');
                $('#google_maps_embed').val(loc.google_maps_embed || '');
                $('#working_hours').val(loc.working_hours || '');
                $('#deleteBtn').show();
            } else {
                $('#deleteBtn').hide();
                $('#locationManagementForm')[0].reset();
                $('#location_id').val('');
            }
        },
        error: function(xhr) {
            console.log('No existing location found or error loading:', xhr);
            $('#deleteBtn').hide();
            $('#locationManagementForm')[0].reset();
            $('#location_id').val('');
        }
    });
}

// Wait for DOM to be fully loaded
$(document).ready(function() {
    // Edit button click handler
    $('#editBtn').on('click', function() {
        if (isProcessing) return;
        
        // Capture the current data before editing
        captureOriginalData();
        
        // Ensure save button is properly reset before showing it
        const saveBtn = $('#saveBtn');
        saveBtn.prop('disabled', false);
        saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
        
        // Ensure delete button is properly reset before showing it
        const deleteBtn = $('#deleteBtn');
        deleteBtn.prop('disabled', false);
        deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
        
        setFormFieldsEnabled(true);
        $('#editBtn').hide();
        $('#saveBtn').show();
        $('#deleteBtn').show();
        $('#cancelBtn').show();
        clearErrors();
        showToast('Edit mode enabled. You can now edit the location details.', false);
    });
    
    // Cancel button click handler - Restores original data
    $('#cancelBtn').on('click', function() {
        if (isProcessing) return;
        
        // Restore the original data that was captured when edit was clicked
        restoreOriginalData();
        
        // Reset to view mode
        setFormFieldsEnabled(false);
        $('#editBtn').show();
        $('#saveBtn').hide();
        $('#deleteBtn').show(); // Show delete button if location exists
        $('#cancelBtn').hide();
        clearErrors();
        
        // Reset save button state
        const saveBtn = $('#saveBtn');
        saveBtn.prop('disabled', false);
        saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
        
        // Reset delete button state
        const deleteBtn = $('#deleteBtn');
        deleteBtn.prop('disabled', false);
        deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
        
        // Reset processing flag
        isProcessing = false;
        
        // Clear captured original data
        originalFormData = {};
        
        showToast('Changes discarded. Form reset to saved state.', false);
    });
    
    // Save button click handler
    $('#saveBtn').on('click', function(e) {
        e.preventDefault();
        
        // Prevent multiple submissions
        if (isProcessing) {
            showToast('Please wait, processing your request...', false);
            return;
        }
        
        const locationId = $('#location_id').val();
        
        // Clear previous errors
        clearErrors();
        
        // Validate required fields
        const requiredFields = ['address_line1', 'city', 'country'];
        let isValid = true;
        
        for (let field of requiredFields) {
            if (!$(`#${field}`).val().trim()) {
                $(`#${field}`).addClass('is-invalid');
                $(`#${field}_error`).text(`${field.replace('_', ' ')} is required.`);
                isValid = false;
            }
        }
        
        if (!isValid) {
            showToast('Please fill in all required fields.', true);
            return;
        }
        
        // Prepare data
        let formData = {
            address_line1: $('#address_line1').val(),
            address_line2: $('#address_line2').val(),
            city: $('#city').val(),
            state: $('#state').val(),
            postal_code: $('#postal_code').val(),
            country: $('#country').val(),
            phone: $('#phone').val(),
            email: $('#email').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            google_maps_embed: $('#google_maps_embed').val(),
            working_hours: $('#working_hours').val(),
            _token: '{{ csrf_token() }}'
        };
        
        let ajaxConfig = {
            url: '',
            method: 'POST',
            data: formData,
            beforeSend: function() {
                isProcessing = true;
                // Disable save button and show loading text
                const saveBtn = $('#saveBtn');
                saveBtn.prop('disabled', true);
                saveBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, false);
                    
                    if ((!locationId || locationId === '') && response.location) {
                        $('#location_id').val(response.location.id);
                    }
                    
                    // Clear original data since we saved
                    originalFormData = {};
                    
                    // Reset to view mode (disables form and shows edit button)
                    resetToViewMode(true);
                    
                    // Reload the latest data from server to ensure consistency
                    loadLocationData();
                } else {
                    showToast(response.message || 'An error occurred', true);
                    isProcessing = false;
                    // Restore save button
                    const saveBtn = $('#saveBtn');
                    saveBtn.prop('disabled', false);
                    saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    displayErrors(xhr.responseJSON.errors);
                    showToast('Please correct the errors above.', true);
                } else {
                    let errorMsg = 'An error occurred while saving. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast(errorMsg, true);
                }
                console.error('Save error:', xhr);
                isProcessing = false;
                // Restore save button
                const saveBtn = $('#saveBtn');
                saveBtn.prop('disabled', false);
                saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
            }
        };
        
        // Set URL based on whether we're updating or creating
        if (locationId && locationId !== '') {
            ajaxConfig.url = `/admin/location/update/${locationId}`;
            formData._method = 'PUT';
        } else {
            ajaxConfig.url = '/admin/location/store';
        }
        
        $.ajax(ajaxConfig);
    });
    
    // Delete button
    $('#deleteBtn').on('click', function() {
        if (isProcessing) {
            showToast('Please wait, processing your request...', false);
            return;
        }
        
        if (confirm('Are you sure you want to delete this location?')) {
            const locationId = $('#location_id').val();
            if (!locationId || locationId === '') {
                showToast('No location to delete.', true);
                return;
            }
            
            isProcessing = true;
            const deleteBtn = $('#deleteBtn');
            deleteBtn.prop('disabled', true);
            deleteBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');
            
            $.ajax({
                url: `/admin/location/delete/${locationId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, false);
                        $('#locationManagementForm')[0].reset();
                        $('#location_id').val('');
                        
                        // Clear original data
                        originalFormData = {};
                        
                        // Reset to view mode (form disabled, edit button shown)
                        resetToViewMode(true);
                        // Hide delete button since no location exists
                        $('#deleteBtn').hide();
                    } else {
                        showToast(response.message || 'Error deleting location', true);
                        isProcessing = false;
                        // Restore delete button
                        deleteBtn.prop('disabled', false);
                        deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error deleting location. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast(errorMsg, true);
                    console.error('Delete error:', xhr);
                    isProcessing = false;
                    // Restore delete button
                    deleteBtn.prop('disabled', false);
                    deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
                }
            });
        }
    });
    
    // Initial load - form starts in disabled state
    setFormFieldsEnabled(false);
    loadLocationData();
});
</script>

<style>
/* Additional custom styles for form fields */
.form-control:disabled, 
textarea:disabled {
    background-color: #e9ecef;
    opacity: 1;
    cursor: not-allowed;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.btn:disabled {
    transform: none;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Spinner animation for button */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Form input focus styles */
.form-control:focus {
    border-color: #3988BD;
    box-shadow: 0 0 0 0.2rem rgba(57, 136, 189, 0.25);
}

/* Invalid feedback styling */
.form-control.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
</style>