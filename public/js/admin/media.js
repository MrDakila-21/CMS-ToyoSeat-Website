/**
 * Media Management Module
 * Handles all event/activity management functionality
 */

(function() {
    'use strict';
    
    // Store modal instances
    let editModal = null;
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMediaManagement);
    } else {
        initMediaManagement();
    }
    
    function initMediaManagement() {
        console.log('Initializing media management...');
        
        // Initialize modal instances
        const editModalElement = document.getElementById('mediaEditModal');
        if (editModalElement && typeof bootstrap !== 'undefined') {
            editModal = new bootstrap.Modal(editModalElement);
        }
        
        // Attach all handlers
        attachEventHandlers();
    }
    
    function attachEventHandlers() {
        // Use event delegation for dynamic elements
        const container = document.querySelector('.card.content-card');
        
        if (!container) {
            console.warn('Container not found, retrying in 500ms...');
            setTimeout(attachEventHandlers, 500);
            return;
        }
        
        // Status change handler (direct binding works because selects are static)
        document.querySelectorAll('.status-select').forEach(select => {
            select.removeEventListener('change', handleStatusChange);
            select.addEventListener('change', handleStatusChange);
        });
        
        // Use event delegation for edit and delete buttons
        container.removeEventListener('click', handleContainerClick);
        container.addEventListener('click', handleContainerClick);
        
        // Form handlers
        attachFormHandlers();
        
        console.log('Event handlers attached successfully');
    }
    
    function handleContainerClick(e) {
        // Handle edit button clicks
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            e.preventDefault();
            const id = editBtn.dataset.id;
            if (id) {
                console.log('Edit button clicked for ID:', id);
                handleEditClick(id);
            }
        }
        
        // Handle delete button clicks
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            e.preventDefault();
            const id = deleteBtn.dataset.id;
            if (id) {
                console.log('Delete button clicked for ID:', id);
                handleDeleteClick(id);
            }
        }
    }
    
    // Status change handler
    async function handleStatusChange(e) {
        const select = e.target;
        const id = select.dataset.id;
        const status = select.value;
        
        if (!id) return;
        
        // Show loading state
        select.disabled = true;
        
        try {
            const response = await fetch(`/admin/media/${id}/status/${status}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showCustomToast(data.message, 'success');
            } else {
                showCustomToast(data.message || 'Failed to update status', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error updating status', 'error');
        } finally {
            select.disabled = false;
        }
    }
    
    // Edit handler
    async function handleEditClick(id) {
        console.log('Editing item with ID:', id);
        
        // Show loading state in modal
        const modalBody = document.getElementById('mediaEditModalBody');
        if (modalBody) {
            modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3">Loading data...</p></div>';
        }
        
        // Show modal immediately with loading state
        if (editModal) {
            editModal.show();
        } else {
            console.error('Edit modal not initialized');
            showCustomToast('Error initializing edit form', 'error');
            return;
        }
        
        try {
            const response = await fetch(`/admin/media/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Received data:', data);
            
            if (data && data.id) {
                populateEditModal(data);
            } else {
                throw new Error('Invalid data received');
            }
        } catch (error) {
            console.error('Error loading edit data:', error);
            showCustomToast('Failed to load data: ' + error.message, 'error');
            if (editModal) {
                editModal.hide();
            }
        }
    }
    
function populateEditModal(data) {
    const modalBody = document.getElementById('mediaEditModalBody');
    if (!modalBody) return;
    
    // Format date for input field (YYYY-MM-DD)
    let formattedDate = '';
    if (data.event_date) {
        const date = new Date(data.event_date);
        if (!isNaN(date.getTime())) {
            formattedDate = date.toISOString().split('T')[0];
        }
    }
    
    console.log('Populating edit modal with data:', data);
    console.log('Formatted date:', formattedDate);
    
    modalBody.innerHTML = `
        <div class="mb-3">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="${escapeHtml(data.title || '')}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" class="form-select" required>
                <option value="event" ${data.type === 'event' ? 'selected' : ''}>Event</option>
                <option value="activity" ${data.type === 'activity' ? 'selected' : ''}>Activity</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="5" required>${escapeHtml(data.description || '')}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="event_date" class="form-control" value="${escapeHtml(formattedDate)}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label>
            ${data.image_url ? `<img src="${escapeHtml(data.image_url)}" style="max-width: 200px; display: block; margin-bottom: 10px; border-radius: 4px;">` : '<p class="text-muted">No image uploaded</p>'}
            <label class="form-label mt-2">Change Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="form-text text-muted">Max size: 2MB. Leave empty to keep current image.</small>
        </div>
    `;
    
    // Update form action and ensure method is set correctly
    const form = document.getElementById('mediaEditForm');
    if (form) {
        // Clear any existing method inputs
        const existingMethodInputs = form.querySelectorAll('input[name="_method"]');
        existingMethodInputs.forEach(input => input.remove());
        
        // Set the form action
        form.action = `/admin/media/${data.id}`;
        
        // Add the PUT method input
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        
        // Also ensure the form has enctype for file uploads
        form.enctype = 'multipart/form-data';
        
        console.log('Form setup complete:');
        console.log('- Action:', form.action);
        console.log('- Method input:', methodInput.value);
        console.log('- Enctype:', form.enctype);
    }
}
    
    // Delete handler
// Delete handler
async function handleDeleteClick(id) {
    console.log('Deleting item with ID:', id);
    
    // Use custom confirmation dialog
    if (!confirm('⚠️ Are you sure you want to delete this item?\n\nThis action cannot be undone!')) {
        return;
    }
    
    // Show loading indication on the button
    const deleteBtn = document.querySelector(`.delete-btn[data-id="${id}"]`);
    let originalText = '';
    
    if (deleteBtn) {
        originalText = deleteBtn.innerHTML;
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
    }
    
    try {
        const response = await fetch(`/admin/media/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show RED toast for deletion (using error type)
            showCustomToast(data.message || 'Item deleted successfully', 'error');
            
            // Remove the row from table with animation
            const row = deleteBtn ? deleteBtn.closest('tr') : document.querySelector(`button.delete-btn[data-id="${id}"]`).closest('tr');
            if (row) {
                row.style.opacity = '0.5';
                row.style.transition = 'opacity 0.3s';
                setTimeout(() => {
                    row.remove();
                    // Check if table is empty and show message
                    const tbody = document.querySelector('.table tbody');
                    if (tbody && tbody.children.length === 0) {
                        location.reload(); // Reload to show empty state message
                    }
                }, 300);
            } else {
                // Reload the page if we can't find the row
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showCustomToast(data.message || 'Failed to delete item', 'error');
            if (deleteBtn) {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = originalText;
            }
        }
    } catch (error) {
        console.error('Error deleting item:', error);
        showCustomToast('Network error deleting item. Please try again.', 'error');
        if (deleteBtn) {
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalText;
        }
    }
}
    
    // Form handlers
    function attachFormHandlers() {
        const addForm = document.getElementById('mediaAddForm');
        if (addForm) {
            // Remove existing listener to avoid duplicates
            addForm.removeEventListener('submit', handleFormSubmit);
            addForm.addEventListener('submit', handleFormSubmit);
        }
        
        const editForm = document.getElementById('mediaEditForm');
        if (editForm) {
            editForm.removeEventListener('submit', handleFormSubmit);
            editForm.addEventListener('submit', handleFormSubmit);
        }
    }
    
async function handleFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    
    console.log('Form submission started');
    console.log('Form action:', form.action);
    
    // Show loading state on submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
    }
    
    try {
        let url = form.action;
        let method = form.method || 'POST';
        
        // Check if this is an update (PUT request)
        const methodInput = form.querySelector('input[name="_method"]');
        let isUpdate = false;
        
        if (methodInput && methodInput.value === 'PUT') {
            isUpdate = true;
            method = 'POST'; // Laravel expects POST with _method=PUT
        }
        
        // Create FormData from the form
        const formData = new FormData(form);
        
        // Debug: Log all form data being sent
        console.log('Form data being sent:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // For updates, ensure we have the _method field
        if (isUpdate && !formData.has('_method')) {
            formData.append('_method', 'PUT');
            console.log('Added _method=PUT to form data');
        }
        
        // Send the request
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        console.log('Server response:', data);
        
        if (response.ok && data.success) {
            showCustomToast(data.message || 'Saved successfully', 'success');
            
            // Close modal
            const modal = form.closest('.modal');
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
            
            // Reload the content
            setTimeout(() => {
                if (typeof window.loadContent === 'function') {
                    const urlParams = new URLSearchParams(window.location.search);
                    const tab = urlParams.get('tab') || 'news';
                    const subtab = urlParams.get('subtab') || 'media';
                    window.loadContent(tab, subtab);
                } else {
                    location.reload();
                }
            }, 500);
        } else {
            // Display error message
            let errorMessage = data.message || 'Failed to save data';
            if (data.errors) {
                const errorList = Object.values(data.errors).flat();
                errorMessage = errorList.join('\n');
                console.error('Validation errors:', data.errors);
            }
            showCustomToast(errorMessage, 'error');
            
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }
    } catch (error) {
        console.error('Error saving data:', error);
        showCustomToast('Network error saving data. Please try again.', 'error');
        
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    }
}
    
    // Utility functions
    function getCsrfToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (!metaTag) {
            console.warn('CSRF token meta tag not found');
            return '';
        }
        return metaTag.content;
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = String(text);
        return div.innerHTML;
    }
    
function showCustomToast(message, type = 'success') {
    // Remove existing toast
    const existingToast = document.querySelector('.login-toast');
    if (existingToast) existingToast.remove();
    
    // Set icon based on type
    let icon = 'fa-circle-check';
    if (type === 'error') {
        icon = 'fa-circle-exclamation';
    } else if (type === 'warning') {
        icon = 'fa-triangle-exclamation';
    } else if (type === 'info') {
        icon = 'fa-info-circle';
    }
    
    // Create toast container
    const toast = document.createElement('div');
    toast.className = `login-toast ${type === 'success' ? 'success-toast' : 'error-toast'}`;
    
    // Create toast content with compact styling
    toast.innerHTML = `
        <div class="login-toast-content" style="padding: 10px 16px; gap: 10px;">
            <i class="fas ${icon}" style="font-size: 18px;"></i>
            <span style="font-size: 13px; line-height: 1.4; flex: 1;">${escapeHtml(message)}</span>
            <button type="button" class="toast-close" style="background: none; border: none; cursor: pointer; font-size: 16px; padding: 0; margin-left: 8px; opacity: 0.6;">&times;</button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Add close button functionality
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.onclick = function() {
        toast.classList.add('hide');
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 300);
    };
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }
    }, 3000);
}
    
    // Expose functions globally for dynamic content
    window.initMediaManagement = initMediaManagement;
    window.refreshMediaHandlers = function() {
        attachEventHandlers();
    };
    
    // Log successful initialization
    console.log('Media.js initialized successfully');
})();

window.initMediaManagement = initMediaManagement;