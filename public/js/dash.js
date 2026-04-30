// ============================================
// Sidebar Dropdowns
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar dropdowns
    initSidebarDropdowns();
    
    // Auto-hide success toast message
    const successToast = document.getElementById('dashboard-success-toast');
    if (successToast) {
        setTimeout(() => {
            successToast.classList.add('hide');
            setTimeout(() => successToast.remove(), 600);
        }, 4000);
    }
    
    // Prevent back button from accessing protected pages
    preventBackButtonAccess();
});

// Initialize sidebar dropdowns
function initSidebarDropdowns() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle-main');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdownId = this.getAttribute('data-dropdown');
            const dropdown = document.getElementById(dropdownId);
            const chevron = this.querySelector('.chevron-icon');
            
            if (dropdown) {
                dropdown.classList.toggle('open');
                if (chevron) {
                    chevron.style.transform = dropdown.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0)';
                }
            }
        });
    });
}

// Function to prevent back button access after logout
function preventBackButtonAccess() {
    history.replaceState(null, null, location.href);
    
    window.addEventListener('popstate', function(event) {
        fetch('/admin/check-auth', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            },
            cache: 'no-store'
        })
        .then(response => response.json())
        .then(data => {
            if (!data.authenticated) {
                window.location.replace('/admin/login');
            } else {
                history.pushState(null, null, location.href);
            }
        })
        .catch(() => {
            window.location.replace('/admin/login');
        });
    });
}

// ============================================
// Homepage Background Image Management
// ============================================

let uploadingModal = null;
let isModalVisible = false;
let currentUploadRequest = null;

function safelyHideModal() {
    return new Promise((resolve) => {
        if (uploadingModal && isModalVisible) {
            try {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                uploadingModal.hide();
                isModalVisible = false;
                setTimeout(resolve, 100);
            } catch (error) {
                console.error('Error hiding modal:', error);
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                isModalVisible = false;
                resolve();
            }
        } else {
            resolve();
        }
    });
}

function safelyShowModal() {
    return new Promise((resolve) => {
        if (uploadingModal) {
            try {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                uploadingModal.show();
                isModalVisible = true;
                setTimeout(resolve, 100);
            } catch (error) {
                console.error('Error showing modal:', error);
                resolve();
            }
        } else {
            resolve();
        }
    });
}

function initHomepageManagement() {
    const modalElement = document.getElementById('uploadingModal');
    if (modalElement) {
        uploadingModal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
        
        modalElement.addEventListener('hidden.bs.modal', function() {
            isModalVisible = false;
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
    
    loadCurrentHomepageImage();
    setupImageUploadListeners();
}

function loadCurrentHomepageImage() {
    fetch('/admin/homepage/image', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.has_image) {
            const previewImg = document.getElementById('previewImg');
            const noImagePlaceholder = document.getElementById('noImagePlaceholder');
            
            if (previewImg && noImagePlaceholder) {
                previewImg.src = 'data:image/png;base64,' + data.image_data;
                previewImg.style.display = 'inline-block';
                noImagePlaceholder.style.display = 'none';
            }
        } else {
            const previewImg = document.getElementById('previewImg');
            const noImagePlaceholder = document.getElementById('noImagePlaceholder');
            
            if (previewImg && noImagePlaceholder) {
                previewImg.style.display = 'none';
                previewImg.src = '';
                noImagePlaceholder.style.display = 'block';
            }
        }
    })
    .catch(error => {
        console.error('Error loading homepage image:', error);
        showCustomToast('Failed to load current image', 'error');
    });
}

function validateImageFile(file) {
    if (!file) {
        return 'Please select an image file first.';
    }
    
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        return 'File exceeds the 5MB size limit. Please choose a smaller file.';
    }
    
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!allowedTypes.includes(file.type) || !allowedExtensions.includes(fileExtension)) {
        return 'Invalid file type. Please upload JPG, PNG, GIF, or WEBP images only.';
    }
    
    return null;
}

function setupImageUploadListeners() {
    const backgroundImageInput = document.getElementById('backgroundImage');
    if (backgroundImageInput) {
        backgroundImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const newPreviewContainer = document.getElementById('newImagePreviewContainer');
            const newPreviewImg = document.getElementById('newPreviewImg');
            
            if (file) {
                const validationError = validateImageFile(file);
                if (validationError) {
                    showCustomToast(validationError, 'error');
                    this.value = '';
                    if (newPreviewContainer) newPreviewContainer.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (newPreviewImg) newPreviewImg.src = event.target.result;
                    if (newPreviewContainer) newPreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                if (newPreviewContainer) newPreviewContainer.style.display = 'none';
                if (newPreviewImg) newPreviewImg.src = '';
            }
        });
    }
    
    const homepageImageForm = document.getElementById('homepageImageForm');
    if (homepageImageForm) {
        homepageImageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (currentUploadRequest) {
                currentUploadRequest.abort();
            }
            
            const fileInput = document.getElementById('backgroundImage');
            const file = fileInput.files[0];
            
            const validationError = validateImageFile(file);
            if (validationError) {
                showCustomToast(validationError, 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('background_image', file);
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
            const uploadBtn = document.getElementById('uploadBtn');
            const removeBtn = document.getElementById('removeImageBtn');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
            if (removeBtn) removeBtn.disabled = true;
            
            await safelyShowModal();
            
            let autoHideTimeout = setTimeout(async () => {
                if (isModalVisible) {
                    console.warn('Modal auto-hide triggered');
                    await safelyHideModal();
                }
            }, 30000);
            
            const controller = new AbortController();
            currentUploadRequest = controller;
            const requestTimeoutId = setTimeout(() => controller.abort(), 30000);
            
            try {
                const response = await fetch('/admin/homepage/upload-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    signal: controller.signal
                });
                
                clearTimeout(requestTimeoutId);
                clearTimeout(autoHideTimeout);
                
                const data = await response.json();
                
                await safelyHideModal();
                
                if (data.success) {
                    showCustomToast(data.message, 'success');
                    fileInput.value = '';
                    const newPreviewContainer = document.getElementById('newImagePreviewContainer');
                    const newPreviewImg = document.getElementById('newPreviewImg');
                    if (newPreviewContainer) newPreviewContainer.style.display = 'none';
                    if (newPreviewImg) newPreviewImg.src = '';
                    await loadCurrentHomepageImage();
                } else {
                    showCustomToast(data.message, 'error');
                }
            } catch (error) {
                clearTimeout(requestTimeoutId);
                clearTimeout(autoHideTimeout);
                
                await safelyHideModal();
                
                if (error.name === 'AbortError') {
                    showCustomToast('Request timeout. Please try again with a smaller file.', 'error');
                } else {
                    console.error('Error:', error);
                    showCustomToast('Network error. Please check your connection and try again.', 'error');
                }
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Upload/Update Background Image';
                if (removeBtn) removeBtn.disabled = false;
                currentUploadRequest = null;
            }
        });
    }
    
    const removeNewImageBtn = document.getElementById('removeNewImageBtn');
    if (removeNewImageBtn) {
        removeNewImageBtn.addEventListener('click', function() {
            const fileInput = document.getElementById('backgroundImage');
            if (fileInput) fileInput.value = '';
            
            const newPreviewContainer = document.getElementById('newImagePreviewContainer');
            const newPreviewImg = document.getElementById('newPreviewImg');
            
            if (newPreviewContainer) newPreviewContainer.style.display = 'none';
            if (newPreviewImg) newPreviewImg.src = '';
            
            showCustomToast('New image selection removed.', 'info');
        });
    }
    
    const removeImageBtn = document.getElementById('removeImageBtn');
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', async function() {
            const previewImg = document.getElementById('previewImg');
            const hasActiveImage = previewImg && previewImg.style.display === 'inline-block' && previewImg.src && previewImg.src !== '';
            
            if (!hasActiveImage) {
                showCustomToast('No background image to remove.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to remove the background image?')) {
                if (currentUploadRequest) {
                    currentUploadRequest.abort();
                }
                
                removeImageBtn.disabled = true;
                const uploadBtn = document.getElementById('uploadBtn');
                if (uploadBtn) uploadBtn.disabled = true;
                removeImageBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Removing...';
                
                await safelyShowModal();
                
                let autoHideTimeout = setTimeout(async () => {
                    if (isModalVisible) {
                        await safelyHideModal();
                    }
                }, 30000);
                
                const controller = new AbortController();
                currentUploadRequest = controller;
                const timeoutId = setTimeout(() => controller.abort(), 30000);
                
                try {
                    const response = await fetch('/admin/homepage/remove-image', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        signal: controller.signal
                    });
                    
                    clearTimeout(timeoutId);
                    clearTimeout(autoHideTimeout);
                    
                    const data = await response.json();
                    
                    await safelyHideModal();
                    
                    if (data.success) {
                        showCustomToast(data.message, 'success');
                        if (previewImg) {
                            previewImg.style.display = 'none';
                            previewImg.src = '';
                        }
                        const noImagePlaceholder = document.getElementById('noImagePlaceholder');
                        if (noImagePlaceholder) noImagePlaceholder.style.display = 'block';
                        
                        const fileInput = document.getElementById('backgroundImage');
                        if (fileInput) fileInput.value = '';
                        
                        const newPreviewContainer = document.getElementById('newImagePreviewContainer');
                        if (newPreviewContainer) newPreviewContainer.style.display = 'none';
                    } else {
                        showCustomToast(data.message, 'error');
                    }
                } catch (error) {
                    clearTimeout(timeoutId);
                    clearTimeout(autoHideTimeout);
                    
                    await safelyHideModal();
                    
                    if (error.name === 'AbortError') {
                        showCustomToast('Request timeout. Please try again.', 'error');
                    } else {
                        console.error('Error:', error);
                        showCustomToast('Network error. Please check your connection and try again.', 'error');
                    }
                } finally {
                    removeImageBtn.disabled = false;
                    removeImageBtn.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Remove Background Image';
                    if (uploadBtn) uploadBtn.disabled = false;
                    currentUploadRequest = null;
                }
            }
        });
    }
}

function showCustomToast(message, type = 'success') {
    const existingToast = document.querySelector('.login-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `login-toast ${type === 'success' ? 'success-toast' : (type === 'error' ? 'error-toast' : 'info-toast')}`;
    toast.innerHTML = `
        <div class="login-toast-content">
            <i class="fas ${type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-circle-exclamation' : 'fa-info-circle')}"></i>
            <span>${escapeHtml(message)}</span>
        </div>
    `;
    document.body.appendChild(toast);
    
    toast.offsetHeight;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 300);
    }, 5000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// Media (Events & Activities) Management
// ============================================

function initMediaManagement() {
    if (window.__mediaMgmtDelegated) return;
    window.__mediaMgmtDelegated = true;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    async function fetchJson(url, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                ...(options.headers || {})
            }
        });

        let data = null;
        try {
            data = await response.json();
        } catch {
            // Non-JSON response
        }

        if (!response.ok) {
            const message = data?.message || data?.error || 'Request failed';
            throw new Error(message);
        }

        return data;
    }

    function getModalInstance(modalId) {
        const el = document.getElementById(modalId);
        if (!el || typeof bootstrap === 'undefined') return null;
        return bootstrap.Modal.getOrCreateInstance(el);
    }

    document.addEventListener('change', async (e) => {
        const target = e.target;
        if (!(target instanceof Element)) return;
        if (!target.classList.contains('status-select')) return;

        const id = target.getAttribute('data-id');
        const status = target.value;
        if (!id || !status) return;

        try {
            const data = await fetchJson(`/admin/media/${id}/status/${status}`, {
                method: 'PATCH'
            });
            showCustomToast(data?.message || 'Status updated successfully', 'success');
        } catch (error) {
            console.error(error);
            showCustomToast(error?.message || 'Error updating status', 'error');
        }
    });

    document.addEventListener('click', async (e) => {
        const target = e.target;
        if (!(target instanceof Element)) return;

        const editBtn = target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.getAttribute('data-id');
            if (!id) return;

            try {
                const data = await fetchJson(`/admin/media/${id}/edit`, { method: 'GET' });

                const editBody = document.getElementById('mediaEditModalBody');
                const editForm = document.getElementById('mediaEditForm');
                if (!editBody || !editForm) return;

                const currentImageHtml = data.image
                    ? `<img src="/storage/${escapeHtml(data.image)}" style="width: 110px; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;" alt="Current image">`
                    : `<span class="badge text-bg-secondary">No image</span>`;

                editBody.innerHTML = `
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
                        <input type="date" name="event_date" class="form-control" value="${escapeHtml(data.event_date || '')}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Image</label><br>
                        ${currentImageHtml}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Replace Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text">Leave empty to keep current image</div>
                    </div>
                `;

                editForm.setAttribute('action', `/admin/media/${id}`);
                getModalInstance('mediaEditModal')?.show();
            } catch (error) {
                console.error(error);
                showCustomToast(error?.message || 'Failed to load record', 'error');
            }

            return;
        }

        const deleteBtn = target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.getAttribute('data-id');
            if (!id) return;
            if (!confirm('Are you sure you want to delete this item?')) return;

            try {
                const data = await fetchJson(`/admin/media/${id}`, { method: 'DELETE' });
                showCustomToast(data?.message || 'Item deleted successfully', 'success');
                if (typeof window.loadContent === 'function') {
                    window.loadContent('news', 'media');
                }
            } catch (error) {
                console.error(error);
                showCustomToast(error?.message || 'Error deleting item', 'error');
            }
        }
    });

    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;

        if (form.id !== 'mediaAddForm' && form.id !== 'mediaEditForm') return;
        e.preventDefault();

        const action = form.getAttribute('action');
        if (!action) return;

        const formData = new FormData(form);

        try {
            const data = await fetchJson(action, {
                method: 'POST',
                body: formData
            });

            showCustomToast(data?.message || 'Saved successfully', 'success');

            if (form.id === 'mediaAddForm') {
                getModalInstance('mediaAddModal')?.hide();
                form.reset();
            } else {
                getModalInstance('mediaEditModal')?.hide();
            }

            if (typeof window.loadContent === 'function') {
                window.loadContent('news', 'media');
            }
        } catch (error) {
            console.error(error);
            showCustomToast(error?.message || 'Failed to save changes', 'error');
        }
    }, true);
}

// Expose functions globally
window.initHomepageManagement = initHomepageManagement;
window.initMediaManagement = initMediaManagement;