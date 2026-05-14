<div class="content-card">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-images me-2"></i>Homepage Slideshow Management</h5>
        </div>
        <div class="card-body">
            <!-- Upload Section -->
            <div class="mb-4">
                <h6 class="mb-3"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Multiple Images</h6>
                <form action="{{ route('admin.homepage.uploadMultiple') }}" method="POST" enctype="multipart/form-data" id="multipleImagesForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="multipleImages" class="form-label">Select Images (Max 10, up to 10MB each)</label>
                                <input type="file" class="form-control" id="multipleImages" name="images[]" multiple accept="image/jpeg,image/png,image/gif,image/webp">
                                <div class="form-text">You can select multiple images at once. Hold Ctrl/Cmd to select multiple. Max 10MB per image.</div>
                            </div>
                            <!-- Preview Container -->
                            <div id="uploadPreviewContainer" class="row mt-3" style="display: none;"></div>
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
                
                <div id="slidesContainer">
                    @php
                        use App\Models\HomepageSlide;
                        $slides = HomepageSlide::orderBy('order', 'asc')->get();
                    @endphp
                    
                    @if($slides->count() > 0)
                        <div class="row" id="slidesSortableContainer">
                            @foreach($slides as $index => $slide)
                                <div class="col-md-3 col-sm-6 mb-3 slide-item" data-id="{{ $slide->id }}" data-order="{{ $slide->order }}">
                                    <div class="card h-100">
                                        <div class="position-relative">
                                            <img src="{{ '/storage.php?file=' . $slide->image_path }}" class="card-img-top" alt="Slide {{ $index + 1 }}" style="height: 150px; object-fit: cover; width: 100%;">
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           class="form-check-input slide-checkbox" 
                                                           data-id="{{ $slide->id }}"
                                                           {{ $slide->is_active ? 'checked' : '' }}
                                                           style="width: 20px; height: 20px;">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-slide-btn" data-id="{{ $slide->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            @if($slide->is_active)
                                                <span class="position-absolute bottom-0 start-0 m-2 badge bg-success">
                                                    <i class="fas fa-play me-1"></i>Active
                                                </span>
                                            @endif
                                        </div>
                                        <div class="card-body p-2 text-center">
                                            <small class="text-muted order-text">Order: {{ $slide->order }}</small>
                                            <div class="drag-handle mt-1" style="cursor: grab;">
                                                <i class="fas fa-grip-vertical"></i> Drag to reorder
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No images uploaded yet. Use the upload form above to add images.
                        </div>
                    @endif
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Instructions:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Check the images you want to present in the slideshow</li>
                        <li>Click "Present Selected Images" to update the homepage slideshow</li>
                        <li>Drag images to reorder them (order is saved automatically)</li>
                        <li>If no images are selected, the default background image will be used</li>
                        <li>Maximum file size: 10MB per image</li>
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
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6>Uploading images...</h6>
                <p class="text-muted mb-0">Please wait while your images are being uploaded.</p>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Get CSRF token from meta tag or input
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;

// ============================================
// Toast Notification System (Floating Only)
// ============================================

function showFloatingToast(message, type = 'success') {
    // Remove any existing toasts first
    const existingToasts = document.querySelectorAll('.floating-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `floating-toast ${type === 'success' ? 'success-toast' : (type === 'error' ? 'error-toast' : (type === 'warning' ? 'warning-toast' : 'info-toast'))}`;
    
    let icon = 'fa-circle-check';
    if (type === 'error') icon = 'fa-circle-exclamation';
    else if (type === 'warning') icon = 'fa-exclamation-triangle';
    else if (type === 'info') icon = 'fa-info-circle';
    
    toast.innerHTML = `
        <div class="floating-toast-content">
            <i class="fas ${icon}"></i>
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
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// File Preview and Validation (10MB limit)
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('multipleImages');
    const previewContainer = document.getElementById('uploadPreviewContainer');
    const form = document.getElementById('multipleImagesForm');
    let selectedFiles = [];
    
    if (fileInput) {
        fileInput.setAttribute('multiple', 'multiple');
        
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            selectedFiles = files;
            
            // Validate each file (10MB limit)
            const validFiles = [];
            const errors = [];
            
            files.forEach((file) => {
                // Check file size (10MB = 10 * 1024 * 1024)
                if (file.size > 10 * 1024 * 1024) {
                    errors.push(`${file.name}: File exceeds the 10MB limit. Current size: ${(file.size / 1024 / 1024).toFixed(2)}MB`);
                } else {
                    validFiles.push(file);
                }
            });
            
            if (errors.length > 0) {
                showFloatingToast(errors.join('\n'), 'error');
                
                // Clear the file input and preview
                fileInput.value = '';
                selectedFiles = [];
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    previewContainer.innerHTML = '';
                }
                return;
            }
            
            if (validFiles.length === 0) {
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    previewContainer.innerHTML = '';
                }
                return;
            }
            
            // Show preview for valid files
            if (previewContainer) {
                previewContainer.style.display = 'flex';
                previewContainer.style.flexWrap = 'wrap';
                previewContainer.style.gap = '10px';
                previewContainer.innerHTML = '';
                
                validFiles.forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'col-md-2 col-4 mb-2 preview-item';
                        previewDiv.setAttribute('data-file-name', file.name);
                        previewDiv.innerHTML = `
                            <div class="position-relative">
                                <img src="${event.target.result}" class="img-thumbnail" style="height: 80px; width: 100%; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-preview-btn" data-file-name="${escapeHtml(file.name)}" style="padding: 2px 6px; font-size: 10px;">
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
                
                // Add remove preview functionality
                setTimeout(() => {
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
    
    function removePreviewImage(fileName) {
        if (!fileInput) return;
        
        const currentFiles = Array.from(fileInput.files);
        const remainingFiles = currentFiles.filter(file => file.name !== fileName);
        
        selectedFiles = remainingFiles;
        
        const dataTransfer = new DataTransfer();
        remainingFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        
        if (remainingFiles.length === 0) {
            if (previewContainer) {
                previewContainer.style.display = 'none';
                previewContainer.innerHTML = '';
            }
        } else {
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
        
        showFloatingToast(`Removed ${fileName}`, 'info');
    }
    
    // Handle form submission with AJAX
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!fileInput || fileInput.files.length === 0) {
                showFloatingToast('Please select at least one image to upload.', 'error');
                return;
            }
            
            if (fileInput.files.length > 10) {
                showFloatingToast('You can only upload up to 10 images at once.', 'error');
                return;
            }
            
            // Validate all files before upload
            for (let i = 0; i < fileInput.files.length; i++) {
                const file = fileInput.files[i];
                if (file.size > 10 * 1024 * 1024) {
                    showFloatingToast(`${file.name}: File exceeds 10MB limit.`, 'error');
                    return;
                }
            }
            
            const formData = new FormData();
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append('images[]', fileInput.files[i]);
            }
            
            if (csrfToken) {
                formData.append('_token', csrfToken);
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
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 500));
                    throw new Error('Server returned HTML instead of JSON. Please check if you are logged in.');
                }
                
                const data = await response.json();
                
                if (modal) modal.hide();
                
                if (data.success) {
                    showFloatingToast(data.message, 'success');
                    fileInput.value = '';
                    selectedFiles = [];
                    if (previewContainer) {
                        previewContainer.style.display = 'none';
                        previewContainer.innerHTML = '';
                    }
                    // Reload page to show new slides
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showFloatingToast(data.message || 'Upload failed', 'error');
                }
            } catch (error) {
                console.error('Upload error:', error);
                if (modal) modal.hide();
                showFloatingToast(error.message || 'Upload failed. Please try again.', 'error');
            } finally {
                if (uploadBtn) {
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = originalText;
                }
            }
        });
    }
    
    // ============================================
    // Delete Slide Functionality
    // ============================================
    
    document.querySelectorAll('.delete-slide-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const slideId = this.getAttribute('data-id');
            if (!confirm('Are you sure you want to delete this image?')) return;
            
            const deleteBtn = this;
            const originalHtml = deleteBtn.innerHTML;
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                const response = await fetch(`/admin/homepage/slide/${slideId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 500));
                    throw new Error('Server returned HTML instead of JSON. Please check if you are logged in.');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showFloatingToast(data.message, 'success');
                    // Remove the slide item from DOM
                    const slideItem = document.querySelector(`.slide-item[data-id="${slideId}"]`);
                    if (slideItem) {
                        slideItem.remove();
                    }
                    // Check if no slides left
                    const remainingSlides = document.querySelectorAll('.slide-item');
                    if (remainingSlides.length === 0) {
                        location.reload();
                    }
                } else {
                    showFloatingToast(data.message || 'Failed to delete image', 'error');
                }
            } catch (error) {
                console.error('Delete error:', error);
                showFloatingToast(error.message || 'Network error deleting image', 'error');
            } finally {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = originalHtml;
            }
        });
    });
    
    // ============================================
    // Drag to Reorder Functionality
    // ============================================
    
    const slidesContainer = document.getElementById('slidesSortableContainer');
    let sortableInstance = null;
    let isUpdatingOrder = false;
    
    function initSortable() {
        if (slidesContainer && typeof Sortable !== 'undefined') {
            if (sortableInstance) {
                sortableInstance.destroy();
            }
            
            sortableInstance = new Sortable(slidesContainer, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    if (!isUpdatingOrder) {
                        saveNewOrder();
                    }
                }
            });
            console.log('Sortable initialized successfully');
        } else {
            console.log('Sortable container not found or Sortable not loaded');
        }
    }
    
    async function saveNewOrder() {
        if (isUpdatingOrder) return;
        isUpdatingOrder = true;
        
        const items = document.querySelectorAll('.slide-item');
        const updatedOrders = [];
        
        items.forEach((item, index) => {
            const slideId = parseInt(item.dataset.id);
            updatedOrders.push({
                id: slideId,
                order: index
            });
        });
        
        try {
            const response = await fetch('/admin/homepage/update-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ slides: updatedOrders })
            });
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text.substring(0, 500));
                throw new Error('Server returned HTML instead of JSON. Please check if you are logged in.');
            }
            
            const data = await response.json();
            
            if (data.success) {
                showFloatingToast('Order updated successfully!', 'success');
                // Update order numbers in UI
                updatedOrders.forEach(order => {
                    const slideItem = document.querySelector(`.slide-item[data-id="${order.id}"]`);
                    if (slideItem) {
                        const orderLabel = slideItem.querySelector('.order-text');
                        if (orderLabel) {
                            orderLabel.textContent = `Order: ${order.order}`;
                        }
                        slideItem.dataset.order = order.order;
                    }
                });
            } else {
                showFloatingToast(data.message || 'Failed to update order', 'error');
            }
        } catch (error) {
            console.error('Error updating order:', error);
            showFloatingToast(error.message || 'Network error updating order. Please refresh the page.', 'error');
        } finally {
            isUpdatingOrder = false;
        }
    }
    
    // Initialize sortable
    initSortable();
    
    // ============================================
    // Present Selected Images Functionality
    // ============================================
    
    const presentBtn = document.getElementById('presentSlidesBtn');
    if (presentBtn) {
        presentBtn.addEventListener('click', async function() {
            const slideItems = document.querySelectorAll('.slide-item');
            const selectedIds = [];
            
            slideItems.forEach(item => {
                const checkbox = item.querySelector('.slide-checkbox');
                if (checkbox && checkbox.checked) {
                    selectedIds.push(parseInt(item.dataset.id));
                }
            });
            
            let confirmMessage = '';
            if (selectedIds.length === 0) {
                confirmMessage = 'No images selected. This will clear the slideshow and use the default background image. Are you sure?';
            } else {
                confirmMessage = `Present ${selectedIds.length} image(s) as slideshow? The order will follow the current visual order.`;
            }
            
            if (!confirm(confirmMessage)) return;
            
            const originalText = presentBtn.innerHTML;
            presentBtn.disabled = true;
            presentBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
            
            try {
                const response = await fetch('/admin/homepage/present', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ slide_ids: selectedIds })
                });
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 500));
                    throw new Error('Server returned HTML instead of JSON. Please check if you are logged in.');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showFloatingToast(data.message, 'success');
                    // Reload to show updated active states
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showFloatingToast(data.message || 'Failed to update slideshow', 'error');
                }
            } catch (error) {
                console.error('Error presenting slides:', error);
                showFloatingToast(error.message || 'Network error. Please try again.', 'error');
            } finally {
                presentBtn.disabled = false;
                presentBtn.innerHTML = originalText;
            }
        });
    }
    
    // Test JSON endpoint to verify connectivity
    async function testJsonEndpoint() {
        try {
            const response = await fetch('/admin/test-json', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            console.log('JSON test endpoint working:', data);
        } catch (error) {
            console.error('JSON test endpoint failed:', error);
            showFloatingToast('Warning: Server JSON endpoints not responding correctly. Some features may not work.', 'warning');
        }
    }
    
    // Run test
    testJsonEndpoint();
});
</script>