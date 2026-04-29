// ============================================
// Sidebar Toggle and Tab Switching
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar dropdowns
    initSidebarDropdowns();
    
    // Initialize tab switching
    initTabSwitching();
    
    // Initialize homepage image management
    initHomepageManagement();
    
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

// Function to prevent back button access after logout
function preventBackButtonAccess() {
    // Replace current state to prevent back navigation
    history.replaceState(null, null, location.href);
    
    // Listen for popstate events (back button)
    window.addEventListener('popstate', function(event) {
        // Check authentication
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
                // Force redirect to login and prevent back navigation
                window.location.replace('/admin/login');
            } else {
                // If authenticated, push state again to prevent further back navigation
                history.pushState(null, null, location.href);
            }
        })
        .catch(() => {
            window.location.replace('/admin/login');
        });
    });
}

// Initialize sidebar dropdowns
function initSidebarDropdowns() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle-main');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
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

// Initialize tab switching
function initTabSwitching() {
    const tabLinks = document.querySelectorAll('[data-tab]');
    const panels = document.querySelectorAll('.content-panel');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('data-tab');
            
            // Update active state on sidebar links
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Show selected panel
            panels.forEach(panel => {
                panel.classList.remove('active');
            });
            
            const activePanel = document.getElementById(`${tabId}-panel`);
            if (activePanel) {
                activePanel.classList.add('active');
            }
            
            // If home panel is selected, reload the image
            if (tabId === 'home') {
                loadCurrentHomepageImage();
            }
        });
    });
}

// ============================================
// Homepage Background Image Management
// ============================================

let uploadingModal = null;
let isModalVisible = false;
let currentUploadRequest = null;

/**
 * Safely hide the modal and clean up all backdrops
 */
function safelyHideModal() {
    return new Promise((resolve) => {
        if (uploadingModal && isModalVisible) {
            try {
                // First, remove any lingering backdrops manually
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                
                // Remove modal-open class and reset body styles
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Now hide the modal
                uploadingModal.hide();
                isModalVisible = false;
                
                // Small delay to ensure modal is fully hidden
                setTimeout(resolve, 100);
            } catch (error) {
                console.error('Error hiding modal:', error);
                // Force cleanup
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

/**
 * Safely show the modal
 */
function safelyShowModal() {
    return new Promise((resolve) => {
        if (uploadingModal) {
            try {
                // Ensure no lingering backdrops before showing
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
    // Initialize modal
    const modalElement = document.getElementById('uploadingModal');
    if (modalElement) {
        uploadingModal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
        
        // Clean up modal when hidden
        modalElement.addEventListener('hidden.bs.modal', function() {
            isModalVisible = false;
            // Force remove any leftover backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
    
    // Load current image
    loadCurrentHomepageImage();
    
    // Set up event listeners
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

// Helper function to validate image file
function validateImageFile(file) {
    // Check if file exists
    if (!file) {
        return 'Please select an image file first.';
    }
    
    // Check file size (5MB = 5 * 1024 * 1024 bytes)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        return 'File exceeds the 5MB size limit. Please choose a smaller file.';
    }
    
    // Validate file type with proper MIME type checking
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!allowedTypes.includes(file.type) || !allowedExtensions.includes(fileExtension)) {
        return 'Invalid file type. Please upload JPG, PNG, GIF, or WEBP images only.';
    }
    
    return null;
}

function setupImageUploadListeners() {
    // File input preview
    const backgroundImageInput = document.getElementById('backgroundImage');
    if (backgroundImageInput) {
        backgroundImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const newPreviewContainer = document.getElementById('newImagePreviewContainer');
            const newPreviewImg = document.getElementById('newPreviewImg');
            
            if (file) {
                // Validate file
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
    
    // Form submission
    const homepageImageForm = document.getElementById('homepageImageForm');
    if (homepageImageForm) {
        homepageImageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Cancel any ongoing request
            if (currentUploadRequest) {
                currentUploadRequest.abort();
            }
            
            const fileInput = document.getElementById('backgroundImage');
            const file = fileInput.files[0];
            
            // Validate file
            const validationError = validateImageFile(file);
            if (validationError) {
                showCustomToast(validationError, 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('background_image', file);
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
            // Disable upload and remove buttons
            const uploadBtn = document.getElementById('uploadBtn');
            const removeBtn = document.getElementById('removeImageBtn');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
            if (removeBtn) removeBtn.disabled = true;
            
            // Show modal with proper cleanup
            await safelyShowModal();
            
            // Set timeout to auto-hide modal after 30 seconds if stuck
            let autoHideTimeout = setTimeout(async () => {
                if (isModalVisible) {
                    console.warn('Modal auto-hide triggered');
                    await safelyHideModal();
                }
            }, 30000);
            
            // Create AbortController for timeout
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
                
                // Hide modal first
                await safelyHideModal();
                
                // Show toast message after modal is hidden
                if (data.success) {
                    showCustomToast(data.message, 'success');
                    // Clear file input and preview
                    fileInput.value = '';
                    const newPreviewContainer = document.getElementById('newImagePreviewContainer');
                    const newPreviewImg = document.getElementById('newPreviewImg');
                    if (newPreviewContainer) newPreviewContainer.style.display = 'none';
                    if (newPreviewImg) newPreviewImg.src = '';
                    // Reload current image
                    await loadCurrentHomepageImage();
                } else {
                    showCustomToast(data.message, 'error');
                }
            } catch (error) {
                clearTimeout(requestTimeoutId);
                clearTimeout(autoHideTimeout);
                
                // Hide modal
                await safelyHideModal();
                
                if (error.name === 'AbortError') {
                    showCustomToast('Request timeout. Please try again with a smaller file.', 'error');
                } else {
                    console.error('Error:', error);
                    showCustomToast('Network error. Please check your connection and try again.', 'error');
                }
            } finally {
                // Re-enable buttons
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Upload/Update Background Image';
                if (removeBtn) removeBtn.disabled = false;
                currentUploadRequest = null;
            }
        });
    }
    
    // Remove new image button
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
    
    // Remove image button
    const removeImageBtn = document.getElementById('removeImageBtn');
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', async function() {
            const previewImg = document.getElementById('previewImg');
            const hasActiveImage = previewImg && previewImg.style.display === 'inline-block' && previewImg.src && previewImg.src !== '';
            
            if (!hasActiveImage) {
                showCustomToast('No background image to remove.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to remove the background image? The default picture will be shown instead.')) {
                // Cancel any ongoing request
                if (currentUploadRequest) {
                    currentUploadRequest.abort();
                }
                
                // Disable buttons
                removeImageBtn.disabled = true;
                const uploadBtn = document.getElementById('uploadBtn');
                if (uploadBtn) uploadBtn.disabled = true;
                removeImageBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Removing...';
                
                // Show modal
                await safelyShowModal();
                
                // Set timeout for auto-hide
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
                    
                    // Hide modal
                    await safelyHideModal();
                    
                    if (data.success) {
                        showCustomToast(data.message, 'success');
                        // Reset preview
                        if (previewImg) {
                            previewImg.style.display = 'none';
                            previewImg.src = '';
                        }
                        const noImagePlaceholder = document.getElementById('noImagePlaceholder');
                        if (noImagePlaceholder) noImagePlaceholder.style.display = 'block';
                        
                        // Clear file input
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
                    
                    // Hide modal
                    await safelyHideModal();
                    
                    if (error.name === 'AbortError') {
                        showCustomToast('Request timeout. Please try again.', 'error');
                    } else {
                        console.error('Error:', error);
                        showCustomToast('Network error. Please check your connection and try again.', 'error');
                    }
                } finally {
                    // Re-enable buttons
                    removeImageBtn.disabled = false;
                    removeImageBtn.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Remove Background Image';
                    if (uploadBtn) uploadBtn.disabled = false;
                    currentUploadRequest = null;
                }
            }
        });
    }
}

// Custom toast function
function showCustomToast(message, type = 'success') {
    // Remove existing toast
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
    
    // Trigger reflow to ensure animation plays
    toast.offsetHeight;
    
    // Show toast
    toast.classList.add('show');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 300);
    }, 5000);
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Expose functions globally
window.switchTab = function(tabId) {
    const panels = document.querySelectorAll('.content-panel');
    panels.forEach(panel => {
        panel.classList.remove('active');
    });
    
    const activePanel = document.getElementById(`${tabId}-panel`);
    if (activePanel) {
        activePanel.classList.add('active');
    }
    
    // Update sidebar links
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-tab') === tabId) {
            link.classList.add('active');
        }
    });
    
    // If home panel is selected, reload the image
    if (tabId === 'home') {
        loadCurrentHomepageImage();
    }
};