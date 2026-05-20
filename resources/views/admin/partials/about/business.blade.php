{{-- resources/views/admin/partials/about/business.blade.php --}}
<div class="card content-card">
    <div class="card-header">
        <h5><i class="fas fa-chart-line me-2"></i>Manage Business Introduction Content</h5>
    </div>
    <div class="card-body">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="businessTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="automotive-tab" data-bs-toggle="tab" data-bs-target="#automotive" type="button" role="tab">
                    <i class="fas fa-car"></i> Products & Services
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="organization-tab" data-bs-toggle="tab" data-bs-target="#organization" type="button" role="tab">
                    <i class="fas fa-users"></i> Organization Structure
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="characteristics-tab" data-bs-toggle="tab" data-bs-target="#characteristics" type="button" role="tab">
                    <i class="fas fa-chart-bar"></i> Characteristics
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="partnership-tab" data-bs-toggle="tab" data-bs-target="#partnership" type="button" role="tab">
                    <i class="fas fa-handshake"></i> Partnership
                </button>
            </li>
        </ul>

        <div class="tab-content mt-4" id="businessTabContent">
            <!-- Automotive Section -->
            <div class="tab-pane fade show active" id="automotive" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Products & Services</h4>
                    <button class="btn btn-primary" onclick="showAddAutomotiveModal()">
                        <i class="fas fa-plus"></i> Add New Item
                    </button>
                </div>
                <div id="automotive-list">
                   @foreach($automotive as $item)
    <div class="card mb-3" data-id="{{ $item->id }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    @if($item->image)
                        <img src="{{ $item->image_url }}" class="img-fluid rounded" alt="{{ $item->title }}">
                    @else
                        <div class="bg-light text-center p-4 rounded">No Image</div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h5>{{ $item->title }}</h5>
                    <p>{{ Str::limit($item->description, 100) }}</p>
                    <button class="btn btn-sm btn-warning" onclick="editAutomotive({{ $item->id }})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $item->id }})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
                </div>
            </div>

 {{-- In admin business.blade.php - Organization Section --}}
<!-- Organization Section -->
<div class="tab-pane fade" id="organization" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Organization Charts</h4>
        <button class="btn btn-primary" onclick="showAddOrganizationModal()">
            <i class="fas fa-plus"></i> Add Organization Chart
        </button>
    </div>
    <div class="row" id="organization-list">
        @foreach($organizations as $member)
            <div class="col-md-6 mb-4" data-id="{{ $member->id }}">
                <div class="card h-100">
                    @if($member->image)
                        <img src="{{ $member->image_url }}" class="card-img-top" alt="{{ $member->title ?? 'Organization Chart' }}" style="width: 100%; object-fit: contain; padding: 20px;">
                    @else
                        <div class="bg-light text-center p-5">
                            <i class="fas fa-building fa-4x text-muted"></i>
                            <p class="mt-2 text-muted">No Image Uploaded</p>
                        </div>
                    @endif
                    <div class="card-body text-center">
                        @if($member->title)
                            <h5>{{ $member->title }}</h5>
                        @else
                            <h5 class="text-muted">Organization Chart</h5>
                        @endif
                        <button class="btn btn-sm btn-warning" onclick="editOrganization({{ $member->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $member->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

            <!-- Characteristics Section -->
<div class="tab-pane fade" id="characteristics" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Business Characteristics</h4>
        <button class="btn btn-primary" onclick="showAddCharacteristicModal()">
            <i class="fas fa-plus"></i> Add Characteristic
        </button>
    </div>
    <div id="characteristics-list">
        @foreach($characteristics as $char)
        <div class="card mb-3" data-id="{{ $char->id }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 d-flex align-items-center">
                        @if($char->image)
                            <img src="{{ $char->image_url }}" class="img-fluid rounded w-100" alt="{{ $char->title }}" style="object-fit: cover; height: 100%; min-height: 150px;">
                        @else
                            <div class="bg-light text-center p-4 rounded w-100">No Image</div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h5>{{ $char->title }}</h5>
                        @if($char->subtitle)
                            <h6 class="text-muted">{{ $char->subtitle }}</h6>
                        @endif
                        <p>{{ Str::limit($char->description, 100) }}</p>
                        <button class="btn btn-sm btn-warning" onclick="editCharacteristic({{ $char->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $char->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

            <!-- Partnership Section -->
            <div class="tab-pane fade" id="partnership" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Partnerships</h4>
                    <button class="btn btn-primary" onclick="showAddPartnershipModal()">
                        <i class="fas fa-plus"></i> Add Partnership
                    </button>
                </div>
                <div class="row" id="partnership-list">
                   @foreach($partnerships as $partner)
    <div class="col-md-3 mb-4" data-id="{{ $partner->id }}">
        <div class="card h-100 text-center">
            @if($partner->image)
                <img src="{{ $partner->image_url }}" class="card-img-top p-3" alt="{{ $partner->title }}" style="height: 150px; object-fit: contain;">
            @endif
            <div class="card-body">
                <h6>{{ Str::limit($partner->title, 100) }}</h6>
                <button class="btn btn-sm btn-warning mt-2" onclick="editPartnership({{ $partner->id }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger mt-2" onclick="deleteItem({{ $partner->id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
@endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('admin.partials.about.business-modals')

<script>
// Replace your entire script section (from document.addEventListener to the end)
// with this corrected code:

document.addEventListener('DOMContentLoaded', function() {
    // Check if this is first visit or if we should use saved preference
    const lastTab = localStorage.getItem('lastBusinessTab');
    
    if (!lastTab || lastTab === 'automotive') {
        // Force automotive tab to be active
        const automotiveTab = document.querySelector('#automotive-tab');
        const automotivePane = document.querySelector('#automotive');
        
        if (automotiveTab && automotivePane) {
            document.querySelectorAll('#businessTab .nav-link').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            automotiveTab.classList.add('active');
            automotivePane.classList.add('show', 'active');
        }
    }
    
    // Save current tab when clicked
    document.querySelectorAll('#businessTab button').forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            const targetId = e.target.getAttribute('data-bs-target');
            localStorage.setItem('lastBusinessTab', targetId);
        });
    });
    
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#businessTab button'));
    triggerTabList.forEach(function(triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});

let currentEditId = null;
let currentSection = null;

// ============================================
// TOAST NOTIFICATION SYSTEM
// ============================================
function showToast(message, type = 'success') {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `floating-toast ${type}-toast`;
    
    // Set icon based on type
    let icon = 'fa-circle-check';
    if (type === 'error') icon = 'fa-circle-exclamation';
    if (type === 'warning') icon = 'fa-exclamation-triangle';
    if (type === 'info') icon = 'fa-info-circle';
    
    let bgColor, borderColor, textColor;
    switch(type) {
        case 'success':
            bgColor = '#d4edda';
            borderColor = '#28a745';
            textColor = '#155724';
            break;
        case 'error':
            bgColor = '#f8d7da';
            borderColor = '#dc3545';
            textColor = '#721c24';
            break;
        case 'warning':
            bgColor = '#fff3cd';
            borderColor = '#ffc107';
            textColor = '#856404';
            break;
        case 'info':
            bgColor = '#d1ecf1';
            borderColor = '#17a2b8';
            textColor = '#0c5460';
            break;
        default:
            bgColor = '#d4edda';
            borderColor = '#28a745';
            textColor = '#155724';
    }
    
    toast.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 6px; background-color: ${bgColor}; border-left: 3px solid ${borderColor}; color: ${textColor};">
            <i class="fas ${icon}" style="font-size: 18px;"></i>
            <span style="font-size: 13px; line-height: 1.4;">${message}</span>
            <button type="button" class="toast-close" style="background: none; border: none; margin-left: auto; cursor: pointer; color: inherit; opacity: 0.5; font-size: 20px; padding: 0 5px;" onclick="this.closest('.floating-toast').remove()">×</button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast && toast.parentNode) {
            toast.style.animation = 'slideOut 0.3s ease-in forwards';
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }
    }, 5000);
}

// Helper function for AJAX requests
async function makeRequest(url, method, formData) {
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Something went wrong');
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message, 'error');
        throw error;
    }
}

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// ============================================
// AUTOMOTIVE FUNCTIONS
// ============================================
function showAddAutomotiveModal() {
    currentSection = 'automotive';
    const modalLabel = document.getElementById('automotiveModalLabel');
    const form = document.getElementById('automotiveForm');
    const idField = document.getElementById('automotiveId');
    const container = document.getElementById('automotiveCurrentImageContainer');
    
    if (modalLabel) modalLabel.textContent = 'Add New Product/Service';
    if (form) form.reset();
    if (idField) idField.value = '';
    if (container) container.style.display = 'none';
    currentEditId = null;
    
    const modalElement = document.getElementById('automotiveModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }
}

async function editAutomotive(id) {
    currentSection = 'automotive';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        const modalLabel = document.getElementById('automotiveModalLabel');
        const idField = document.getElementById('automotiveId');
        const titleField = document.getElementById('automotive_title');
        const descField = document.getElementById('automotive_description');
        
        if (modalLabel) modalLabel.textContent = 'Edit Product/Service';
        if (idField) idField.value = data.id;
        if (titleField) titleField.value = data.title;
        if (descField) descField.value = data.description;
        currentEditId = data.id;
        
        // Show and update current image if exists
        const currentImageContainer = document.getElementById('automotiveCurrentImageContainer');
        const currentImage = document.getElementById('automotiveCurrentImage');
        const currentImageName = document.getElementById('automotiveCurrentImageName');
        
        if (currentImageContainer && currentImage && currentImageName) {
            if (data.image_url) {
                currentImage.src = data.image_url;
                const filename = data.image ? data.image.split('/').pop() : 'image';
                currentImageName.textContent = `Current: ${data.display_filename || (data.image ? data.image.split('/').pop() : 'No image')}`;
                currentImageContainer.style.display = 'block';
            } else {
                currentImageContainer.style.display = 'none';
            }
        }
        
        const modalElement = document.getElementById('automotiveModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            modal.show();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading data', 'error');
    }
}

// Automotive Form Handler - FIXED with null checks
const automotiveForm = document.getElementById('automotiveForm');
if (automotiveForm) {
    automotiveForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Check if form elements exist
        const idField = document.getElementById('automotiveId');
        const titleField = document.getElementById('automotive_title');
        const descField = document.getElementById('automotive_description');
        
        if (!titleField || !descField) {
            showToast('Form elements not found', 'error');
            return;
        }
        
        let formData = new FormData(this);
        let url = currentEditId ? `/admin/business-content/automotive/${currentEditId}` : '/admin/business-content/automotive';
        
        if (currentEditId) {
            formData.append('_method', 'PUT');
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : 'Save';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        }
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    showToast(errorMessages, 'error');
                } else {
                    throw new Error(data.message || 'Error saving data');
                }
                return;
            }
            
            if (data.success) {
                const automotiveList = document.getElementById('automotive-list');
                if (automotiveList) {
                    if (currentEditId) {
                        // Update existing item
                        const itemElement = automotiveList.querySelector(`[data-id="${currentEditId}"]`);
                        if (itemElement && data.html) {
                            itemElement.outerHTML = data.html;
                        }
                    } else {
                        // Add new item
                        if (data.html) {
                            automotiveList.insertAdjacentHTML('beforeend', data.html);
                        }
                    }
                }
                
                showToast(data.message, 'success');
                
                // Close modal
                const modalElement = document.getElementById('automotiveModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                
                // Reset form
                this.reset();
                currentEditId = null;
            }
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}

// ============================================
// ORGANIZATION FUNCTIONS - FIXED
// ============================================
function showAddOrganizationModal() {
    currentSection = 'organization';
    const modalLabel = document.getElementById('organizationModalLabel');
    const form = document.getElementById('organizationForm');
    const idField = document.getElementById('organizationId');
    const container = document.getElementById('organizationCurrentImageContainer');
    
    if (modalLabel) modalLabel.textContent = 'Add Organization Chart';
    if (form) form.reset();
    if (idField) idField.value = '';
    if (container) container.style.display = 'none';
    currentEditId = null;
    
    const modalElement = document.getElementById('organizationModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }
}

async function editOrganization(id) {
    currentSection = 'organization';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        const modalLabel = document.getElementById('organizationModalLabel');
        const idField = document.getElementById('organizationId');
        const titleField = document.getElementById('organization_title');
        
        if (modalLabel) modalLabel.textContent = 'Edit Organization Chart';
        if (idField) idField.value = data.id;
        if (titleField) titleField.value = data.title || '';
        currentEditId = data.id;
        
        // Show and update current image if exists
        const currentImageContainer = document.getElementById('organizationCurrentImageContainer');
        const currentImage = document.getElementById('organizationCurrentImage');
        const currentImageName = document.getElementById('organizationCurrentImageName');
        
        if (currentImageContainer && currentImage && currentImageName) {
            if (data.image_url) {
                currentImage.src = data.image_url;
                currentImageName.textContent = `Current: ${data.display_filename || (data.image ? data.image.split('/').pop() : 'No image')}`;
                currentImageContainer.style.display = 'block';
            } else {
                currentImageContainer.style.display = 'none';
            }
        }
        
        const modalElement = document.getElementById('organizationModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            modal.show();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading data', 'error');
    }
}

// Organization Form Handler - FIXED
const organizationForm = document.getElementById('organizationForm');
if (organizationForm) {
    organizationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = currentEditId ? `/admin/business-content/organization/${currentEditId}` : '/admin/business-content/organization';
        
        if (currentEditId) {
            formData.append('_method', 'PUT');
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : 'Save';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        }
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    showToast(errorMessages, 'error');
                } else {
                    throw new Error(data.message || 'Error saving data');
                }
                return;
            }
            
            if (data.success) {
                const organizationList = document.getElementById('organization-list');
                if (organizationList) {
                    if (currentEditId) {
                        const itemElement = organizationList.querySelector(`[data-id="${currentEditId}"]`);
                        if (itemElement && data.html) {
                            itemElement.outerHTML = data.html;
                        }
                    } else {
                        if (data.html) {
                            organizationList.insertAdjacentHTML('beforeend', data.html);
                        }
                    }
                }
                
                showToast(data.message, 'success');
                
                const modalElement = document.getElementById('organizationModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                
                this.reset();
                currentEditId = null;
            }
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}

// ============================================
// CHARACTERISTIC FUNCTIONS
// ============================================
function showAddCharacteristicModal() {
    currentSection = 'characteristic';
    const modalLabel = document.getElementById('characteristicModalLabel');
    const form = document.getElementById('characteristicForm');
    const idField = document.getElementById('characteristicId');
    const subtitleField = document.getElementById('characteristic_subtitle'); 
    const container = document.getElementById('characteristicCurrentImageContainer');
    
    if (modalLabel) modalLabel.textContent = 'Add Characteristic';
    if (form) form.reset();
    if (idField) idField.value = '';
    if (subtitleField) subtitleField.value = ''; // NEW - Clear subtitle
    if (container) container.style.display = 'none';
    currentEditId = null;
    
    const modalElement = document.getElementById('characteristicModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }
}

async function editCharacteristic(id) {
    currentSection = 'characteristic';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        const modalLabel = document.getElementById('characteristicModalLabel');
        const idField = document.getElementById('characteristicId');
        const titleField = document.getElementById('characteristic_title');
        const subtitleField = document.getElementById('characteristic_subtitle'); // NEW
        const descField = document.getElementById('characteristic_description');
        
        if (modalLabel) modalLabel.textContent = 'Edit Characteristic';
        if (idField) idField.value = data.id;
        if (titleField) titleField.value = data.title;
        if (subtitleField) subtitleField.value = data.subtitle || ''; // NEW
        if (descField) descField.value = data.description;
        currentEditId = data.id;
        
        const currentImageContainer = document.getElementById('characteristicCurrentImageContainer');
        const currentImage = document.getElementById('characteristicCurrentImage');
        const currentImageName = document.getElementById('characteristicCurrentImageName');
        
        if (currentImageContainer && currentImage && currentImageName) {
            if (data.image_url) {
                currentImage.src = data.image_url;
                currentImageName.textContent = `Current: ${data.display_filename || (data.image ? data.image.split('/').pop() : 'No image')}`;
                currentImageContainer.style.display = 'block';
            } else {
                currentImageContainer.style.display = 'none';
            }
        }
        
        const modalElement = document.getElementById('characteristicModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            modal.show();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading data', 'error');
    }
}

// Characteristic Form Handler
const characteristicForm = document.getElementById('characteristicForm');
if (characteristicForm) {
    characteristicForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = currentEditId ? `/admin/business-content/characteristic/${currentEditId}` : '/admin/business-content/characteristic';
        
        if (currentEditId) {
            formData.append('_method', 'PUT');
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : 'Save';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        }
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    showToast(errorMessages, 'error');
                } else {
                    throw new Error(data.message || 'Error saving data');
                }
                return;
            }
            
            if (data.success) {
                const characteristicsList = document.getElementById('characteristics-list');
                if (characteristicsList) {
                    if (currentEditId) {
                        const itemElement = characteristicsList.querySelector(`[data-id="${currentEditId}"]`);
                        if (itemElement && data.html) {
                            itemElement.outerHTML = data.html;
                        }
                    } else {
                        if (data.html) {
                            characteristicsList.insertAdjacentHTML('beforeend', data.html);
                        }
                    }
                }
                
                showToast(data.message, 'success');
                
                const modalElement = document.getElementById('characteristicModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                
                this.reset();
                currentEditId = null;
            }
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}

// ============================================
// PARTNERSHIP FUNCTIONS
// ============================================
function showAddPartnershipModal() {
    currentSection = 'partnership';
    const modalLabel = document.getElementById('partnershipModalLabel');
    const form = document.getElementById('partnershipForm');
    const idField = document.getElementById('partnershipId');
    const container = document.getElementById('partnershipCurrentImageContainer');
    
    if (modalLabel) modalLabel.textContent = 'Add Partnership';
    if (form) form.reset();
    if (idField) idField.value = '';
    if (container) container.style.display = 'none';
    currentEditId = null;
    
    const modalElement = document.getElementById('partnershipModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }
}

async function editPartnership(id) {
    currentSection = 'partnership';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        const modalLabel = document.getElementById('partnershipModalLabel');
        const idField = document.getElementById('partnershipId');
        const titleField = document.getElementById('partnership_title');
        
        if (modalLabel) modalLabel.textContent = 'Edit Partnership';
        if (idField) idField.value = data.id;
        if (titleField) titleField.value = data.title;
        currentEditId = data.id;
        
        const currentImageContainer = document.getElementById('partnershipCurrentImageContainer');
        const currentImage = document.getElementById('partnershipCurrentImage');
        const currentImageName = document.getElementById('partnershipCurrentImageName');
        
        if (currentImageContainer && currentImage && currentImageName) {
            if (data.image_url) {
                currentImage.src = data.image_url;
                currentImageName.textContent = `Current: ${data.display_filename || (data.image ? data.image.split('/').pop() : 'No image')}`;
                currentImageContainer.style.display = 'block';
            } else {
                currentImageContainer.style.display = 'none';
            }
        }
        
        const modalElement = document.getElementById('partnershipModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            modal.show();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading data', 'error');
    }
}

// Partnership Form Handler
const partnershipForm = document.getElementById('partnershipForm');
if (partnershipForm) {
    partnershipForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = currentEditId ? `/admin/business-content/partnership/${currentEditId}` : '/admin/business-content/partnership';
        
        if (currentEditId) {
            formData.append('_method', 'PUT');
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : 'Save';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        }
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    showToast(errorMessages, 'error');
                } else {
                    throw new Error(data.message || 'Error saving data');
                }
                return;
            }
            
            if (data.success) {
                const partnershipList = document.getElementById('partnership-list');
                if (partnershipList) {
                    if (currentEditId) {
                        const itemElement = partnershipList.querySelector(`[data-id="${currentEditId}"]`);
                        if (itemElement && data.html) {
                            itemElement.outerHTML = data.html;
                        }
                    } else {
                        if (data.html) {
                            partnershipList.insertAdjacentHTML('beforeend', data.html);
                        }
                    }
                }
                
                showToast(data.message, 'success');
                
                const modalElement = document.getElementById('partnershipModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                
                this.reset();
                currentEditId = null;
            }
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}

// ============================================
// DELETE FUNCTION - FIXED
// ============================================
async function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        showToast('Deleting item...', 'info');
        
        try {
            const response = await fetch(`/admin/business-content/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Error deleting item');
            }
            
            if (data.success) {
                // Remove the item from DOM - search in all containers
                const itemElement = document.querySelector(`[data-id="${id}"]`);
                if (itemElement && itemElement.parentNode) {
                    itemElement.remove();
                }
                showToast(data.message, 'success');
            }
        } catch (error) {
            showToast(error.message, 'error');
        }
    }
}

// ============================================
// ADD CSS ANIMATIONS IF NOT EXISTS
// ============================================
(function addToastStyles() {
    if (!document.querySelector('#toast-animation-styles')) {
        const styleSheet = document.createElement('style');
        styleSheet.id = 'toast-animation-styles';
        styleSheet.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            .floating-toast {
                position: relative;
                margin-bottom: 10px;
                min-width: 250px;
                max-width: 350px;
                background: white;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                animation: slideIn 0.3s ease-out;
            }
            
            .floating-toast.hide {
                animation: slideOut 0.3s ease-in forwards;
            }
            
            .toast-close:hover {
                opacity: 0.8 !important;
            }
            
            .current-image-wrapper {
                background: #f8f9fa;
                padding: 10px;
                border-radius: 8px;
                text-align: center;
            }
        `;
        document.head.appendChild(styleSheet);
    }
})();

// Log that toast system is ready
console.log('Business management system loaded successfully');
</script>
