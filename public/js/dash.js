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
    
    // Reset the present listener flag when reinitializing
    presentListenerAttached = false;
    
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

// Replace the renderSlides function with this updated version with better checkbox styling
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
                <div class="card h-100 position-relative">
                    <div style="position: relative;">
                        <img src="${slide.image_url}" class="card-img-top" alt="Slide ${index + 1}" style="height: 150px; object-fit: cover; width: 100%;">
                        <div style="position: absolute; top: 8px; left: 8px; z-index: 10; background: rgba(255,255,255,0.9); border-radius: 4px; padding: 4px;">
                            <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; margin: 0;">
                                <input type="checkbox" class="slide-checkbox" data-id="${slide.id}" style="width: 18px; height: 18px; cursor: pointer; margin: 0;" ${slide.is_active ? 'checked' : ''}>
                                <span style="font-size: 12px; color: #333;">Select</span>
                            </label>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" style="position: absolute; top: 8px; right: 8px; z-index: 10; padding: 4px 8px; font-size: 12px;" onclick="window.deleteSlide(${slide.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-2 text-center">
                        <small class="text-muted">Order: ${slide.order}</small>
                        <div class="drag-handle mt-1" style="cursor: grab;">
                            <i class="fas fa-grip-vertical"></i> Drag to reorder
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    
    // Initialize drag and drop - preserve checkbox states during drag
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
                    // Save checked states before updating order
                    const checkedIds = getCheckedSlideIds();
                    // Update the order
                    updateOrderFromDragWithState(checkedIds);
                }
            });
            console.log('Sortable initialized');
        }
    } else {
        console.warn('SortableJS not available - drag and drop disabled');
    }
}

// New function to handle order update with state preservation
function updateOrderFromDragWithState(checkedIds) {
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
        console.log('Order update response:', data);
        if (data.success) {
            // Update order numbers in UI without reloading
            updatedOrders.forEach(order => {
                const slideItem = document.querySelector(`.slide-item[data-id="${order.id}"]`);
                if (slideItem) {
                    const orderLabel = slideItem.querySelector('.text-muted');
                    if (orderLabel) {
                        orderLabel.textContent = `Order: ${order.order}`;
                    }
                    slideItem.dataset.order = order.order;
                }
            });
            // Show success message
            window.showCustomToast('Order updated successfully!', 'success');
        } else {
            window.showCustomToast(data.message || 'Failed to update order', 'error');
            // Reload slides to restore correct order
            loadSlides();
        }
    })
    .catch(error => {
        console.error('Error updating order:', error);
        window.showCustomToast('Network error updating order.', 'error');
    });
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
    
    // Don't show toast for order update to avoid confusion
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
        console.log('Order update response:', data);
        if (data.success) {
            // Only show success toast, don't reload to avoid flicker
            window.showCustomToast('Order updated successfully!', 'success');
            // Just update the order numbers in the UI without reloading
            updateOrderNumbersInUI(updatedOrders);
        } else {
            window.showCustomToast(data.message || 'Failed to update order', 'error');
            // Reload slides to restore correct order on error
            loadSlides();
        }
    })
    .catch(error => {
        console.error('Error updating order:', error);
        window.showCustomToast('Network error updating order. Please refresh the page.', 'error');
        // Reload slides to restore correct order on error
        loadSlides();
    });
}

// Helper function to update order numbers in UI without reloading
function updateOrderNumbersInUI(updatedOrders) {
    updatedOrders.forEach(order => {
        const slideItem = document.querySelector(`.slide-item[data-id="${order.id}"]`);
        if (slideItem) {
            const orderLabel = slideItem.querySelector('.text-muted');
            if (orderLabel) {
                orderLabel.textContent = `Order: ${order.order}`;
            }
            // Update the data-order attribute
            slideItem.dataset.order = order.order;
        }
    });
}

// Helper function to get currently checked slide IDs
function getCheckedSlideIds() {
    const checkedBoxes = document.querySelectorAll('.slide-checkbox:checked');
    const checkedIds = [];
    checkedBoxes.forEach(checkbox => {
        const id = parseInt(checkbox.getAttribute('data-id'));
        if (!isNaN(id)) {
            checkedIds.push(id);
        }
    });
    return checkedIds;
}

// Helper function to restore checked states after reload
function restoreCheckedStates(checkedIds) {
    if (!checkedIds || checkedIds.length === 0) return;
    
    // Wait a bit for the DOM to update
    setTimeout(() => {
        const checkboxes = document.querySelectorAll('.slide-checkbox');
        checkboxes.forEach(checkbox => {
            const id = parseInt(checkbox.getAttribute('data-id'));
            if (checkedIds.includes(id)) {
                checkbox.checked = true;
            }
        });
    }, 100);
}

// Setup multiple image upload listener - FIX for multiple file upload with remove preview
function setupMultipleUploadListener() {
    const form = document.getElementById('multipleImagesForm');
    if (!form) {
        console.error('Multiple images form not found');
        return;
    }
    
    console.log('Setting up upload listener');
    
    const fileInput = document.getElementById('multipleImages');
    const previewContainer = document.getElementById('uploadPreviewContainer');
    
    // Store selected files for validation
    let selectedFiles = [];
    
    // Important: Set multiple attribute properly
    if (fileInput) {
        fileInput.setAttribute('multiple', 'multiple');
        
        // Preview selected images with remove buttons
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            selectedFiles = files;
            console.log('Files selected:', files.length);
            
            if (files.length === 0) {
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    previewContainer.innerHTML = '';
                }
                return;
            }
            
            // Validate each file before showing preview
            const validFiles = [];
            const errors = [];
            
            files.forEach((file, index) => {
                const validationError = validateUploadFile(file);
                if (validationError) {
                    errors.push(`${file.name}: ${validationError}`);
                } else {
                    validFiles.push(file);
                }
            });
            
            if (errors.length > 0) {
                window.showCustomToast(errors.join('\n'), 'error');
                // Remove invalid files from selection
                selectedFiles = validFiles;
                // Update file input with valid files only
                const dataTransfer = new DataTransfer();
                validFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }
            
            if (validFiles.length === 0) {
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    previewContainer.innerHTML = '';
                }
                return;
            }
            
            if (previewContainer) {
                previewContainer.innerHTML = '<div class="col-12 mb-2"><strong>Preview (' + validFiles.length + ' images):</strong> <button type="button" class="btn btn-sm btn-danger" id="clearAllPreviews">Clear All</button></div>';
                previewContainer.style.display = 'flex';
                previewContainer.style.flexWrap = 'wrap';
                
                validFiles.forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'col-md-2 col-4 mb-2 preview-item';
                        previewDiv.setAttribute('data-file-index', idx);
                        previewDiv.setAttribute('data-file-name', file.name);
                        previewDiv.innerHTML = `
                            <div class="position-relative">
                                <img src="${event.target.result}" class="img-thumbnail" style="height: 80px; width: 100%; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 inset-end-0 m-1 remove-preview-btn" style="padding: 2px 6px; font-size: 10px;" data-file-name="${escapeHtml(file.name)}">
                                    <i class="fas fa-times"></i>
                                </button>
                                <small class="text-muted d-block text-truncate mt-1">${escapeHtml(file.name.substring(0, 20))}</small>
                                <small class="text-muted d-block" style="font-size: 10px;">${(file.size / 1024).toFixed(1)} KB</small>
                            </div>
                        `;
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                });
                
                // Add clear all button functionality after DOM update
                setTimeout(() => {
                    const clearAllBtn = document.getElementById('clearAllPreviews');
                    if (clearAllBtn) {
                        clearAllBtn.addEventListener('click', function() {
                            clearAllPreviews();
                        });
                    }
                    
                    // Add remove individual preview functionality
                    document.querySelectorAll('.remove-preview-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const fileName = this.getAttribute('data-file-name');
                            removePreviewImage(fileName);
                        });
                    });
                }, 100);
            }
        });
    }
    
    // Function to clear all previews
    function clearAllPreviews() {
        if (fileInput) {
            fileInput.value = '';
            selectedFiles = [];
        }
        if (previewContainer) {
            previewContainer.style.display = 'none';
            previewContainer.innerHTML = '';
        }
        window.showCustomToast('All images cleared', 'info');
    }
    
    // Function to remove individual preview image
    function removePreviewImage(fileName) {
        if (!fileInput) return;
        
        // Get current files
        const currentFiles = Array.from(fileInput.files);
        // Filter out the file to remove
        const remainingFiles = currentFiles.filter(file => file.name !== fileName);
        
        // Update selectedFiles
        selectedFiles = remainingFiles;
        
        // Update file input
        const dataTransfer = new DataTransfer();
        remainingFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        
        // Re-render preview
        if (remainingFiles.length === 0) {
            if (previewContainer) {
                previewContainer.style.display = 'none';
                previewContainer.innerHTML = '';
            }
        } else {
            // Trigger change event to re-render preview
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
        
        window.showCustomToast(`Removed ${fileName}`, 'info');
    }
    
    // Function to validate upload file
    function validateUploadFile(file) {
        if (!file) {
            return 'Invalid file';
        }
        
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            return `File exceeds the 5MB size limit. Current size: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
        }
        
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!allowedTypes.includes(file.type) || !allowedExtensions.includes(fileExtension)) {
            return 'Invalid file type. Please upload JPG, PNG, GIF, or WEBP images only.';
        }
        
        return null;
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
        console.log('Files to upload:', files.length);
        
        if (files.length === 0) {
            window.showCustomToast('Please select at least one image to upload.', 'error');
            return;
        }
        
        if (files.length > 10) {
            window.showCustomToast('You can only upload up to 10 images at once.', 'error');
            return;
        }
        
        // Validate all files before upload
        const validationErrors = [];
        for (let i = 0; i < files.length; i++) {
            const error = validateUploadFile(files[i]);
            if (error) {
                validationErrors.push(`${files[i].name}: ${error}`);
            }
        }
        
        if (validationErrors.length > 0) {
            window.showCustomToast(validationErrors.join('\n'), 'error');
            return;
        }
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
            console.log('Appending file:', files[i].name);
        }
        
        const token = document.querySelector('input[name="_token"]')?.value;
        if (token) {
            formData.append('_token', token);
        }
        
        const uploadBtn = document.getElementById('uploadMultipleBtn');
        const originalText = uploadBtn ? uploadBtn.innerHTML : '';
        if (uploadBtn) {
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading ' + files.length + ' images...';
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
                selectedFiles = [];
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

let presentListenerAttached = false;

function setupPresentListener() {
    const presentBtn = document.getElementById('presentSlidesBtn');
    if (!presentBtn) {
        console.error('Present button not found');
        return;
    }
    
    // Remove existing listener if already attached
    if (presentListenerAttached) {
        // Clone and replace the button to remove all existing listeners
        const newPresentBtn = presentBtn.cloneNode(true);
        presentBtn.parentNode.replaceChild(newPresentBtn, presentBtn);
        // Update reference to the new button
        const updatedPresentBtn = document.getElementById('presentSlidesBtn');
        if (updatedPresentBtn) {
            attachPresentListener(updatedPresentBtn);
        }
    } else {
        attachPresentListener(presentBtn);
    }
}

function attachPresentListener(button) {
    console.log('Setting up present listener');
    presentListenerAttached = true;
    
    button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
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
            window.showCustomToast('Please select at least one image to present.', 'error');
            return;
        }
        
        // Use a simple confirm instead of multiple confirms
        const confirmMessage = `Are you sure you want to present ${selectedIds.length} image(s) as the slideshow? The order will follow the current visual order of checked images.`;
        
        if (confirm(confirmMessage)) {
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
            
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
                    loadSlides(); // Reload to show updated active states
                } else {
                    window.showCustomToast(data.message || 'Failed to update slideshow', 'error');
                }
            })
            .catch(error => {
                console.error('Error presenting slides:', error);
                window.showCustomToast('Network error. Please try again.', 'error');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalText;
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