<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-certificate me-2"></i>Manage ISO Obtained Content</h5>
                    <button class="btn btn-success btn-sm" id="addNewBtn">
                        <i class="fas fa-plus me-1"></i> Add New Entry
                    </button>
                </div>
                <div class="card-body">
                    <!-- ISO Entries List -->
                    <div id="isoListContainer" class="mb-4">
                        <table class="table table-hover" id="isoTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="isoTableBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Loading entries...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add/Edit Modal -->
                    <div class="modal fade" id="isoFormContainer" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content bg-white">
                                <form id="isoForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="isoId" name="id">
                                    <div class="modal-header bg-white border-bottom">
                                        <h6 id="formTitle" class="mb-0">Add New ISO Entry</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body bg-white">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="title"
                                                   name="title"
                                                   placeholder="Enter ISO title"
                                                   required>
                                            <small class="text-muted">Max 255 characters</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control"
                                                      id="description"
                                                      name="description"
                                                      rows="6"
                                                      placeholder="Enter detailed description"
                                                      required></textarea>
                                            <small class="text-muted">Support for basic formatting</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">Image</label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="image"
                                                   name="image"
                                                   accept="image/*">
                                            <small class="text-muted">JPEG, PNG, JPG, GIF, WebP. Max 5MB</small>

                                            <div id="currentImagePreview" class="mt-3 d-none">
                                                <p class="fw-bold">Current Image:</p>
                                                <img id="currentImage"
                                                     src=""
                                                     alt="Current ISO Image"
                                                     style="max-width: 300px; max-height: 200px; border-radius: 8px;">
                                                <br>
                                                <button type="button"
                                                        class="btn btn-danger btn-sm mt-2"
                                                        id="removeImageBtn">
                                                    <i class="fas fa-trash me-1"></i> Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-white border-top">
                                        <button type="button" class="btn btn-secondary" id="cancelBtn" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-1"></i> Save Entry
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<div id="customToast" style="position: fixed; top: 20px; right: 20px; z-index: 11; display: none;">
    <div style="background: #d4edda; color: #155724; border-radius: 8px; padding: 15px 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 320px; display: flex; align-items: center; gap: 12px; border-left: 4px solid #28a745;">
        <i class="fas fa-check-circle" id="toastIcon" style="color: #28a745;"></i>
        <span id="toastMessageText" style="flex: 1; font-size: 14px;"></span>
        <button type="button" onclick="closeToast()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999; padding: 0; line-height: 1;">&times;</button>
    </div>
</div>
<script>
let toastTimeout;

function showToast(message, type = 'success', duration = 3000) {
    const toast = document.getElementById('customToast');
    if (!toast) {
        console.error('Toast element not found');
        alert(message);
        return;
    }

    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessageText');
    const toastContent = toast.querySelector('div');

    if (!toastIcon || !toastMessage || !toastContent) {
        console.error('Toast elements not found');
        alert(message);
        return;
    }

    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }

    const normalizedType = type === true ? 'error' : type === false ? 'success' : type;
    const color = normalizedType === 'success' ? '#28a745' : normalizedType === 'error' ? '#dc3545' : '#17a2b8';
    const icon = normalizedType === 'success' ? 'check-circle' : normalizedType === 'error' ? 'exclamation-circle' : 'info-circle';

    toastContent.style.borderLeftColor = color;
    toastContent.style.background = normalizedType === 'success' ? '#d4edda' : normalizedType === 'error' ? '#f8d7da' : '#d1ecf1';
    toastContent.style.color = normalizedType === 'success' ? '#155724' : normalizedType === 'error' ? '#721c24' : '#0c5460';
    toastIcon.className = `fas fa-${icon}`;
    toastIcon.style.color = color;

    toastMessage.textContent = message;
    toast.style.display = 'block';

    toastTimeout = setTimeout(() => {
        closeToast();
    }, duration);
}

function closeToast() {
    const toast = document.getElementById('customToast');
    if (toast) {
        toast.style.display = 'none';
    }
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }
}

$(document).ready(function() {

    // Load ISO entries on page load
    function loadIsoEntries() {
        $.ajax({
            url: "{{ route('admin.iso.index') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                renderTable(data);
            },
            error: function() {
                showToast('Failed to load ISO entries', 'error');
            }
        });
    }

    // Render table with ISO entries
    function renderTable(entries) {
        const tbody = $('#isoTableBody');
        tbody.empty();

        if (entries.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-inbox me-2"></i>No ISO entries found. Click "Add New Entry" to create one.
                    </td>
                </tr>
            `);
            return;
        }

        entries.forEach(entry => {
            const description = entry.description.substring(0, 50) + (entry.description.length > 50 ? '...' : '');
            const createdDate = new Date(entry.created_at).toLocaleDateString();
            const currentStatus = entry.status || 'published';
            const imageCell = `<img src="${entry.image_url}" alt="${entry.title}" style="width: 72px; height: 72px; object-fit: cover; border-radius: 8px; border: 1px solid #e9ecef;">`;

            tbody.append(`
                <tr>
                    <td><strong>${entry.title}</strong></td>
                    <td>${description}</td>
                    <td>
                        ${imageCell}
                    </td>
                    <td>
                        <select class="form-select form-select-sm status-select" data-id="${entry.id}" style="width: 130px;">
                            <option value="published" ${currentStatus === 'published' ? 'selected' : ''}>Published</option>
                            <option value="archived" ${currentStatus === 'archived' ? 'selected' : ''}>Archived</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning text-dark editBtn" data-id="${entry.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${entry.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Add New Entry Button
    $('#addNewBtn').on('click', function() {
        resetForm();
        $('#formTitle').html('<i class="fas fa-plus me-2"></i>Add New ISO Entry');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('isoFormContainer')).show();
        setTimeout(function() {
            $('#title').trigger('focus');
        }, 150);
    });

    // Cancel Button
    $('#cancelBtn').on('click', function() {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('isoFormContainer')).hide();
        resetForm();
    });

    // Edit Button
    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete ISO Entry?',
            html: '<div style="font-size: 1rem; color: #6c757d;">This action is permanent and will remove the ISO entry and its image files.</div>',
            icon: 'warning',
            iconColor: '#f0ad4e',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-4 shadow',
                title: 'fw-semibold',
                confirmButton: 'btn btn-danger px-4 py-2 me-2',
                cancelButton: 'btn btn-outline-secondary px-4 py-2'
            }
        }).then((result) => {
                if (data.image_url && !data.image_url.includes('default-image.png')) {
                    $('#currentImage').attr('src', data.image_url);
                    $('#currentImagePreview').removeClass('d-none');
                } else {
                    $('#currentImagePreview').addClass('d-none');
                }

                $('#formTitle').html('<i class="fas fa-edit me-2"></i>Edit ISO Entry');
                bootstrap.Modal.getOrCreateInstance(document.getElementById('isoFormContainer')).show();
            },
            error: function() {
                showToast('Failed to load entry details', 'error');
            }
        });
    });

    // Delete Button
    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');
        const button = $(this);

        Swal.fire({
            title: 'Delete Entry?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/iso-obtained/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'error');
                            loadIsoEntries();
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to delete entry';
                        showToast(message, 'error');
                    }
                });
            }
        });
    });

    // Status dropdown update
    $(document).on('change', '.status-select', function() {
        const id = $(this).data('id');
        const status = $(this).val();
        const select = $(this);
        select.prop('disabled', true);

        $.ajax({
            url: `/admin/iso-obtained/${id}/status/${status}`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to update status';
                showToast(message, 'error');
                loadIsoEntries();
            },
            complete: function() {
                select.prop('disabled', false);
            }
        });
    });

    // Form Submit (Create or Update)
    $('#isoForm').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const id = $('#isoId').val();
        const url = id ? `/admin/iso-obtained/${id}` : '{{ route('admin.iso.store') }}';
        const method = id ? 'PUT' : 'POST';

        const formData = new FormData(this);
        const submitBtn = $('#submitBtn');
        const originalBtnHtml = submitBtn.html();

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('isoFormContainer')).hide();
                    resetForm();
                    loadIsoEntries();
                }
            },
            error: function(xhr) {
                let message = 'An error occurred';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).join(', ');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });

    // Remove Image Button
    $(document).on('click', '#removeImageBtn', function() {
        const id = $('#isoId').val();

        Swal.fire({
            title: 'Remove Image?',
            text: 'This will delete the image permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/iso-obtained/${id}/remove-image`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');
                            $('#currentImagePreview').addClass('d-none');
                            $('#image').val('');
                        }
                    },
                    error: function() {
                        showToast('Failed to remove image', 'error');
                    }
                });
            }
        });
    });

    // Reset form
    function resetForm() {
        $('#isoForm')[0].reset();
        $('#isoId').val('');
        $('#currentImagePreview').addClass('d-none');
    }

    $('#isoFormContainer').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Load entries on page load
    loadIsoEntries();
});
</script>
@endpush
