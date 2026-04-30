{{-- resources/views/admin/partials/home.blade.php --}}
<div class="content-card">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-images me-2"></i>Homepage Slideshow Management</h5>
        </div>
        <div class="card-body">
            <!-- Upload Section -->
            <div class="mb-4">
                <h6 class="mb-3"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Multiple Images</h6>
                <form id="multipleImagesForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="multipleImages" class="form-label">Select Images (Max 10, up to 5MB each)</label>
                                <input type="file" class="form-control" id="multipleImages" name="images[]" multiple accept="image/jpeg,image/png,image/gif,image/webp">
                                <div class="form-text">You can select multiple images at once. Hold Ctrl/Cmd to select multiple.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100" id="uploadMultipleBtn">
                                    <i class="fas fa-upload me-1"></i> Upload Images
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="uploadPreviewContainer" class="row mt-3" style="display: none;"></div>
            </div>

            <hr class="my-4">

            <!-- Manage Slideshow Section -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Manage Slideshow Images</h6>
                    <button type="button" class="btn btn-success" id="presentSlidesBtn">
                        <i class="fas fa-check-circle me-1"></i> Present Selected Images/Save
                    </button>
                </div>
                
                <div id="slidesContainer" class="row">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading slides...</p>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Instructions:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Drag and drop images to reorder them</li>
                        <li>Check the images you want to present in the slideshow</li>
                        <li>Click "Present Selected Images" to update the homepage slideshow</li>
                        <li>The order of checked images determines the slide sequence (first checked = first slide)</li>
                        <li>If no images are selected, the default background image will be used</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Uploading Modal -->
<div class="modal fade" id="uploadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Processing Images...</h5>
                <p class="text-muted">Please wait while we upload and process your images.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Make sure SortableJS is loaded
function loadSortableScript() {
    return new Promise((resolve, reject) => {
        if (typeof Sortable !== 'undefined') {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Failed to load SortableJS'));
        document.head.appendChild(script);
    });
}

let sortableInstance = null;
let allSlides = [];

// Initialize homepage management
function initHomepageManagement() {
    console.log('Initializing homepage management...');
    loadSortableScript()
        .then(() => {
            console.log('SortableJS loaded');
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
}

// Load all slides
function loadSlides() {
    console.log('Loading slides...');
    const container = document.getElementById('slidesContainer');
    if (!container) return;
    
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
            'Accept': 'application/json'
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
                        ${data.message || 'Failed to load slides'}
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

// Render slides in the container with checkboxes and drag-and-drop
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
                        <img src="data:image/png;base64,${slide.image_data}" class="card-img-top" alt="Slide ${index + 1}" style="height: 150px; object-fit: cover;">
                        <div class="position-absolute top-0 start-0 m-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input slide-checkbox" data-id="${slide.id}" style="width: 20px; height: 20px;">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="window.deleteSlide(${slide.id})">
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
        console.warn('SortableJS not available');
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
        console.error('Form not found');
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
                            <img src="${event.target.result}" class="img-thumbnail" style="height: 80px; width: 100%; object-fit: cover;" title="${file.name}">
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

// Show toast message
window.showCustomToast = function(message, type = 'success') {
    const existingToast = document.querySelector('.login-toast');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = `login-toast ${type === 'success' ? 'success-toast' : 'error-toast'}`;
    toast.innerHTML = `
        <div class="login-toast-content">
            <i class="fas ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
            <span>${escapeHtml(message)}</span>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
};

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHomepageManagement);
} else {
    // DOM already loaded, initialize after a short delay to ensure all elements are ready
    setTimeout(initHomepageManagement, 100);
}
</script>