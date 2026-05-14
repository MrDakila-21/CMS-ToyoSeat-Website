<div class="card content-card">
    <div class="card-header">
        <h5><i class="fas fa-info-circle me-2"></i>Manage Overview Content</h5>
    </div>
    <div class="card-body">
        <!-- Business Principles Section -->
        <div class="section-box mb-4">
            <div class="section-header">
                <h4><i class="fas fa-gem me-2"></i>Business Principles</h4>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPrincipleModal">
                    <i class="fas fa-plus me-1"></i> Add Principle
                </button>
            </div>
            <div class="section-body">
                <div id="business-principles-list">
                    @if($content->business_principles && count($content->business_principles) > 0)
                        @foreach($content->business_principles as $index => $principle)
                            <div class="principle-item card mb-3" data-id="{{ $principle['id'] }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="principle-title">{{ $principle['title'] }}</h5>
                                            <p class="principle-description text-muted" style="white-space: pre-wrap;">{{ $principle['description'] }}</p>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-warning edit-principle me-2" 
                                                data-id="{{ $principle['id'] }}"
                                                data-title="{{ $principle['title'] }}"
                                                data-description="{{ $principle['description'] }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-principle" 
                                                data-id="{{ $principle['id'] }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">No business principles added yet. Click "Add Principle" to get started.</div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Message from President Section with Edit/Save -->
        <div class="section-box mb-4" id="president-section">
            <div class="section-header">
                <h4><i class="fas fa-user-tie me-2"></i>Message from the President</h4>
                <div>
                    <button type="button" class="btn btn-sm btn-warning edit-section-btn" data-section="president">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-success save-section-btn" data-section="president" style="display: none;">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </div>
            <div class="section-body">
                <form id="president-form" class="section-form" data-section="president">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">President Image</label>
                                <div class="image-upload-wrapper">
                                    <input type="file" class="form-control" name="president_image" accept="image/jpeg,image/png,image/gif" id="president_image" disabled>
                                    <div class="form-text">Max 5MB. Allowed: JPG, PNG, GIF</div>
                                    
                                    <!-- Current Image Display with Placeholder -->
                                    <div class="mt-2">
                                        <label class="form-label small text-muted">Current Image:</label>
                                        <div id="currentPresidentImageWrapper" class="position-relative d-inline-block">
                                            @if($content->president_image)
                                                <div class="current-image-wrapper" id="currentPresidentImage">
                                                    <img src="{{ '/storage.php?file=' . $content->president_image }}" alt="President" class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button" class="btn btn-sm btn-danger remove-image-btn" data-image-type="president" style="position: absolute; top: 5px; right: 5px;">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="image-placeholder" id="presidentImagePlaceholder" style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                                    <i class="fas fa-user-tie fa-3x text-muted mb-2"></i>
                                                    <span class="text-muted small">No Image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- New Image Preview Container -->
                                    <div id="presidentImagePreviewContainer" style="display: none;" class="mt-3">
                                        <label class="form-label small text-muted">New Image Preview:</label>
                                        <div class="position-relative d-inline-block">
                                            <img id="presidentImagePreview" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 inset-e-0 remove-new-image-preview" data-target="president_image" style="padding: 2px 6px; font-size: 10px;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">President Name</label>
                                <input type="text" class="form-control" name="president_name" value="{{ $content->president_name }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">President Title</label>
                                <input type="text" class="form-control" name="president_title" value="{{ $content->president_title }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea class="form-control auto-expand" name="president_message" rows="5" disabled>{{ $content->president_message }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Company Profile Section with Edit/Save and Dynamic Categories -->
        <div class="section-box mb-4" id="company-profile-section">
            <div class="section-header">
                <h4><i class="fas fa-building me-2"></i>Company Profile</h4>
                <div>
                    <button type="button" class="btn btn-sm btn-info add-category-btn me-2" style="display: none;">
                        <i class="fas fa-plus me-1"></i>Add Category
                    </button>
                    <button type="button" class="btn btn-sm btn-warning edit-section-btn" data-section="company">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-success save-section-btn" data-section="company" style="display: none;">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </div>
            <div class="section-body" id="company-profile-section-body">
                <form id="company-form" class="section-form" data-section="company">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Company Image</label>
                                <div class="image-upload-wrapper">
                                    <input type="file" class="form-control" name="company_profile_image" accept="image/jpeg,image/png,image/gif" id="company_profile_image" disabled>
                                    <div class="form-text">Max 5MB. Allowed: JPG, PNG, GIF</div>
                                    
                                    <!-- Current Image Display with Placeholder -->
                                    <div class="mt-2">
                                        <label class="form-label small text-muted">Current Image:</label>
                                        <div id="currentCompanyImageWrapper" class="position-relative d-inline-block">
                                            @if($content->company_profile_image)
                                                <div class="current-image-wrapper" id="currentCompanyImage">
                                                    <img src="{{ '/storage.php?file=' . $content->company_profile_image }}" alt="Company" class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button" class="btn btn-sm btn-danger remove-image-btn" data-image-type="company" style="position: absolute; top: 5px; right: 5px;">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="image-placeholder" id="companyImagePlaceholder" style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                                    <i class="fas fa-building fa-3x text-muted mb-2"></i>
                                                    <span class="text-muted small">No Image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- New Image Preview Container -->
                                    <div id="companyImagePreviewContainer" style="display: none;" class="mt-3">
                                        <label class="form-label small text-muted">New Image Preview:</label>
                                        <div class="position-relative d-inline-block">
                                            <img id="companyImagePreview" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 inset-e-0 remove-new-image-preview" data-target="company_profile_image" style="padding: 2px 6px; font-size: 10px;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name" value="{{ $content->company_name }}" disabled>
                            </div>
                            
                            <!-- Dynamic Categories Container -->
                            <div id="dynamic-categories-container">
                                @php
                                    $categories = [
                                        'established_date' => ['label' => 'Established Date', 'icon' => 'fa-calendar-alt', 'value' => $content->established_date],
                                        'capital' => ['label' => 'Capital', 'icon' => 'fa-chart-line', 'value' => $content->capital],
                                        'president_representative' => ['label' => 'President & Representative', 'icon' => 'fa-user-tie', 'value' => $content->president_representative],
                                        'business_description' => ['label' => 'Business Description', 'icon' => 'fa-industry', 'value' => $content->business_description, 'is_textarea' => true],
                                        'employees' => ['label' => 'Number of Employees', 'icon' => 'fa-users', 'value' => $content->employees, 'is_number' => true]
                                    ];
                                @endphp
                                
                                @foreach($categories as $key => $category)
                                    <div class="category-item mb-3" data-category-key="{{ $key }}">
                                        <div class="d-flex align-items-start gap-2">
                                            <div class="grow">
                                                <label class="form-label">
                                                    <i class="fas {{ $category['icon'] }} me-1"></i>{{ $category['label'] }}
                                                </label>
                                                @if(isset($category['is_textarea']) && $category['is_textarea'])
                                                    <textarea class="form-control auto-expand" name="{{ $key }}" rows="3" disabled>{{ $category['value'] }}</textarea>
                                                @elseif(isset($category['is_number']) && $category['is_number'])
                                                    <input type="number" class="form-control" name="{{ $key }}" value="{{ $category['value'] }}" disabled>
                                                @else
                                                    <input type="text" class="form-control" name="{{ $key }}" value="{{ $category['value'] }}" disabled>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger remove-category-btn mt-4" data-category-key="{{ $key }}" style="display: none;">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                
                               <!-- Dynamic categories from database -->
@if($content->dynamic_categories && count($content->dynamic_categories) > 0)
    @foreach($content->dynamic_categories as $key => $value)
        @if(!in_array($key, ['established_date', 'capital', 'president_representative', 'business_description', 'employees']))
            @php
                $metadata = $content->category_metadata[$key] ?? ['label' => ucfirst(str_replace('_', ' ', $key)), 'icon' => 'fa-tag', 'field_type' => 'text'];
            @endphp
            <div class="category-item mb-3" data-category-key="{{ $key }}">
                <div class="d-flex align-items-start gap-2">
                    <div class="grow">
                        <label class="form-label">
                            <i class="fas {{ $metadata['icon'] }} me-1"></i>{{ $metadata['label'] }}
                        </label>
                        @if(isset($metadata['field_type']) && $metadata['field_type'] === 'textarea')
                            <textarea class="form-control auto-expand" name="{{ $key }}" rows="3" disabled>{{ $value }}</textarea>
                        @elseif(isset($metadata['field_type']) && $metadata['field_type'] === 'number')
                            <input type="number" class="form-control" name="{{ $key }}" value="{{ $value }}" disabled>
                        @else
                            <input type="text" class="form-control" name="{{ $key }}" value="{{ $value }}" disabled>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-category-btn mt-4" data-category-key="{{ $key }}" style="display: none;">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        @endif
    @endforeach
@endif
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Company Profile Details</label>
                                <textarea class="form-control auto-expand" name="company_profile" rows="5" disabled>{{ $content->company_profile }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Principle Modal -->
<div class="modal fade" id="addPrincipleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Business Principle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-principle-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" name="title" placeholder="Enter principle title (optional)">
                        <small class="text-muted">You can leave this blank if needed</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control auto-expand" name="description" rows="3" required placeholder="Enter principle description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-principle-btn">Save Principle</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-category-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="category_key" required placeholder="e.g., website, email, address">
                        <small class="text-muted">Use lowercase with underscores (e.g., contact_email)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="category_label" required placeholder="e.g., Website, Email Address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" class="form-control" name="category_icon" placeholder="e.g., fa-globe, fa-envelope" value="fa-tag">
                        <small class="text-muted">Font Awesome icon class (e.g., fa-globe)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Field Type</label>
                        <select class="form-control" name="field_type">
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="number">Number</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Value</label>
                        <textarea class="form-control" name="initial_value" rows="2" placeholder="Enter initial value"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-category-btn">Add Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Principle Modal -->
<div class="modal fade" id="editPrincipleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Business Principle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-principle-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="principle_id" id="edit_principle_id">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" name="title" id="edit_title" placeholder="Enter principle title (optional)">
                        <small class="text-muted">You can leave this blank if needed</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control auto-expand" name="description" id="edit_description" rows="3" required placeholder="Enter principle description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="update-principle-btn">Update Principle</button>
            </div>
        </div>
    </div>
</div>

<style>
.section-box {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}


.section-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}


/* Add scrollbar to Company Profile section only */
#company-profile-section-body {
    max-height: 500px;
    overflow-y: auto;
    overflow-x: hidden;  /* This removes horizontal scrollbar */
    padding-right: 10px;
}

/* Custom scrollbar styling */
#company-profile-section-body::-webkit-scrollbar {
    width: 8px;
}

#company-profile-section-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#company-profile-section-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#company-profile-section-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.section-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.section-body {
    padding: 20px;
}

.principle-item {
    transition: all 0.3s ease;
}

.principle-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.auto-expand {
    overflow: hidden;
    resize: vertical;
    min-height: 80px;
    transition: height 0.1s ease;
}

.image-upload-wrapper {
    position: relative;
}

.current-image-wrapper {
    position: relative;
    display: inline-block;
}

.image-placeholder {
    transition: all 0.3s ease;
}

.category-item {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.category-item-removing {
    animation: slideOut 0.3s ease forwards;
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(20px);
        display: none;
    }
}

/* Additional styling for image placeholders */
.image-placeholder i {
    font-size: 3rem;
    color: #adb5bd;
}

.image-placeholder span {
    font-size: 0.75rem;
    color: #6c757d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                      document.querySelector('input[name="_token"]')?.value;
    
    // ============================================
    // Toast Notification System
    // ============================================
    
    function showFloatingToast(message, type = 'success') {
        // Remove any existing toasts
        const existingToasts = document.querySelectorAll('.floating-toast');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `floating-toast ${type === 'success' ? 'success-toast' : (type === 'error' ? 'error-toast' : 'info-toast')}`;
        
        let icon = 'fa-circle-check';
        if (type === 'error') icon = 'fa-circle-exclamation';
        else if (type === 'info') icon = 'fa-info-circle';
        
        toast.innerHTML = `
            <div class="floating-toast-content">
                <i class="fas ${icon}"></i>
                <span>${escapeHtml(message)}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Add styles if not already present
        if (!document.querySelector('#toast-styles')) {
            const styles = document.createElement('style');
            styles.id = 'toast-styles';
            styles.textContent = `
                .floating-toast {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    animation: slideInRight 0.3s ease;
                    max-width: 350px;
                    min-width: 250px;
                }
                .floating-toast.hide {
                    animation: slideOutRight 0.3s ease forwards;
                }
                .floating-toast-content {
                    background: white;
                    border-radius: 8px;
                    padding: 12px 20px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    border-left: 4px solid;
                }
                .success-toast .floating-toast-content {
                    border-left-color: #28a745;
                }
                .success-toast i {
                    color: #28a745;
                }
                .error-toast .floating-toast-content {
                    border-left-color: #dc3545;
                }
                .error-toast i {
                    color: #dc3545;
                }
                .info-toast .floating-toast-content {
                    border-left-color: #17a2b8;
                }
                .info-toast i {
                    color: #17a2b8;
                }
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(styles);
        }
        
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
    // Image File Validation and Preview
    // ============================================
    
    function validateImageFile(file) {
        if (!file) return 'No file selected';
        
        // Check file size (5MB = 5 * 1024 * 1024)
        if (file.size > 5 * 1024 * 1024) {
            return `File exceeds the 5MB limit. Current size: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        const fileExtension = file.name.split('.').pop().toLowerCase();
        const isValidMime = allowedTypes.includes(file.type);
        const isValidExtension = allowedExtensions.includes(fileExtension);
        
        if (!isValidMime || !isValidExtension) {
            return `Invalid file type. Please upload JPG, PNG, GIF images only.`;
        }
        
        return null;
    }
    
    // Function to handle remove button state based on edit mode
    function updateRemoveButtonsState(isEditMode, section) {
        const removeButtons = document.querySelectorAll(`.remove-image-btn[data-image-type="${section}"]`);
        removeButtons.forEach(btn => {
            if (isEditMode) {
                btn.style.display = 'block';
                btn.disabled = false;
            } else {
                btn.style.display = 'none';
                btn.disabled = true;
            }
        });
    }
    
    // Setup image preview for president image
    const presidentImageInput = document.getElementById('president_image');
    const presidentPreviewContainer = document.getElementById('presidentImagePreviewContainer');
    const presidentPreviewImg = document.getElementById('presidentImagePreview');
    let currentPresidentWrapper = document.getElementById('currentPresidentImageWrapper');
    
    if (presidentImageInput) {
        presidentImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            const existingError = this.parentElement.querySelector('.invalid-feedback');
            if (existingError) existingError.remove();
            
            if (file) {
                const validationError = validateImageFile(file);
                if (validationError) {
                    showFloatingToast(validationError, 'error');
                    this.value = '';
                    if (presidentPreviewContainer) presidentPreviewContainer.style.display = 'none';
                    if (currentPresidentWrapper) currentPresidentWrapper.style.display = 'block';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (presidentPreviewImg) presidentPreviewImg.src = event.target.result;
                    if (presidentPreviewContainer) presidentPreviewContainer.style.display = 'block';
                    if (currentPresidentWrapper) currentPresidentWrapper.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                if (presidentPreviewContainer) presidentPreviewContainer.style.display = 'none';
                if (presidentPreviewImg) presidentPreviewImg.src = '';
                if (currentPresidentWrapper) currentPresidentWrapper.style.display = 'block';
            }
        });
    }
    
    // Setup image preview for company image
    const companyImageInput = document.getElementById('company_profile_image');
    const companyPreviewContainer = document.getElementById('companyImagePreviewContainer');
    const companyPreviewImg = document.getElementById('companyImagePreview');
    let currentCompanyWrapper = document.getElementById('currentCompanyImageWrapper');
    
    if (companyImageInput) {
        companyImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            const existingError = this.parentElement.querySelector('.invalid-feedback');
            if (existingError) existingError.remove();
            
            if (file) {
                const validationError = validateImageFile(file);
                if (validationError) {
                    showFloatingToast(validationError, 'error');
                    this.value = '';
                    if (companyPreviewContainer) companyPreviewContainer.style.display = 'none';
                    if (currentCompanyWrapper) currentCompanyWrapper.style.display = 'block';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (companyPreviewImg) companyPreviewImg.src = event.target.result;
                    if (companyPreviewContainer) companyPreviewContainer.style.display = 'block';
                    if (currentCompanyWrapper) currentCompanyWrapper.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                if (companyPreviewContainer) companyPreviewContainer.style.display = 'none';
                if (companyPreviewImg) companyPreviewImg.src = '';
                if (currentCompanyWrapper) currentCompanyWrapper.style.display = 'block';
            }
        });
    }
    
    // Remove new image preview buttons
    document.querySelectorAll('.remove-new-image-preview').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.target;
            const fileInput = document.getElementById(target);
            if (fileInput) {
                fileInput.value = '';
            }
            
            const previewContainer = document.getElementById(`${target === 'president_image' ? 'presidentImagePreviewContainer' : 'companyImagePreviewContainer'}`);
            if (previewContainer) previewContainer.style.display = 'none';
            
            if (target === 'president_image') {
                const currentWrapper = document.getElementById('currentPresidentImageWrapper');
                if (currentWrapper) currentWrapper.style.display = 'block';
            } else {
                const currentWrapper = document.getElementById('currentCompanyImageWrapper');
                if (currentWrapper) currentWrapper.style.display = 'block';
            }
            
            showFloatingToast('Image selection removed', 'info');
        });
    });
    
    // ============================================
    // Auto-expand textareas
    // ============================================
    
    function autoExpandTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 300) + 'px';
    }
    
    document.querySelectorAll('.auto-expand').forEach(textarea => {
        autoExpandTextarea(textarea);
        textarea.addEventListener('input', function() {
            autoExpandTextarea(this);
        });
    });
    
    // ============================================
    // Section edit/save functionality
    // ============================================
    
    // Edit button click
    document.querySelectorAll('.edit-section-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const section = this.dataset.section;
            const form = document.getElementById(`${section}-form`);
            const inputs = form.querySelectorAll('input, textarea, select');
            const saveBtn = document.querySelector(`.save-section-btn[data-section="${section}"]`);
            const editBtn = this;
            const addCategoryBtn = document.querySelector('.add-category-btn');
            
            // Enable all inputs
            inputs.forEach(input => {
                if (input.type !== 'file') {
                    input.disabled = false;
                } else {
                    input.disabled = false;
                }
            });
            
            // Show remove buttons when in edit mode
            updateRemoveButtonsState(true, section);
            
            // Show add category button for company section
            if (section === 'company' && addCategoryBtn) {
                addCategoryBtn.style.display = 'inline-block';
            }
            
            // Show remove category buttons
            if (section === 'company') {
                const removeCategoryBtns = form.querySelectorAll('.remove-category-btn');
                removeCategoryBtns.forEach(btn => {
                    btn.style.display = 'block';
                });
            }
            
            // Hide edit button, show save button
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        });
    });
    
    // Helper function to update President section dynamically (NO PAGE REFRESH)
    function updatePresidentSectionDynamically(data) {
        // Update president image
        const wrapper = document.getElementById('currentPresidentImageWrapper');
        if (wrapper) {
            if (data.president_image) {
                wrapper.innerHTML = `
                    <div class="current-image-wrapper" id="currentPresidentImage">
                        <img src="${data.president_image}?t=${Date.now()}" alt="President" class="img-thumbnail" style="max-height: 150px;">
                        <button type="button" class="btn btn-sm btn-danger remove-image-btn" data-image-type="president" style="position: absolute; top: 5px; right: 5px; display: none;" disabled>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                wrapper.style.display = 'block';
            } else {
                wrapper.innerHTML = `
                    <div class="image-placeholder" id="presidentImagePlaceholder" style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        <i class="fas fa-user-tie fa-3x text-muted mb-2"></i>
                        <span class="text-muted small">No Image</span>
                    </div>
                `;
                wrapper.style.display = 'block';
            }
        }
        
        // Update president name
        if (data.president_name !== undefined) {
            const nameInput = document.querySelector('#president-form input[name="president_name"]');
            if (nameInput) nameInput.value = data.president_name;
        }
        
        // Update president title
        if (data.president_title !== undefined) {
            const titleInput = document.querySelector('#president-form input[name="president_title"]');
            if (titleInput) titleInput.value = data.president_title;
        }
        
        // Update president message
        if (data.president_message !== undefined) {
            const messageTextarea = document.querySelector('#president-form textarea[name="president_message"]');
            if (messageTextarea) messageTextarea.value = data.president_message;
        }
    }
    
// Helper function to update Company section dynamically (NO PAGE REFRESH)
function updateCompanySectionDynamically(data) {
    // Update company image
    const wrapper = document.getElementById('currentCompanyImageWrapper');
    if (wrapper) {
        if (data.company_profile_image) {
            wrapper.innerHTML = `
                <div class="current-image-wrapper" id="currentCompanyImage">
                    <img src="${data.company_profile_image}?t=${Date.now()}" alt="Company" class="img-thumbnail" style="max-height: 150px;">
                    <button type="button" class="btn btn-sm btn-danger remove-image-btn" data-image-type="company" style="position: absolute; top: 5px; right: 5px; display: none;" disabled>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            wrapper.style.display = 'block';
        } else {
            wrapper.innerHTML = `
                <div class="image-placeholder" id="companyImagePlaceholder" style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                    <i class="fas fa-building fa-3x text-muted mb-2"></i>
                    <span class="text-muted small">No Image</span>
                </div>
            `;
            wrapper.style.display = 'block';
        }
    }
    
    // Update company name
    if (data.company_name !== undefined) {
        const companyNameInput = document.querySelector('#company-form input[name="company_name"]');
        if (companyNameInput) companyNameInput.value = data.company_name;
    }
    
    // Update company profile
    if (data.company_profile !== undefined) {
        const profileTextarea = document.querySelector('#company-form textarea[name="company_profile"]');
        if (profileTextarea) profileTextarea.value = data.company_profile;
    }
    
    // Update standard categories
    const standardCategories = ['established_date', 'capital', 'president_representative', 'business_description', 'employees'];
    standardCategories.forEach(category => {
        if (data[category] !== undefined) {
            const input = document.querySelector(`#company-form input[name="${category}"], #company-form textarea[name="${category}"]`);
            if (input) input.value = data[category];
        }
    });
    
    // Update dynamic categories - preserve the labels
    if (data.dynamic_categories) {
        // Get all existing category items to preserve their labels
        const existingItems = document.querySelectorAll('.category-item');
        
        Object.keys(data.dynamic_categories).forEach(key => {
            // Find the input field for this category
            const input = document.querySelector(`#company-form input[name="${key}"], #company-form textarea[name="${key}"]`);
            if (input) {
                input.value = data.dynamic_categories[key];
            } else {
                // If input doesn't exist (new category), we need to keep the one already added via JS
                // The category item was already created in the DOM, just update its value
                const existingInput = document.querySelector(`.category-item[data-category-key="${key}"] input, .category-item[data-category-key="${key}"] textarea`);
                if (existingInput) {
                    existingInput.value = data.dynamic_categories[key];
                }
            }
        });
    }
}
    
    // Save button click - ENHANCED VERSION with dynamic updates (no page reload)
    document.querySelectorAll('.save-section-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const section = this.dataset.section;
            const form = document.getElementById(`${section}-form`);
            const formData = new FormData(form);
            const editBtn = document.querySelector(`.edit-section-btn[data-section="${section}"]`);
            const saveBtn = this;
            const addCategoryBtn = document.querySelector('.add-category-btn');
            
            // Add section identifier
            formData.append('section', section);
            
            // Validate files before upload
            let validationError = null;
            
            if (section === 'president') {
                const presidentFile = document.getElementById('president_image')?.files[0];
                if (presidentFile) {
                    validationError = validateImageFile(presidentFile);
                    if (validationError) {
                        showFloatingToast(validationError, 'error');
                        document.getElementById('president_image').value = '';
                        const previewContainer = document.getElementById('presidentImagePreviewContainer');
                        if (previewContainer) previewContainer.style.display = 'none';
                        const currentWrapper = document.getElementById('currentPresidentImageWrapper');
                        if (currentWrapper) currentWrapper.style.display = 'block';
                        return;
                    }
                }
            } else if (section === 'company') {
                const companyFile = document.getElementById('company_profile_image')?.files[0];
                if (companyFile) {
                    validationError = validateImageFile(companyFile);
                    if (validationError) {
                        showFloatingToast(validationError, 'error');
                        document.getElementById('company_profile_image').value = '';
                        const previewContainer = document.getElementById('companyImagePreviewContainer');
                        if (previewContainer) previewContainer.style.display = 'none';
                        const currentWrapper = document.getElementById('currentCompanyImageWrapper');
                        if (currentWrapper) currentWrapper.style.display = 'block';
                        return;
                    }
                }
            }
            
            // Collect removed categories
            const removedCategories = [];
            document.querySelectorAll('input[name="removed_categories[]"]').forEach(input => {
                removedCategories.push(input.value);
            });
            if (removedCategories.length > 0) {
                formData.append('removed_categories', JSON.stringify(removedCategories));
            }
            
            // Store original button text and disable button
            const originalText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
            
            fetch('{{ route("admin.overview.updateSection") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 500));
                    throw new Error('Server returned an invalid response');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showFloatingToast(data.message, 'success');

                           if (data.reload) {
    showFloatingToast(data.message + ' Refreshing page...', 'success');
    setTimeout(() => {
        window.location.href = window.location.href; // Force reload current page
    }, 1500);
    return;
}
                    
                    // DYNAMIC UPDATE WITHOUT PAGE RELOAD
                    if (section === 'president' && data.data) {
                        updatePresidentSectionDynamically(data.data);
                    } else if (section === 'company' && data.data) {
                        updateCompanySectionDynamically(data.data);
                    }
                    
                    // Disable all inputs again
                    const inputs = form.querySelectorAll('input, textarea, select');
                    inputs.forEach(input => {
                        if (input.type !== 'file') {
                            input.disabled = true;
                        } else {
                            input.disabled = true;
                        }
                    });
                    
                    // Hide remove buttons after saving
                    updateRemoveButtonsState(false, section);
                    
                    // Clear any file inputs and previews
                    if (section === 'president') {
                        const presidentInput = document.getElementById('president_image');
                        if (presidentInput) presidentInput.value = '';
                        const previewContainer = document.getElementById('presidentImagePreviewContainer');
                        if (previewContainer) previewContainer.style.display = 'none';
                    } else if (section === 'company') {
                        const companyInput = document.getElementById('company_profile_image');
                        if (companyInput) companyInput.value = '';
                        const previewContainer = document.getElementById('companyImagePreviewContainer');
                        if (previewContainer) previewContainer.style.display = 'none';
                    }
                    
                    // Hide add category button
                    if (section === 'company' && addCategoryBtn) {
                        addCategoryBtn.style.display = 'none';
                    }
                    
                    // Hide remove category buttons
                    if (section === 'company') {
                        const removeCategoryBtns = form.querySelectorAll('.remove-category-btn');
                        removeCategoryBtns.forEach(btn => {
                            btn.style.display = 'none';
                        });
                    }
                    
                    // Show edit button, hide save button
                    editBtn.style.display = 'inline-block';
                    saveBtn.style.display = 'none';
                    
                    // Remove removed_categories inputs after successful save
                    document.querySelectorAll('input[name="removed_categories[]"]').forEach(input => input.remove());
                    
                } else {
                    showFloatingToast(data.message || 'Failed to save changes', 'error');
                }
                
                // Reset save button state
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            })
            .catch(error => {
                console.error('Error:', error);
                showFloatingToast(error.message || 'An error occurred while saving', 'error');
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            });
        });
    });
    
    // ============================================
    // Remove image functionality (AJAX with dynamic update - no refresh needed)
    // ============================================
    
    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-image-btn');
        if (removeBtn && !removeBtn.disabled) {
            e.preventDefault();
            e.stopPropagation();
            
            if (confirm('Are you sure you want to remove this image?')) {
                const imageType = removeBtn.dataset.imageType;
                const formData = new FormData();
                formData.append('image_type', imageType);
                formData.append('_token', csrfToken);
                
                // Store reference to elements
                const parentWrapper = document.getElementById(`${imageType === 'president' ? 'currentPresidentImageWrapper' : 'currentCompanyImageWrapper'}`);
                
                // Disable the remove button temporarily to prevent double submission
                removeBtn.disabled = true;
                removeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch('{{ route("admin.overview.removeImage") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Immediately update UI without refresh
                        if (parentWrapper) {
                            const iconClass = imageType === 'president' ? 'fa-user-tie' : 'fa-building';
                            const placeholderHtml = `
                                <div class="image-placeholder" id="${imageType}ImagePlaceholder" style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                    <i class="fas ${iconClass} fa-3x text-muted mb-2"></i>
                                    <span class="text-muted small">No Image</span>
                                </div>
                            `;
                            parentWrapper.innerHTML = placeholderHtml;
                            parentWrapper.style.display = 'block';
                        }
                        showFloatingToast(data.message, 'success');
                    } else {
                        showFloatingToast(data.message || 'Failed to remove image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showFloatingToast('An error occurred while removing the image', 'error');
                })
                .finally(() => {
                    // Re-enable the remove button
                    removeBtn.disabled = false;
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                });
            }
        }
    });
    
    // Initialize remove buttons state (hidden by default, not in edit mode)
    updateRemoveButtonsState(false, 'president');
    updateRemoveButtonsState(false, 'company');
    
    // ============================================
    // Add new category functionality
    // ============================================
    
    document.querySelector('.add-category-btn')?.addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
    });
    
    // Replace the existing 'save-category-btn' click handler with this:

document.getElementById('save-category-btn')?.addEventListener('click', function() {
    const form = document.getElementById('add-category-form');
    const categoryKey = form.querySelector('[name="category_key"]').value.trim();
    const categoryLabel = form.querySelector('[name="category_label"]').value.trim();
    const categoryIcon = form.querySelector('[name="category_icon"]').value.trim() || 'fa-tag';
    const fieldType = form.querySelector('[name="field_type"]').value;
    const initialValue = form.querySelector('[name="initial_value"]').value;
    
    if (!categoryKey || !categoryLabel) {
        showFloatingToast('Please fill in all required fields', 'error');
        return;
    }
    
    // Validate category key format
    if (!/^[a-z_]+$/.test(categoryKey)) {
        showFloatingToast('Category key must be lowercase with underscores only', 'error');
        return;
    }
    
    // Check if category already exists in DOM or database
    if (document.querySelector(`.category-item[data-category-key="${categoryKey}"]`)) {
        showFloatingToast('Category with this key already exists', 'error');
        return;
    }
    
    // Create FormData for AJAX request
    const saveData = new FormData();
    saveData.append('section', 'company');
    saveData.append('category_key', categoryKey);
    saveData.append('category_label', categoryLabel);
    saveData.append('category_icon', categoryIcon);
    saveData.append('field_type', fieldType);
    saveData.append('initial_value', initialValue);
    saveData.append('_token', csrfToken);
    
    // Store original button text and disable
    const saveBtn = this;
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    
    // Send AJAX request to save category directly
    fetch('{{ route("admin.overview.addCategory") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: saveData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showFloatingToast(data.message, 'success');
            
            // Add the category to the DOM
            const container = document.getElementById('dynamic-categories-container');
            const categoryHtml = `
                <div class="category-item mb-3" data-category-key="${categoryKey}">
                    <div class="d-flex align-items-start gap-2">
                        <div class="flex-grow-1">
                            <label class="form-label">
                                <i class="fas ${categoryIcon} me-1"></i>${escapeHtml(categoryLabel)}
                            </label>
                            ${fieldType === 'textarea' ? 
                                `<textarea class="form-control auto-expand" name="${categoryKey}" rows="3" disabled>${escapeHtml(initialValue)}</textarea>` :
                                fieldType === 'number' ?
                                `<input type="number" class="form-control" name="${categoryKey}" value="${escapeHtml(initialValue)}" disabled>` :
                                `<input type="text" class="form-control" name="${categoryKey}" value="${escapeHtml(initialValue)}" disabled>`
                            }
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-category-btn mt-4" data-category-key="${categoryKey}" style="display: none;">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', categoryHtml);
            
            // Auto-expand new textarea if any
            const newTextarea = container.querySelector(`.category-item[data-category-key="${categoryKey}"] textarea`);
            if (newTextarea) {
                newTextarea.classList.add('auto-expand');
                autoExpandTextarea(newTextarea);
                newTextarea.addEventListener('input', function() { autoExpandTextarea(this); });
            }
            
            // If we're in edit mode, enable the new input
            const editBtn = document.querySelector('.edit-section-btn[data-section="company"]');
            if (editBtn && editBtn.style.display === 'none') {
                const newInput = container.querySelector(`.category-item[data-category-key="${categoryKey}"] input, .category-item[data-category-key="${categoryKey}"] textarea`);
                if (newInput) {
                    newInput.disabled = false;
                }
                const removeBtn = container.querySelector(`.remove-category-btn[data-category-key="${categoryKey}"]`);
                if (removeBtn) {
                    removeBtn.style.display = 'block';
                }
            }
            
            // Hide the modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
            form.reset();
        } else {
            showFloatingToast(data.message || 'Failed to add category', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showFloatingToast('An error occurred while adding the category', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
});
    
    // ============================================
    // Remove category functionality
    // ============================================
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-category-btn')) {
            const btn = e.target.closest('.remove-category-btn');
            const categoryKey = btn.dataset.categoryKey;
            const categoryItem = document.querySelector(`.category-item[data-category-key="${categoryKey}"]`);
            
            if (confirm(`Are you sure you want to remove this category?`)) {
                // Mark for removal
                const removalInput = document.createElement('input');
                removalInput.type = 'hidden';
                removalInput.name = 'removed_categories[]';
                removalInput.value = categoryKey;
                document.getElementById('company-form').appendChild(removalInput);
                
                // Animate and remove
                categoryItem.classList.add('category-item-removing');
                setTimeout(() => {
                    categoryItem.remove();
                    showFloatingToast('Category marked for removal. Click Save Changes to confirm.', 'info');
                }, 300);
            }
        }
    });
    
   // ============================================
// Business Principles CRUD with AJAX (No Page Reload)
// ============================================

// Helper function to generate consistent icon for principles
function getPrincipleIcon(index) {
    const icons = ['briefcase', 'chart-line', 'handshake', 'globe', 'users', 'medal'];
    return icons[index % icons.length];
}

// Function to create principle HTML for the list
function createPrincipleHTML(principle, index) {
    const icon = getPrincipleIcon(index);
    return `
        <div class="principle-item card mb-3" data-id="${principle.id}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="principle-title">${escapeHtml(principle.title || '')}</h5>
                        <p class="principle-description text-muted">${escapeHtml(principle.description)}</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-warning edit-principle me-2" 
                            data-id="${principle.id}"
                            data-title="${escapeHtml(principle.title || '')}"
                            data-description="${escapeHtml(principle.description)}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-principle" 
                            data-id="${principle.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Add Principle - AJAX with dynamic update
document.getElementById('save-principle-btn')?.addEventListener('click', function() {
    const form = document.getElementById('add-principle-form');
    const title = form.querySelector('input[name="title"]').value;
    const description = form.querySelector('textarea[name="description"]').value;
    
    if (!description.trim()) {
        showFloatingToast('Please enter a description', 'error');
        return;
    }
    
    const formData = new FormData(form);
    const saveBtn = this;
    const originalText = saveBtn.innerHTML;
    
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    
    fetch('{{ route("admin.overview.addPrinciple") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showFloatingToast(data.message, 'success');
            
            // Get current principles count for icon index
            const principlesList = document.getElementById('business-principles-list');
            const currentPrinciples = principlesList.querySelectorAll('.principle-item');
            const newIndex = currentPrinciples.length;
            
            // Create new principle HTML
            const newPrincipleHTML = createPrincipleHTML(data.principle, newIndex);
            
            // Remove "no principles" alert if exists
            const alertInfo = principlesList.querySelector('.alert-info');
            if (alertInfo) {
                alertInfo.remove();
            }
            
            // Append new principle
            principlesList.insertAdjacentHTML('beforeend', newPrincipleHTML);
            
            // Add event listeners to new buttons
            const newPrinciple = principlesList.lastElementChild;
            attachPrincipleEventListeners(newPrinciple);
            
            // Reset and close modal
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addPrincipleModal')).hide();
        } else {
            showFloatingToast(data.message || 'Failed to add principle', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showFloatingToast('An error occurred', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
});

// Function to attach event listeners to principle buttons
function attachPrincipleEventListeners(principleElement) {
    // Edit button
    const editBtn = principleElement.querySelector('.edit-principle');
    if (editBtn) {
        editBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const title = this.dataset.title;
            const description = this.dataset.description;
            
            document.getElementById('edit_principle_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            
            new bootstrap.Modal(document.getElementById('editPrincipleModal')).show();
        });
    }
    
    // Delete button
    const deleteBtn = principleElement.querySelector('.delete-principle');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (confirm('Are you sure you want to delete this business principle?')) {
                const id = this.dataset.id;
                const principleItem = document.querySelector(`.principle-item[data-id="${id}"]`);
                
                fetch(`{{ url('admin/overview/business-principle') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showFloatingToast(data.message, 'success');
                        // Animate and remove
                        principleItem.style.transition = 'all 0.3s ease';
                        principleItem.style.opacity = '0';
                        principleItem.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            principleItem.remove();
                            
                            // If no principles left, show alert
                            const principlesList = document.getElementById('business-principles-list');
                            if (principlesList.children.length === 0) {
                                principlesList.innerHTML = '<div class="alert alert-info">No business principles added yet. Click "Add Principle" to get started.</div>';
                            } else {
                                // Re-index remaining principles to update icons
                                const remainingPrinciples = principlesList.querySelectorAll('.principle-item');
                                remainingPrinciples.forEach((item, newIndex) => {
                                    const principleId = item.dataset.id;
                                    const titleElem = item.querySelector('.principle-title');
                                    const descElem = item.querySelector('.principle-description');
                                    const icon = getPrincipleIcon(newIndex);
                                    // Icons are in the guest view, not in admin - so no need to update icons here
                                });
                            }
                        }, 300);
                    } else {
                        showFloatingToast('Failed to delete principle', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showFloatingToast('An error occurred', 'error');
                });
            }
        });
    }
}
    
// Update Principle - AJAX with dynamic update
document.getElementById('update-principle-btn')?.addEventListener('click', function() {
    const id = document.getElementById('edit_principle_id').value;
    const form = document.getElementById('edit-principle-form');
    const title = form.querySelector('input[name="title"]').value;
    const description = form.querySelector('textarea[name="description"]').value;
    
    if (!description.trim()) {
        showFloatingToast('Please enter a description', 'error');
        return;
    }
    
    const formData = new FormData(form);
    const updateBtn = this;
    const originalText = updateBtn.innerHTML;
    
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
    
    fetch(`{{ url('admin/overview/business-principle') }}/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showFloatingToast(data.message, 'success');
            
            // Update the principle in the DOM
            const principleItem = document.querySelector(`.principle-item[data-id="${id}"]`);
            if (principleItem && data.principle) {
                const titleElem = principleItem.querySelector('.principle-title');
                const descElem = principleItem.querySelector('.principle-description');
                
                if (titleElem) titleElem.textContent = data.principle.title || '';
                if (descElem) descElem.textContent = data.principle.description;
                
                // Update data attributes on edit button
                const editBtn = principleItem.querySelector('.edit-principle');
                if (editBtn) {
                    editBtn.dataset.title = data.principle.title || '';
                    editBtn.dataset.description = data.principle.description;
                }
            }
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('editPrincipleModal')).hide();
        } else {
            showFloatingToast(data.message || 'Failed to update principle', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showFloatingToast('An error occurred', 'error');
    })
    .finally(() => {
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
    });
});

// Attach event listeners to existing principles on page load
document.querySelectorAll('.principle-item').forEach(principle => {
    attachPrincipleEventListeners(principle);
});
    
    document.querySelectorAll('.delete-principle')?.forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this business principle?')) {
                const id = this.dataset.id;
                
                fetch(`{{ url('admin/overview/business-principle') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showFloatingToast(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showFloatingToast('Failed to delete principle', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showFloatingToast('An error occurred', 'error');
                });
            }
        });
    });
});
</script>