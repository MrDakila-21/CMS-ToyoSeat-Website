  // ============================================
    // Homepage Background Image Management
    // ============================================
    
    // Global modal instance
    let uploadingModal = null;
    let currentOperation = null; // Track current operation: 'upload' or 'remove'
    
    // Initialize modal when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('uploadingModal');
        if (modalElement) {
            uploadingModal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            // Add event listener to ensure cleanup when modal is fully hidden
            modalElement.addEventListener('hidden.bs.modal', function() {
                // Force remove any leftover backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
        }
        
        // Load current homepage image if home panel is active
        if (document.querySelector('#home-panel') && document.querySelector('#home-panel').classList.contains('active')) {
            loadCurrentHomepageImage();
        }
    });
    
    // Function to update modal content based on operation
    function updateModalContent(operation) {
        const modalBody = document.querySelector('#uploadingModal .modal-body');
        
        if (!modalBody) return;
        
        if (operation === 'upload') {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5>Uploading Image...</h5>
                    <p class="text-muted mb-0">Please wait while your image is being processed.</p>
                </div>
            `;
        } else if (operation === 'remove') {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5>Removing Background Image...</h5>
                    <p class="text-muted mb-0">Please wait while we remove the background image.</p>
                </div>
            `;
        }
    }
    
    // Function to safely hide modal with proper cleanup
    function hideUploadingModal() {
        if (uploadingModal) {
            // Force hide the modal
            uploadingModal.hide();
            
            // Manually clean up any leftover backdrop after a short delay
            setTimeout(function() {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(function(backdrop) {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                document.body.style.position = '';
            }, 150);
        }
    }
    
    // Load current homepage image
    function loadCurrentHomepageImage() {
        fetch('/admin/homepage/image', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.has_image) {
                const previewImg = document.getElementById('previewImg');
                const noImagePlaceholder = document.getElementById('noImagePlaceholder');
                
                previewImg.src = 'data:image/png;base64,' + data.image_data;
                previewImg.style.display = 'inline-block';
                noImagePlaceholder.style.display = 'none';
            } else {
                const previewImg = document.getElementById('previewImg');
                const noImagePlaceholder = document.getElementById('noImagePlaceholder');
                previewImg.style.display = 'none';
                noImagePlaceholder.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading homepage image:', error);
            showCustomToast('Failed to load current image', 'error');
        });
    }
    
    // Preview new image before upload
    const backgroundImageInput = document.getElementById('backgroundImage');
    if (backgroundImageInput) {
        backgroundImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const newPreviewContainer = document.getElementById('newImagePreviewContainer');
            const newPreviewImg = document.getElementById('newPreviewImg');
            
            if (file) {
                // Validate file size first
                if (file.size > 5 * 1024 * 1024) {
                    showCustomToast('File exceeds the 5MB size limit. Please choose a smaller file.', 'error');
                    this.value = ''; // Clear the input
                    newPreviewContainer.style.display = 'none';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showCustomToast('Invalid file type. Please upload JPG, PNG, GIF, or WEBP images only.', 'error');
                    this.value = ''; // Clear the input
                    newPreviewContainer.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    newPreviewImg.src = event.target.result;
                    newPreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                newPreviewContainer.style.display = 'none';
                newPreviewImg.src = '';
            }
        });
    }
    
    // Handle form submission for image upload
    const homepageImageForm = document.getElementById('homepageImageForm');
    if (homepageImageForm) {
        homepageImageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('backgroundImage');
            const file = fileInput.files[0];
            
            if (!file) {
                showCustomToast('Please select an image file first.', 'error');
                return;
            }
            
            // Validate file type again before submission
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showCustomToast('Invalid file type. Please upload JPG, PNG, GIF, or WEBP images only.', 'error');
                return;
            }
            
            // Validate file size again before submission
            if (file.size > 5 * 1024 * 1024) {
                showCustomToast('File is too large. Maximum size is 5MB.', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('background_image', file);
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
            // Disable the upload button to prevent double submission
            const uploadBtn = document.getElementById('uploadBtn');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
            
            // Update modal for upload operation and show it
            currentOperation = 'upload';
            updateModalContent('upload');
            if (uploadingModal) {
                uploadingModal.show();
            }
            
            fetch('/admin/homepage/upload-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Hide modal first
                hideUploadingModal();
                
                // Re-enable upload button
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Upload/Update Background Image';
                
                if (data.success) {
                    showCustomToast(data.message, 'success');
                    // Clear file input and new preview
                    fileInput.value = '';
                    document.getElementById('newImagePreviewContainer').style.display = 'none';
                    document.getElementById('newPreviewImg').src = '';
                    // Reload current image
                    loadCurrentHomepageImage();
                } else {
                    showCustomToast(data.message, 'error');
                }
            })
            .catch(error => {
                hideUploadingModal();
                // Re-enable upload button
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Upload/Update Background Image';
                console.error('Error:', error);
                showCustomToast('Network error. Please check your connection and try again.', 'error');
            });
        });
    }
    
    // Remove newly selected image (before upload)
    const removeNewImageBtn = document.getElementById('removeNewImageBtn');
    if (removeNewImageBtn) {
        removeNewImageBtn.addEventListener('click', function() {
            const fileInput = document.getElementById('backgroundImage');
            fileInput.value = '';
            
            const newPreviewContainer = document.getElementById('newImagePreviewContainer');
            const newPreviewImg = document.getElementById('newPreviewImg');
            
            newPreviewContainer.style.display = 'none';
            newPreviewImg.src = '';
            
            showCustomToast('New image selection removed.', 'success');
        });
    }
    
    // Handle remove image button
    const removeImageBtn = document.getElementById('removeImageBtn');
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            const previewImg = document.getElementById('previewImg');
            const noImagePlaceholder = document.getElementById('noImagePlaceholder');
            
            // Check if image is currently displayed
            const hasActiveImage = previewImg.style.display === 'inline-block' && previewImg.src && previewImg.src !== '';
            
            if (!hasActiveImage) {
                showCustomToast('No background image to remove.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to remove the background image? The default picture will be shown instead.')) {
                // Disable remove button
                removeImageBtn.disabled = true;
                removeImageBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Removing...';
                
                // Update modal for remove operation and show it
                currentOperation = 'remove';
                updateModalContent('remove');
                if (uploadingModal) {
                    uploadingModal.show();
                }
                
                fetch('/admin/homepage/remove-image', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide modal
                    hideUploadingModal();
                    
                    // Re-enable remove button
                    removeImageBtn.disabled = false;
                    removeImageBtn.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Remove Background Image';
                    
                    if (data.success) {
                        showCustomToast(data.message, 'success');
                        // Reset preview to show placeholder
                        previewImg.style.display = 'none';
                        previewImg.src = '';
                        noImagePlaceholder.style.display = 'block';
                        // Clear file input
                        const fileInput = document.getElementById('backgroundImage');
                        if (fileInput) {
                            fileInput.value = '';
                        }
                        const newPreviewContainer = document.getElementById('newImagePreviewContainer');
                        if (newPreviewContainer) {
                            newPreviewContainer.style.display = 'none';
                        }
                    } else {
                        showCustomToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    hideUploadingModal();
                    // Re-enable remove button
                    removeImageBtn.disabled = false;
                    removeImageBtn.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Remove Background Image';
                    console.error('Error:', error);
                    showCustomToast('Network error. Please check your connection and try again.', 'error');
                });
            }
        });
    }
    
    // Custom toast function for dashboard messages
    function showCustomToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.querySelector('.login-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
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
        }, 5000);
        
        setTimeout(() => {
            toast.remove();
        }, 5600);
    }
    
    // Helper function to escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Override switchTab function to load homepage image when home tab is selected
    const originalSwitchTab = window.switchTab;
    window.switchTab = function(tabId) {
        if (originalSwitchTab) {
            originalSwitchTab(tabId);
        }
        if (tabId === 'home') {
            loadCurrentHomepageImage();
        }
    };
    
    // Initial load if home tab is active
    if (document.querySelector('#home-panel') && document.querySelector('#home-panel').classList.contains('active')) {
        loadCurrentHomepageImage();
    }
