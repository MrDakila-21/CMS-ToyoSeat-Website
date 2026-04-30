// ============================================
// resources/js/dash.js (or public/js/dash.js)
// ============================================

// ============================================
// Sidebar Dropdowns and Main Navigation
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard initialized');
    
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
    
    // Load initial content (home tab)
    loadContent('home');
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

// Function to load content via AJAX
function loadContent(mainTab, subTab = null) {
    const contentContainer = document.getElementById('dynamic-content');
    
    if (!contentContainer) {
        console.error('Content container not found');
        return;
    }
    
    // Show loading indicator
    contentContainer.innerHTML = `
        <div class="content-loading">
            <div class="spinner"></div>
            <p>Loading content...</p>
        </div>
    `;
    
    // Build URL with parameters
    let url = `/admin/load-content?tab=${mainTab}`;
    if (subTab) {
        url += `&subtab=${subTab}`;
    }
    
    // Fetch content
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            contentContainer.innerHTML = data.html;
            
            // Re-initialize any tab-specific JavaScript
            if (mainTab === 'home') {
                // Small delay to ensure DOM is fully updated
                setTimeout(() => {
                    if (typeof initHomepageManagement !== 'undefined') {
                        console.log('Calling initHomepageManagement');
                        initHomepageManagement();
                    } else {
                        console.log('initHomepageManagement not defined yet, checking for scripts in loaded content...');
                        // Try to find and execute the script in the loaded content
                        const scripts = contentContainer.querySelectorAll('script');
                        scripts.forEach(script => {
                            if (script.textContent.includes('initHomepageManagement')) {
                                eval(script.textContent);
                                if (typeof initHomepageManagement === 'function') {
                                    initHomepageManagement();
                                }
                            }
                        });
                    }
                }, 100);
            }
        } else if (data.error) {
            contentContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${escapeHtml(data.error)}
                </div>
            `;
        } else {
            contentContainer.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No content available.
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading content:', error);
        contentContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Failed to load content. Please try again.
            </div>
        `;
    });
}

// Tab switching logic
document.querySelectorAll('[data-tab]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const mainTab = this.getAttribute('data-tab');
        const subTab = this.getAttribute('data-subtab');
        
        // Update active state on sidebar links
        document.querySelectorAll('.sidebar-link, .sidebar-dropdown a').forEach(l => {
            l.classList.remove('active');
        });
        this.classList.add('active');
        
        // Load content
        loadContent(mainTab, subTab);
    });
});

// Prevent back button access after logout
(function() {
    // Check authentication status periodically
    function checkAuthStatus() {
        fetch('/admin/check-auth', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.authenticated) {
                window.location.href = '/admin/login';
            }
        })
        .catch(error => {
            console.error('Auth check failed:', error);
        });
    }
    
    setInterval(checkAuthStatus, 5000);
    
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            checkAuthStatus();
        }
    });
})();

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// Homepage Slideshow Management Functions
// These are exposed globally and will be used when home tab is loaded
// ============================================

// Make sure SortableJS is loaded
function loadSortableScript() {
    return new Promise((resolve, reject) => {
        if (typeof Sortable !== 'undefined') {
            console.log('SortableJS already loaded');
            resolve();
            return;
        }
        
        console.log('Loading SortableJS...');
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js';
        script.onload = () => {
            console.log('SortableJS loaded successfully');
            resolve();
        };
        script.onerror = () => {
            console.error('Failed to load SortableJS');
            reject(new Error('Failed to load SortableJS'));
        };
        document.head.appendChild(script);
    });
}

let sortableInstance = null;
let allSlides = [];

// Initialize homepage management
window.initHomepageManagement = function() {
    console.log('Initializing homepage management...');
    loadSortableScript()
        .then(() => {
            console.log('SortableJS ready, loading slides...');
            loadSlides();
            setupMultipleUploadListener();
            setupPresentListener();
        })
        .catch(error => {
            console.error('Error loading SortableJS:', error);
            // Still load slides even if Sortable fails
            loadSlides();
            setupMultipleUploadListener();
            setupPresentListener();
        });
};

// Load all slides
function loadSlides() {
    console.log('Loading slides...');
    const container = document.getElementById('slidesContainer');
    if (!container) {
        console.error('Slides container not found');
        return;
    }
    
    container.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading slides...</p>
        </div>
    `;
    
    fetch('/admin/homepage/slides', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Slides loaded:', data);
        if (data.success) {
            allSlides = data.slides || [];
            renderSlides(allSlides);
        } else {
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${escapeHtml(data.message || 'Failed to load slides')}
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading slides:', error);
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Network error loading slides. Please refresh the page.
                </div>
            </div>
        `;
    });
}

// Replace the renderSlides function in dash.js with this:

function renderSlides(slides) {
    const container = document.getElementById('slidesContainer');
    
    if (!container) return;
    
    if (!slides || slides.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    No images uploaded yet. Use the upload form above to add images.
                </div>
            </div>
        `;
        return;
    }
    
    let html = '';
    slides.forEach((slide, index) => {
        html += `
            <div class="col-md-3 col-sm-6 mb-3 slide-item" data-id="${slide.id}" data-order="${slide.order}">
                <div class="card h-100">
                    <div class="position-relative">
                        <img src="${slide.image_url}" class="card-img-top" alt="Slide ${index + 1}" style="height: 150px; object-fit: cover;">
                        <div class="position-absolute top-0 inset-start-0 m-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input slide-checkbox" data-id="${slide.id}" style="width: 20px; height: 20px;" ${slide.is_active ? 'checked' : ''}>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 inset-end-0 m-2" onclick="window.deleteSlide(${slide.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-2 text-center">
                        <small class="text-muted">Order: ${slide.order}</small>
                        <div class="drag-handle mt-1" style="cursor: move;">
                            <i class="fas fa-grip-vertical"></i> Drag to reorder
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    
    // Initialize drag and drop
    if (typeof Sortable !== 'undefined') {
        if (sortableInstance) {
            sortableInstance.destroy();
        }
        
        const sortableContainer = document.getElementById('slidesContainer');
        if (sortableContainer) {
            sortableInstance = new Sortable(sortableContainer, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    updateOrderFromDrag();
                }
            });
            console.log('Sortable initialized');
        }
    } else {
        console.warn('SortableJS not available - drag and drop disabled');
    }
}

function updateOrderFromDrag() {
    const items = document.querySelectorAll('.slide-item');
    const updatedOrders = [];
    
    items.forEach((item, index) => {
        const slideId = parseInt(item.dataset.id);
        updatedOrders.push({
            id: slideId,
            order: index
        });
    });
    
    console.log('Updating orders:', updatedOrders);
    
    // Update orders in database
    fetch('/admin/homepage/update-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ slides: updatedOrders })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showCustomToast('Order updated successfully!', 'success');
            loadSlides(); // Reload to refresh order display
        } else {
            window.showCustomToast(data.message || 'Failed to update order', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating order:', error);
        window.showCustomToast('Network error updating order', 'error');
    });
}

// Setup multiple image upload listener
function setupMultipleUploadListener() {
    const form = document.getElementById('multipleImagesForm');
    if (!form) {
        console.error('Multiple images form not found');
        return;
    }
    
    console.log('Setting up upload listener');
    
    const fileInput = document.getElementById('multipleImages');
    const previewContainer = document.getElementById('uploadPreviewContainer');
    
    // Preview selected images
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length === 0) {
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    previewContainer.innerHTML = '';
                }
                return;
            }
            
            if (previewContainer) {
                previewContainer.innerHTML = '<div class="col-12 mb-2"><strong>Preview:</strong></div>';
                previewContainer.style.display = 'flex';
                previewContainer.style.flexWrap = 'wrap';
                
                files.forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'col-md-2 col-4 mb-2';
                        previewDiv.innerHTML = `
                            <img src="${event.target.result}" class="img-thumbnail" style="height: 80px; width: 100%; object-fit: cover;" title="${escapeHtml(file.name)}">
                        `;
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }
    
    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Form submitted');
        
        if (!fileInput) {
            window.showCustomToast('File input not found', 'error');
            return;
        }
        
        const files = fileInput.files;
        if (files.length === 0) {
            window.showCustomToast('Please select at least one image to upload.', 'error');
            return;
        }
        
        if (files.length > 10) {
            window.showCustomToast('You can only upload up to 10 images at once.', 'error');
            return;
        }
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        
        const token = document.querySelector('input[name="_token"]')?.value;
        if (token) {
            formData.append('_token', token);
        }
        
        const uploadBtn = document.getElementById('uploadMultipleBtn');
        const originalText = uploadBtn ? uploadBtn.innerHTML : '';
        if (uploadBtn) {
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
        }
        
        // Show modal
        const modalElement = document.getElementById('uploadingModal');
        let modal = null;
        if (modalElement) {
            modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
        
        try {
            const response = await fetch('/admin/homepage/upload-multiple', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            console.log('Upload response:', data);
            
            if (modal) modal.hide();
            
            if (data.success) {
                window.showCustomToast(data.message, 'success');
                if (fileInput) fileInput.value = '';
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    previewContainer.innerHTML = '';
                }
                loadSlides(); // Reload slides
            } else {
                window.showCustomToast(data.message || 'Upload failed', 'error');
            }
        } catch (error) {
            console.error('Upload error:', error);
            if (modal) modal.hide();
            window.showCustomToast('Upload failed. Please try again.', 'error');
        } finally {
            if (uploadBtn) {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = originalText;
            }
        }
    });
}

// Setup present button listener
function setupPresentListener() {
    const presentBtn = document.getElementById('presentSlidesBtn');
    if (!presentBtn) {
        console.error('Present button not found');
        return;
    }
    
    console.log('Setting up present listener');
    
    presentBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.slide-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            window.showCustomToast('Please select at least one image to present.', 'error');
            return;
        }
        
        // Get IDs in the order they appear in the DOM (visual order)
        const slideItems = document.querySelectorAll('.slide-item');
        const selectedIds = [];
        
        slideItems.forEach(item => {
            const checkbox = item.querySelector('.slide-checkbox');
            if (checkbox && checkbox.checked) {
                selectedIds.push(parseInt(item.dataset.id));
            }
        });
        
        if (selectedIds.length === 0) {
            window.showCustomToast('No valid images selected.', 'error');
            return;
        }
        
        if (confirm(`Are you sure you want to present ${selectedIds.length} image(s) as the slideshow?`)) {
            presentBtn.disabled = true;
            presentBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
            
            fetch('/admin/homepage/present', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ slide_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.showCustomToast(data.message, 'success');
                } else {
                    window.showCustomToast(data.message || 'Failed to update slideshow', 'error');
                }
            })
            .catch(error => {
                console.error('Error presenting slides:', error);
                window.showCustomToast('Network error. Please try again.', 'error');
            })
            .finally(() => {
                presentBtn.disabled = false;
                presentBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Present Selected Images';
            });
        }
    });
}

// Delete slide function (global for onclick)
window.deleteSlide = function(slideId) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch(`/admin/homepage/slide/${slideId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.showCustomToast(data.message, 'success');
                loadSlides(); // Reload slides
            } else {
                window.showCustomToast(data.message || 'Failed to delete image', 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            window.showCustomToast('Network error deleting image', 'error');
        });
    }
};

// Show toast message (global function)
window.showCustomToast = function(message, type = 'success') {
    console.log('Showing toast:', message, type);
    
    const existingToast = document.querySelector('.login-toast');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = `login-toast ${type === 'success' ? 'success-toast' : (type === 'error' ? 'error-toast' : 'info-toast')}`;
    toast.innerHTML = `
        <div class="login-toast-content">
            <i class="fas ${type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-circle-exclamation' : 'fa-info-circle')}"></i>
            <span>${escapeHtml(message)}</span>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 300);
    }, 5000);
};

// ============================================
// Legacy Homepage Background Image Management (kept for compatibility)
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

function initHomepageLegacyManagement() {
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
    setupLegacyImageUploadListeners();
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

function setupLegacyImageUploadListeners() {
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