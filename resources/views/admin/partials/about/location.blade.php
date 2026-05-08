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
                        <input type="text" class="form-control" id="city" name="city" value="Santa Rosa" required disabled>
                        <div class="invalid-feedback" id="city_error"></div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="state" class="form-label">State/Province</label>
                        <input type="text" class="form-control" id="state" name="state" value="Laguna" disabled>
                        <div class="invalid-feedback" id="state_error"></div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="4026" disabled>
                        <div class="invalid-feedback" id="postal_code_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="country" name="country" value="Philippines" required disabled>
                        <div class="invalid-feedback" id="country_error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="+63 2 8123 4567" disabled>
                        <div class="invalid-feedback" id="phone_error"></div>
                    </div>
           
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="company@example.com" disabled>
                        <div class="invalid-feedback" id="email_error"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label">Telephone Number</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="(049) 123-4567" disabled>
                        <div class="invalid-feedback" id="telephone_error"></div>
                    </div>
         
                    
                    <div class="col-12 mb-3">
                        <label for="google_maps_embed" class="form-label">Google Maps Embed Code</label>
                        <textarea class="form-control" id="google_maps_embed" name="google_maps_embed" rows="3" placeholder="Paste the Google Maps embed iframe code here..." disabled></textarea>
                        <small class="text-muted">Paste Google Maps embed iframe code here</small>
                        <div class="invalid-feedback" id="google_maps_embed_error"></div>
                    </div>
                    
                    <!-- Improved Working Hours Section with Day Range -->
                    <div class="col-12 mb-3">
                        <label for="working_hours" class="form-label">Working Hours</label>
                        <div id="workingHoursContainer">
                            <div id="workingHoursList">
                                <!-- Working hours will be dynamically added here -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addWorkingHourBtn" disabled>
                                <i class="fas fa-plus"></i> Add Working Hours Entry
                            </button>
                        </div>
                        <textarea class="form-control d-none" id="working_hours" name="working_hours" rows="4" disabled></textarea>
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
    <div style="background: white; border-radius: 8px; padding: 15px 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 350px; display: flex; align-items: center; gap: 12px; border-left: 4px solid;">
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
let isProcessing = false;
let originalFormData = {};

// Day options for working hours
const daysOfWeek = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
];

function showToast(message, isError = false) {
    const toast = document.getElementById('customToast');
    if (!toast) {
        console.error('Toast element not found');
        alert(message);
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
    
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }
    
    if (isError) {
        toastContent.style.borderLeftColor = '#dc3545';
        toastIcon.className = 'fas fa-exclamation-circle';
        toastIcon.style.color = '#dc3545';
    } else {
        toastContent.style.borderLeftColor = '#28a745';
        toastIcon.className = 'fas fa-check-circle';
        toastIcon.style.color = '#28a745';
    }
    
    toastMessage.textContent = message;
    toast.style.display = 'block';
    
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

// Working Hours Management Functions with Day Range Support
function parseWorkingHoursText(text) {
    const hours = [];
    if (!text) return hours;
    
    const lines = text.split('\n');
    for (const line of lines) {
        if (line.trim()) {
            const colonIndex = line.indexOf(':');
            if (colonIndex > 0) {
                const dayRange = line.substring(0, colonIndex).trim();
                const time = line.substring(colonIndex + 1).trim();
                
                // Parse day range (e.g., "Monday - Friday" or "Saturday")
                let fromDay = '';
                let toDay = '';
                if (dayRange.includes('-')) {
                    const days = dayRange.split('-').map(d => d.trim());
                    fromDay = days[0];
                    toDay = days[1];
                } else {
                    fromDay = dayRange;
                    toDay = dayRange;
                }
                
                hours.push({ 
                    fromDay, 
                    toDay, 
                    time,
                    displayText: dayRange 
                });
            }
        }
    }
    return hours;
}

function formatWorkingHoursText(hoursArray) {
    return hoursArray.map(hour => {
        let dayDisplay = hour.fromDay;
        if (hour.toDay && hour.toDay !== hour.fromDay) {
            dayDisplay = `${hour.fromDay} - ${hour.toDay}`;
        }
        return `${dayDisplay}: ${hour.time}`;
    }).join('\n');
}

// Helper function to format time for display in the working hours list
function formatTimeDisplay(time) {
    if (!time || time === 'Closed') return time;
    
    function formatSingleTimeDisplay(t) {
        if (!t) return '';
        // Check if already has AM/PM
        if (/(am|pm)/i.test(t)) return t;
        
        // Parse 24-hour format
        const match = t.match(/(\d{1,2}):(\d{2})/);
        if (match) {
            let hour = parseInt(match[1]);
            const minute = match[2];
            const ampm = hour >= 12 ? 'PM' : 'AM';
            hour = hour % 12;
            if (hour === 0) hour = 12;
            return `${hour}:${minute} ${ampm}`;
        }
        return t;
    }
    
    if (time.includes('-')) {
        const times = time.split('-').map(t => formatSingleTimeDisplay(t.trim()));
        return times.join(' - ');
    }
    
    return formatSingleTimeDisplay(time);
}

function renderWorkingHoursList(hoursArray, enabled = false) {
    const container = $('#workingHoursList');
    container.empty();
    
    if (hoursArray.length === 0) {
        // Add default empty entry
        hoursArray.push({ fromDay: 'Monday', toDay: 'Friday', time: '' });
    }
    
    hoursArray.forEach((hour, index) => {
        const hourDiv = $('<div>').addClass('working-hour-item mb-3 p-3 border rounded').css('background', '#f8f9fa');
        
        const row1 = $('<div>').addClass('row g-2 mb-2');
        const row2 = $('<div>').addClass('row g-2');
        
        // From Day dropdown
        const fromDayCol = $('<div>').addClass('col-md-5');
        const fromDaySelect = $('<select>')
            .addClass('form-select working-hour-from-day')
            .attr('data-index', index)
            .prop('disabled', !enabled);
        
        daysOfWeek.forEach(day => {
            const option = $('<option>').val(day).text(day);
            if (hour.fromDay === day) option.prop('selected', true);
            fromDaySelect.append(option);
        });
        
        fromDayCol.append($('<label>').addClass('form-label small mb-1').text('From Day'));
        fromDayCol.append(fromDaySelect);
        
        // To Day dropdown
        const toDayCol = $('<div>').addClass('col-md-5');
        const toDaySelect = $('<select>')
            .addClass('form-select working-hour-to-day')
            .attr('data-index', index)
            .prop('disabled', !enabled);
        
        daysOfWeek.forEach(day => {
            const option = $('<option>').val(day).text(day);
            if (hour.toDay === day) option.prop('selected', true);
            toDaySelect.append(option);
        });
        
        toDayCol.append($('<label>').addClass('form-label small mb-1').text('To Day'));
        toDayCol.append(toDaySelect);
        
        // Remove button
        const actionCol = $('<div>').addClass('col-md-2 d-flex align-items-end');
        const removeBtn = $('<button>')
            .attr('type', 'button')
            .addClass('btn btn-sm btn-danger w-100')
            .html('<i class="fas fa-trash"></i> Remove')
            .prop('disabled', !enabled)
            .on('click', function() {
                if (enabled) {
                    $(this).closest('.working-hour-item').remove();
                    updateWorkingHoursText();
                }
            });
        
        actionCol.append(removeBtn);
        
        row1.append(fromDayCol, toDayCol, actionCol);
        
        // Time inputs
        const startTimeCol = $('<div>').addClass('col-md-3');
        const startTimeInput = $('<input>')
            .attr('type', 'time')
            .addClass('form-control working-hour-start-time')
            .attr('data-index', index)
            .prop('disabled', !enabled);
        
        let startTime = '';
        let endTime = '';
        if (hour.time && hour.time !== 'Closed' && hour.time.includes('-')) {
            const times = hour.time.split('-').map(t => t.trim());
            startTime = times[0];
            endTime = times[1];
        } else if (hour.time && hour.time !== 'Closed') {
            startTime = hour.time;
            endTime = '';
        }
        
        startTimeInput.val(startTime);
        
        startTimeCol.append($('<label>').addClass('form-label small mb-1').text('Start Time'));
        startTimeCol.append(startTimeInput);
        
        const separatorCol = $('<div>').addClass('col-md-1 d-flex align-items-center justify-content-center');
        separatorCol.append($('<span>').addClass('text-muted').text('to'));
        
        const endTimeCol = $('<div>').addClass('col-md-3');
        const endTimeInput = $('<input>')
            .attr('type', 'time')
            .addClass('form-control working-hour-end-time')
            .attr('data-index', index)
            .val(endTime)
            .prop('disabled', !enabled);
        
        endTimeCol.append($('<label>').addClass('form-label small mb-1').text('End Time'));
        endTimeCol.append(endTimeInput);
        
        const closedCheckCol = $('<div>').addClass('col-md-3 d-flex align-items-end');
        const closedCheckDiv = $('<div>').addClass('form-check mb-2');
        const closedCheckbox = $('<input>')
            .attr('type', 'checkbox')
            .addClass('form-check-input working-hour-closed')
            .attr('data-index', index)
            .prop('disabled', !enabled)
            .prop('checked', hour.time === 'Closed');
        
        const closedLabel = $('<label>').addClass('form-check-label').text('Closed');
        
        closedCheckDiv.append(closedCheckbox, closedLabel);
        closedCheckCol.append(closedCheckDiv);
        
        row2.append(startTimeCol, separatorCol, endTimeCol, closedCheckCol);
        
        // Add preview text for disabled mode
        if (!enabled && hour.time && hour.time !== 'Closed') {
            const previewRow = $('<div>').addClass('row mt-2');
            const previewCol = $('<div>').addClass('col-12');
            const formattedTime = formatTimeDisplay(hour.time);
            previewCol.append($('<small>').addClass('text-muted').html(`<i class="fas fa-clock"></i> ${formattedTime}`));
            previewRow.append(previewCol);
            hourDiv.append(previewRow);
        }
        
        // Handle closed checkbox
        closedCheckbox.on('change', function() {
            const isClosed = $(this).is(':checked');
            startTimeInput.prop('disabled', isClosed);
            endTimeInput.prop('disabled', isClosed);
            if (isClosed) {
                startTimeInput.val('');
                endTimeInput.val('');
            }
            updateWorkingHoursText();
        });
        
        hourDiv.append(row1, row2);
        
        // Add change handlers
        if (enabled) {
            fromDaySelect.on('change', updateWorkingHoursText);
            toDaySelect.on('change', updateWorkingHoursText);
            startTimeInput.on('change', updateWorkingHoursText);
            endTimeInput.on('change', updateWorkingHoursText);
        }
        
        container.append(hourDiv);
    });
}

function collectWorkingHours() {
    const hours = [];
    $('.working-hour-item').each(function() {
        const fromDay = $(this).find('.working-hour-from-day').val();
        const toDay = $(this).find('.working-hour-to-day').val();
        const startTime = $(this).find('.working-hour-start-time').val();
        const endTime = $(this).find('.working-hour-end-time').val();
        const isClosed = $(this).find('.working-hour-closed').is(':checked');
        
        let time = '';
        if (isClosed) {
            time = 'Closed';
        } else if (startTime && endTime) {
            time = `${startTime} - ${endTime}`;
        } else if (startTime) {
            time = startTime;
        } else if (endTime) {
            time = endTime;
        }
        
        if (fromDay && toDay && time) {
            hours.push({ fromDay, toDay, time });
        }
    });
    return hours;
}

function updateWorkingHoursText() {
    const hours = collectWorkingHours();
    const text = formatWorkingHoursText(hours);
    $('#working_hours').val(text);
}

function loadWorkingHoursToUI(workingHoursText, enabled = false) {
    const hours = parseWorkingHoursText(workingHoursText);
    renderWorkingHoursList(hours, enabled);
}

// Enable/Disable form fields
function setFormFieldsEnabled(enabled) {
    const inputs = $('#locationManagementForm').find('input, textarea, select');
    inputs.prop('disabled', !enabled);
    
    // Special handling for working hours buttons
    $('#addWorkingHourBtn').prop('disabled', !enabled);
    
    // Ensure city, province, postal code, country stay disabled
    $('#city, #state, #postal_code, #country').prop('disabled', true);
    
    // Re-render working hours with enabled state
    const currentWorkingHours = $('#working_hours').val();
    loadWorkingHoursToUI(currentWorkingHours, enabled);
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
        telephone: $('#telephone').val(),
        email: $('#email').val(),
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
        $('#telephone').val(originalFormData.telephone);
        $('#email').val(originalFormData.email);
        $('#google_maps_embed').val(originalFormData.google_maps_embed);
        $('#working_hours').val(originalFormData.working_hours);
        loadWorkingHoursToUI(originalFormData.working_hours, false);
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
    
    const saveBtn = $('#saveBtn');
    saveBtn.prop('disabled', false);
    saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
    
    const deleteBtn = $('#deleteBtn');
    deleteBtn.prop('disabled', false);
    deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
    
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
                $('#city').val(loc.city || 'Santa Rosa');
                $('#state').val(loc.state || 'Laguna');
                $('#postal_code').val(loc.postal_code || '4026');
                $('#country').val(loc.country || 'Philippines');
                $('#phone').val(loc.phone || '');
                $('#email').val(loc.email || '');
                $('#google_maps_embed').val(loc.google_maps_embed || '');
                $('#working_hours').val(loc.working_hours || '');
                loadWorkingHoursToUI(loc.working_hours || '', false);
                $('#deleteBtn').show();
            } else {
                $('#deleteBtn').hide();
                $('#locationManagementForm')[0].reset();
                $('#location_id').val('');
                $('#city').val('Santa Rosa');
                $('#state').val('Laguna');
                $('#postal_code').val('4026');
                $('#country').val('Philippines');
                loadWorkingHoursToUI('', false);
            }
        },
        error: function(xhr) {
            console.log('No existing location found or error loading:', xhr);
            $('#deleteBtn').hide();
            $('#locationManagementForm')[0].reset();
            $('#location_id').val('');
            $('#city').val('Santa Rosa');
            $('#state').val('Laguna');
            $('#postal_code').val('4026');
            $('#country').val('Philippines');
            loadWorkingHoursToUI('', false);
        }
    });
}

$(document).ready(function() {
    // Add working hour button click
    $('#addWorkingHourBtn').on('click', function() {
        const currentHours = collectWorkingHours();
        currentHours.push({ fromDay: 'Monday', toDay: 'Friday', time: '' });
        renderWorkingHoursList(currentHours, true);
        updateWorkingHoursText();
    });
    
    // Edit button click handler
    $('#editBtn').on('click', function() {
        if (isProcessing) return;
        
        captureOriginalData();
        
        const saveBtn = $('#saveBtn');
        saveBtn.prop('disabled', false);
        saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
        
        const deleteBtn = $('#deleteBtn');
        deleteBtn.prop('disabled', false);
        deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
        
        setFormFieldsEnabled(true);
        
        // Re-enable add button specifically
        $('#addWorkingHourBtn').prop('disabled', false);
        
        $('#editBtn').hide();
        $('#saveBtn').show();
        $('#deleteBtn').show();
        $('#cancelBtn').show();
        clearErrors();
        showToast('Edit mode enabled. You can now edit the location details.', false);
    });
    
    // Cancel button click handler
    $('#cancelBtn').on('click', function() {
        if (isProcessing) return;
        
        restoreOriginalData();
        
        setFormFieldsEnabled(false);
        $('#editBtn').show();
        $('#saveBtn').hide();
        $('#deleteBtn').show();
        $('#cancelBtn').hide();
        clearErrors();
        
        const saveBtn = $('#saveBtn');
        saveBtn.prop('disabled', false);
        saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
        
        const deleteBtn = $('#deleteBtn');
        deleteBtn.prop('disabled', false);
        deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
        
        isProcessing = false;
        originalFormData = {};
        
        showToast('Changes discarded. Form reset to saved state.', false);
    });
    
    // Save button click handler
    $('#saveBtn').on('click', function(e) {
        e.preventDefault();
        
        if (isProcessing) {
            showToast('Please wait, processing your request...', false);
            return;
        }
        
        const locationId = $('#location_id').val();
        
        clearErrors();
        
        // Validate required fields
        const requiredFields = ['address_line1'];
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
        
        let formData = {
            address_line1: $('#address_line1').val(),
            address_line2: $('#address_line2').val(),
            city: $('#city').val(),
            state: $('#state').val(),
            postal_code: $('#postal_code').val(),
            country: $('#country').val(),
            phone: $('#phone').val(),
            telephone: $('#telephone').val(),
            email: $('#email').val(),
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
                    
                    originalFormData = {};
                    resetToViewMode(true);
                    loadLocationData();
                } else {
                    showToast(response.message || 'An error occurred', true);
                    isProcessing = false;
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
                const saveBtn = $('#saveBtn');
                saveBtn.prop('disabled', false);
                saveBtn.html('<i class="fas fa-save me-1"></i> Save Changes');
            }
        };
        
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
                        $('#city').val('Santa Rosa');
                        $('#state').val('Laguna');
                        $('#postal_code').val('4026');
                        $('#country').val('Philippines');
                        loadWorkingHoursToUI('', false);
                        
                        originalFormData = {};
                        resetToViewMode(true);
                        $('#deleteBtn').hide();
                    } else {
                        showToast(response.message || 'Error deleting location', true);
                        isProcessing = false;
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
                    deleteBtn.prop('disabled', false);
                    deleteBtn.html('<i class="fas fa-trash me-1"></i> Delete');
                }
            });
        }
    });
    
    // Initial load - set disabled fields and default values
    setFormFieldsEnabled(false);
    
    // Ensure these fields are always disabled and have default values
    $('#city, #state, #postal_code, #country').prop('disabled', true);
    
    loadLocationData();
});
</script>

<style>
/* Additional custom styles for form fields */
.form-control:disabled, 
textarea:disabled,
select:disabled {
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

.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.form-control:focus {
    border-color: #3988BD;
    box-shadow: 0 0 0 0.2rem rgba(57, 136, 189, 0.25);
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.working-hour-item {
    transition: all 0.2s ease;
}

.working-hour-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .working-hour-item .row {
        margin-bottom: 10px;
    }
    
    .btn-sm {
        margin-top: 5px;
    }
}
</style>