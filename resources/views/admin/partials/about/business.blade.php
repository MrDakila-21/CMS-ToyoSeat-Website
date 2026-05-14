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
                    <i class="fas fa-car"></i> Automotive Seat Cover
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
                    <h4>Automotive Seat Cover Items</h4>
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
                                            <img src="{{ Storage::url($item->image) }}" class="img-fluid rounded" alt="{{ $item->title }}">
                                        @else
                                            <div class="bg-light text-center p-4 rounded">No Image</div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5>{{ $item->title }}</h5>
                                        <p>{{ Str::limit($item->description, 200) }}</p>
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

            <!-- Organization Section -->
            <div class="tab-pane fade" id="organization" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Organization Members</h4>
                    <button class="btn btn-primary" onclick="showAddOrganizationModal()">
                        <i class="fas fa-plus"></i> Add Member
                    </button>
                </div>
                <div class="row" id="organization-list">
                    @foreach($organizations as $member)
                        <div class="col-md-4 mb-4" data-id="{{ $member->id }}">
                            <div class="card h-100">
                                @if($member->image)
                                    <img src="{{ Storage::url($member->image) }}" class="card-img-top" alt="{{ $member->name }}" style="height: 250px; object-fit: cover;">
                                @endif
                                <div class="card-body text-center">
                                    <h5>{{ $member->name }}</h5>
                                    <p class="text-muted">{{ $member->position }}</p>
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
                                    <div class="col-md-3">
                                        @if($char->image)
                                            <img src="{{ Storage::url($char->image) }}" class="img-fluid rounded" alt="{{ $char->title }}">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5>{{ $char->title }}</h5>
                                        <p>{{ $char->description }}</p>
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
                                    <img src="{{ Storage::url($partner->image) }}" class="card-img-top p-3" alt="{{ $partner->title }}" style="height: 150px; object-fit: contain;">
                                @endif
                                <div class="card-body">
                                    <h6>{{ $partner->title }}</h6>
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
// Make sure Bootstrap JS is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tabs if needed
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

// Helper function for AJAX requests
async function makeRequest(url, method, formData) {
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
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
        alert(error.message);
        throw error;
    }
}

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Automotive Functions
function showAddAutomotiveModal() {
    currentSection = 'automotive';
    document.getElementById('automotiveModalLabel').textContent = 'Add Automotive Seat Cover';
    document.getElementById('automotiveForm').reset();
    document.getElementById('automotiveId').value = '';
    currentEditId = null;
    new bootstrap.Modal(document.getElementById('automotiveModal')).show();
}

async function editAutomotive(id) {
    currentSection = 'automotive';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();
        
        document.getElementById('automotiveModalLabel').textContent = 'Edit Automotive Seat Cover';
        document.getElementById('automotiveId').value = data.id;
        document.getElementById('automotive_title').value = data.title;
        document.getElementById('automotive_description').value = data.description;
        currentEditId = data.id;
        new bootstrap.Modal(document.getElementById('automotiveModal')).show();
    } catch (error) {
        console.error('Error:', error);
        alert('Error loading data');
    }
}

document.getElementById('automotiveForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let url = currentEditId ? `/admin/business-content/automotive/${currentEditId}` : '/admin/business-content/automotive';
    let method = currentEditId ? 'POST' : 'POST';
    
    if (currentEditId) {
        formData.append('_method', 'PUT');
    }
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error saving data');
        }
        
        bootstrap.Modal.getInstance(document.getElementById('automotiveModal')).hide();
        location.reload();
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

// Organization Functions
function showAddOrganizationModal() {
    currentSection = 'organization';
    document.getElementById('organizationModalLabel').textContent = 'Add Organization Member';
    document.getElementById('organizationForm').reset();
    document.getElementById('organizationId').value = '';
    currentEditId = null;
    new bootstrap.Modal(document.getElementById('organizationModal')).show();
}

async function editOrganization(id) {
    currentSection = 'organization';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();
        
        document.getElementById('organizationModalLabel').textContent = 'Edit Organization Member';
        document.getElementById('organizationId').value = data.id;
        document.getElementById('organization_name').value = data.name;
        document.getElementById('organization_position').value = data.position;
        currentEditId = data.id;
        new bootstrap.Modal(document.getElementById('organizationModal')).show();
    } catch (error) {
        console.error('Error:', error);
        alert('Error loading data');
    }
}

document.getElementById('organizationForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let url = currentEditId ? `/admin/business-content/organization/${currentEditId}` : '/admin/business-content/organization';
    
    if (currentEditId) {
        formData.append('_method', 'PUT');
    }
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error saving data');
        }
        
        bootstrap.Modal.getInstance(document.getElementById('organizationModal')).hide();
        location.reload();
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

// Characteristic Functions
function showAddCharacteristicModal() {
    currentSection = 'characteristic';
    document.getElementById('characteristicModalLabel').textContent = 'Add Characteristic';
    document.getElementById('characteristicForm').reset();
    document.getElementById('characteristicId').value = '';
    currentEditId = null;
    new bootstrap.Modal(document.getElementById('characteristicModal')).show();
}

async function editCharacteristic(id) {
    currentSection = 'characteristic';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();
        
        document.getElementById('characteristicModalLabel').textContent = 'Edit Characteristic';
        document.getElementById('characteristicId').value = data.id;
        document.getElementById('characteristic_title').value = data.title;
        document.getElementById('characteristic_description').value = data.description;
        currentEditId = data.id;
        new bootstrap.Modal(document.getElementById('characteristicModal')).show();
    } catch (error) {
        console.error('Error:', error);
        alert('Error loading data');
    }
}

document.getElementById('characteristicForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let url = currentEditId ? `/admin/business-content/characteristic/${currentEditId}` : '/admin/business-content/characteristic';
    
    if (currentEditId) {
        formData.append('_method', 'PUT');
    }
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error saving data');
        }
        
        bootstrap.Modal.getInstance(document.getElementById('characteristicModal')).hide();
        location.reload();
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

// Partnership Functions
function showAddPartnershipModal() {
    currentSection = 'partnership';
    document.getElementById('partnershipModalLabel').textContent = 'Add Partnership';
    document.getElementById('partnershipForm').reset();
    document.getElementById('partnershipId').value = '';
    currentEditId = null;
    new bootstrap.Modal(document.getElementById('partnershipModal')).show();
}

async function editPartnership(id) {
    currentSection = 'partnership';
    try {
        const response = await fetch(`/admin/business-content/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();
        
        document.getElementById('partnershipModalLabel').textContent = 'Edit Partnership';
        document.getElementById('partnershipId').value = data.id;
        document.getElementById('partnership_title').value = data.title;
        currentEditId = data.id;
        new bootstrap.Modal(document.getElementById('partnershipModal')).show();
    } catch (error) {
        console.error('Error:', error);
        alert('Error loading data');
    }
}

document.getElementById('partnershipForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let url = currentEditId ? `/admin/business-content/partnership/${currentEditId}` : '/admin/business-content/partnership';
    
    if (currentEditId) {
        formData.append('_method', 'PUT');
    }
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error saving data');
        }
        
        bootstrap.Modal.getInstance(document.getElementById('partnershipModal')).hide();
        location.reload();
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

// Delete Function
async function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        try {
            const response = await fetch(`/admin/business-content/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error('Error deleting item');
            }
            
            location.reload();
        } catch (error) {
            alert('Error deleting item: ' + error.message);
        }
    }
}
</script>