/**
 * Media Management Module
 * Handles all event/activity management functionality
 */

(function() {
    'use strict';
    
    let addModal = null;
    let editModal = null;
    let allData = [];
    let currentPage = 1;
    let rowsPerPage = 10;
    let currentFilters = {
        search: '',
        type: '',
        status: ''
    };
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMediaManagement);
    } else {
        initMediaManagement();
    }
    
    function initMediaManagement() {
        console.log('Initializing media management...');
        
        // Initialize Add Modal with static backdrop
        const addModalElement = document.getElementById('mediaAddModal');
        if (addModalElement && typeof bootstrap !== 'undefined') {
            addModal = new bootstrap.Modal(addModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            addModalElement.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('mediaAddForm');
                if (form) {
                    form.reset();
                    form.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                }
            });
        }
        
        // Initialize Edit Modal with static backdrop
        const editModalElement = document.getElementById('mediaEditModal');
        if (editModalElement && typeof bootstrap !== 'undefined') {
            editModal = new bootstrap.Modal(editModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            editModalElement.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('mediaEditForm');
                if (form) {
                    form.reset();
                }
            });
        }
        
        loadDataFromServer();
        attachEventHandlers();
        initDirectUploadModal();
        initBatchUpload();
        
        // Global Enter key prevention for all modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.closest('.modal')) {
                const activeElement = document.activeElement;
                if (activeElement && activeElement.tagName.toLowerCase() === 'textarea') {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    }
    
    async function loadDataFromServer() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const tableContainer = document.getElementById('tableContainer');
        
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (tableContainer) tableContainer.style.display = 'none';
        
        try {
            const response = await fetch('/admin/media/all', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            });
            
            allData = await response.json();
            console.log(`Loaded ${allData.length} records`);
            
            renderTable();
            
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (tableContainer) tableContainer.style.display = 'block';
            
        } catch (error) {
            console.error('Error loading data:', error);
            if (loadingIndicator) {
                loadingIndicator.innerHTML = '<div class="alert alert-danger">Error loading data. Please refresh the page.</div>';
            }
        }
    }
    
    function formatDate(dateString) {
        if (!dateString) return '-';
        
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        
        return `${year}-${month}-${day}`;
    }
    
    function formatDateTime(dateString) {
        if (!dateString) return '-';
        
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day} ${hours}:${minutes}`;
    }
    
    function renderTable() {
        let filteredData = [...allData];
        
        if (currentFilters.search) {
            filteredData = filteredData.filter(item => 
                item.title.toLowerCase().includes(currentFilters.search) || 
                (item.description && item.description.toLowerCase().includes(currentFilters.search))
            );
        }
        
        if (currentFilters.type) {
            filteredData = filteredData.filter(item => item.type === currentFilters.type);
        }
        
        if (currentFilters.status) {
            filteredData = filteredData.filter(item => item.status === currentFilters.status);
        }
        
        const totalRecords = filteredData.length;
        const totalPages = Math.ceil(totalRecords / rowsPerPage);
        
        if (currentPage > totalPages) currentPage = totalPages || 1;
        if (currentPage < 1) currentPage = 1;
        
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageData = filteredData.slice(start, end);
        
        let tableHtml = `
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" id="mediaTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th style="width: 80px;">Image</th>
                            <th>Title</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 110px;">Date</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 140px;">Created At</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        if (pageData.length === 0) {
            tableHtml += '<tr><td colspan="8" class="text-center text-muted py-4">No matching records found</td></tr>';
        } else {
            pageData.forEach(item => {
                const imageHtml = item.image_url 
                    ? `<img src="${item.image_url}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" onerror="this.src='/images/default-image.png'">`
                    : '<span class="badge bg-secondary">No Image</span>';
                
                const typeBadge = item.type === 'event' 
                    ? '<span class="badge bg-primary">Event</span>'
                    : '<span class="badge bg-success">Activity</span>';
                
                const statusSelect = `
                    <select class="form-select form-select-sm status-select" data-id="${item.id}">
                        <option value="published" ${item.status === 'published' ? 'selected' : ''}>Published</option>
                        <option value="archived" ${item.status === 'archived' ? 'selected' : ''}>Archived</option>
                    </select>
                `;
                
                const actions = `
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-warning edit-btn" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger delete-btn" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                const formattedEventDate = formatDate(item.event_date);
                const formattedCreatedAt = formatDateTime(item.created_at);
                
                tableHtml += `
                    <tr data-id="${item.id}" data-type="${item.type}" data-status="${item.status}">
                        <td class="text-center">${item.id}</td>
                        <td>${imageHtml}</td>
                        <td>${escapeHtml(item.title)}</td>
                        <td>${typeBadge}</td>
                        <td>${formattedEventDate}</td>
                        <td>${statusSelect}</td>
                        <td>${formattedCreatedAt}</td>
                        <td>${actions}</td>
                    </tr>
                `;
            });
        }
        
        tableHtml += `
                    </tbody>
                </table>
            </div>
        `;
        
        document.getElementById('tableContainer').innerHTML = tableHtml;
        
        document.getElementById('showingStart').textContent = totalRecords === 0 ? 0 : start + 1;
        document.getElementById('showingEnd').textContent = Math.min(end, totalRecords);
        document.getElementById('totalRecords').textContent = totalRecords;
        
        renderPagination(currentPage, totalPages);
        attachDynamicHandlers();
    }
    
    function renderPagination(currentPage, totalPages) {
        const paginationUl = document.getElementById('tablePagination');
        if (!paginationUl) return;
        
        if (totalPages <= 1) {
            paginationUl.innerHTML = '';
            return;
        }
        
        let html = '';
        
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}" data-pagination-link="true">&laquo; Previous</a>
        </li>`;
        
        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage + 1 < maxVisible) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1" data-pagination-link="true">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}" data-pagination-link="true">${i}</a>
            </li>`;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}" data-pagination-link="true">${totalPages}</a></li>`;
        }
        
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}" data-pagination-link="true">Next &raquo;</a>
        </li>`;
        
        paginationUl.innerHTML = html;
        attachPaginationHandlers();
    }
    
    function attachPaginationHandlers() {
        const paginationLinks = document.querySelectorAll('#tablePagination .page-link[data-pagination-link="true"]');
        
        paginationLinks.forEach(link => {
            link.removeEventListener('click', handlePaginationClick);
            link.addEventListener('click', handlePaginationClick);
        });
    }
    
    function handlePaginationClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const page = parseInt(this.getAttribute('data-page'));
        
        let filteredData = [...allData];
        
        if (currentFilters.search) {
            filteredData = filteredData.filter(item => 
                item.title.toLowerCase().includes(currentFilters.search) || 
                (item.description && item.description.toLowerCase().includes(currentFilters.search))
            );
        }
        
        if (currentFilters.type) {
            filteredData = filteredData.filter(item => item.type === currentFilters.type);
        }
        
        if (currentFilters.status) {
            filteredData = filteredData.filter(item => item.status === currentFilters.status);
        }
        
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
        
        if (isNaN(page)) return;
        if (page < 1 || page > totalPages) return;
        if (page === currentPage) return;
        
        currentPage = page;
        renderTable();
    }
    
    function attachDynamicHandlers() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.removeEventListener('change', handleStatusChange);
            select.addEventListener('change', handleStatusChange);
        });
        
        const container = document.getElementById('tableContainer');
        if (container) {
            container.removeEventListener('click', handleTableClick);
            container.addEventListener('click', handleTableClick);
        }
    }
    
    function handleTableClick(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            e.preventDefault();
            handleEditClick(editBtn.dataset.id);
        }
        
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            e.preventDefault();
            handleDeleteClick(deleteBtn.dataset.id);
        }
    }
    
    async function handleStatusChange(e) {
        const select = e.target;
        const id = select.dataset.id;
        const status = select.value;
        
        select.disabled = true;
        
        try {
            const response = await fetch(`/admin/media/${id}/status/${status}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showCustomToast(data.message, 'success');
                const itemIndex = allData.findIndex(item => item.id == id);
                if (itemIndex !== -1) {
                    allData[itemIndex].status = status;
                }
                renderTable();
            } else {
                showCustomToast(data.message || 'Failed to update status', 'error');
                await loadDataFromServer();
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error updating status', 'error');
            await loadDataFromServer();
        } finally {
            select.disabled = false;
        }
    }
    
    async function handleEditClick(id) {
        const modalBody = document.getElementById('mediaEditModalBody');
        if (modalBody) {
            modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-3">Loading...</p></div>';
        }
        
        editModal.show();
        
        try {
            const response = await fetch(`/admin/media/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            populateEditModal(data);
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Failed to load data', 'error');
            editModal.hide();
        }
    }
    
function populateEditModal(data) {
    const modalBody = document.getElementById('mediaEditModalBody');
    
    let formattedDate = '';
    if (data.event_date) {
        const date = new Date(data.event_date);
        if (!isNaN(date.getTime())) {
            formattedDate = date.toISOString().split('T')[0];
        }
    }
    
    // Use the has_custom_image flag from server response
    let hasCustomImage = data.has_custom_image === true;
    let imageSrc = '/images/default-image.png';
    
    // Only use custom image URL if has_custom_image is true
    if (hasCustomImage && data.image_url) {
        imageSrc = data.image_url;
    }
    
    console.log('Edit modal data:', { 
        hasCustomImage, 
        imageSrc, 
        image: data.image,
        image_url: data.image_url 
    });
    
    modalBody.innerHTML = `
        <div class="mb-3">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="${escapeHtml(data.title)}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" class="form-select" required>
                <option value="event" ${data.type === 'event' ? 'selected' : ''}>Event</option>
                <option value="activity" ${data.type === 'activity' ? 'selected' : ''}>Activity</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="5" required>${escapeHtml(data.description)}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="event_date" class="form-control" value="${formattedDate}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label>
            <div class="current-image-wrapper mt-2">
                <div class="d-flex align-items-start gap-3">
                    <img src="${imageSrc}" alt="Current image" id="currentEditImage" style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;" 
                         onerror="this.onerror=null; this.src='/images/default-image.png';">
                    <div class="image-info">
                        ${hasCustomImage ? 
                            '<div class="text-success small mb-2"><i class="fas fa-check-circle"></i> Custom image uploaded</div>' : 
                            '<div class="text-muted small mb-2"><i class="fas fa-image"></i> Using default image</div>'}
                        ${hasCustomImage ? 
                            `<button type="button" class="btn btn-danger btn-sm" id="removeImageBtn" data-id="${data.id}">
                                <i class="fas fa-trash-alt me-1"></i> Remove Image
                            </button>` : 
                            ''}
                    </div>
                </div>
            </div>
            <label class="form-label mt-3">Change Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="form-text text-muted">Max size: 5MB. Upload a new image to replace the current one.</small>
            <div id="editImagePreview" style="display: none;" class="mt-2">
                <label class="text-muted">New Image Preview:</label>
                <div>
                    <img id="editPreviewImg" src="#" alt="Preview" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                </div>
            </div>
        </div>
    `;
    
    // Attach remove image button handler
    const removeBtn = document.getElementById('removeImageBtn');
    if (removeBtn) {
        // Remove any existing event listeners to prevent duplicates
        const newRemoveBtn = removeBtn.cloneNode(true);
        removeBtn.parentNode.replaceChild(newRemoveBtn, removeBtn);
        newRemoveBtn.addEventListener('click', function(e) {
            e.preventDefault();
            handleRemoveImage(data.id);
        });
    }
    
    // Attach image preview handler
    const editImageInput = modalBody.querySelector('input[name="image"]');
    if (editImageInput) {
        editImageInput.addEventListener('change', function(e) {
            const preview = document.getElementById('editImagePreview');
            const previewImg = document.getElementById('editPreviewImg');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
                previewImg.src = '#';
            }
        });
    }
    
    const form = document.getElementById('mediaEditForm');
    form.action = `/admin/media/${data.id}`;
    form.enctype = 'multipart/form-data';
    
    preventEnterKeyOnForm(form);
}

async function handleRemoveImage(mediaId) {
    if (!confirm('⚠️ Are you sure you want to remove this image?\n\nDefault image will be used instead.')) {
        return;
    }
    
    // Show loading state on the remove button
    const removeBtn = document.getElementById('removeImageBtn');
    const originalBtnHtml = removeBtn ? removeBtn.innerHTML : '';
    if (removeBtn) {
        removeBtn.disabled = true;
        removeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Removing...';
    }
    
    try {
        const response = await fetch(`/admin/media/${mediaId}/remove-image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showCustomToast(data.message, 'success');
            
            // Update the current image display to default
            const currentImage = document.getElementById('currentEditImage');
            if (currentImage) {
                currentImage.src = '/images/default-image.png';
            }
            
            // Update the image info section - remove the remove button since image is gone
            const imageInfoDiv = document.querySelector('.image-info');
            if (imageInfoDiv) {
                imageInfoDiv.innerHTML = '<div class="text-muted small"><i class="fas fa-image"></i> Using default image</div>';
            }
            
            // Also remove the remove button if it still exists
            const removeButton = document.getElementById('removeImageBtn');
            if (removeButton) {
                removeButton.remove();
            }
            
            // Update the global data to reflect image removal
            const itemIndex = allData.findIndex(item => item.id == mediaId);
            if (itemIndex !== -1) {
                allData[itemIndex].image = null;
                allData[itemIndex].image_url = null;
                allData[itemIndex].has_custom_image = false;
                allData[itemIndex].updated_at = data.data.updated_at;
            }
            
            // Refresh the table to show updated image
            renderTable();
            
            // Force refresh images in the table
            setTimeout(() => {
                forceRefreshTableImages();
            }, 100);
            
        } else {
            showCustomToast(data.message || 'Failed to remove image', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showCustomToast('Network error removing image', 'error');
    } finally {
        if (removeBtn) {
            removeBtn.disabled = false;
            removeBtn.innerHTML = originalBtnHtml;
        }
    }
}
    
    async function handleDeleteClick(id) {
        if (!confirm('⚠️ Are you sure you want to delete this item?\n\nThis action cannot be undone!')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/media/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showCustomToast(data.message, 'error');
                await loadDataFromServer();
            } else {
                showCustomToast(data.message || 'Failed to delete item', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error deleting item', 'error');
        }
    }
    
    function attachEventHandlers() {
        const searchInput = document.getElementById('tableSearchInput');
        if (searchInput) {
            searchInput.removeEventListener('keyup', handleSearch);
            searchInput.addEventListener('keyup', handleSearch);
        }
        
        const typeFilter = document.getElementById('typeFilter');
        if (typeFilter) {
            typeFilter.removeEventListener('change', handleTypeFilter);
            typeFilter.addEventListener('change', handleTypeFilter);
        }
        
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.removeEventListener('change', handleStatusFilter);
            statusFilter.addEventListener('change', handleStatusFilter);
        }
        
        const resetBtn = document.getElementById('resetFilters');
        if (resetBtn) {
            resetBtn.removeEventListener('click', handleReset);
            resetBtn.addEventListener('click', handleReset);
        }
        
        const rowsPerPageSelect = document.getElementById('rowsPerPage');
        if (rowsPerPageSelect) {
            rowsPerPageSelect.removeEventListener('change', handleRowsPerPageChange);
            rowsPerPageSelect.addEventListener('change', handleRowsPerPageChange);
        }
        
        const addForm = document.getElementById('mediaAddForm');
        if (addForm) {
            addForm.removeEventListener('submit', handleAddFormSubmit);
            addForm.addEventListener('submit', handleAddFormSubmit);
            preventEnterKeyOnForm(addForm);
            
            const addModalCloseBtns = document.querySelectorAll('#mediaAddModal [data-bs-dismiss="modal"]');
            addModalCloseBtns.forEach(btn => {
                btn.removeEventListener('click', handleAddModalClose);
                btn.addEventListener('click', handleAddModalClose);
            });
        }
        
        const editForm = document.getElementById('mediaEditForm');
        if (editForm) {
            editForm.removeEventListener('submit', handleEditFormSubmit);
            editForm.addEventListener('submit', handleEditFormSubmit);
            preventEnterKeyOnForm(editForm);
        }
    }
    
    async function handleAddFormSubmit(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        
        try {
            const formData = new FormData(form);
            
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showCustomToast(data.message, 'success');
                
                if (addModal) {
                    addModal.hide();
                }
                
                form.reset();
                await loadDataFromServer();
            } else {
                let errorMessage = data.message || 'Failed to save';
                if (data.errors) {
                    errorMessage = Object.values(data.errors).flat().join('\n');
                }
                showCustomToast(errorMessage, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error saving data', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
    
    async function handleEditFormSubmit(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        
        try {
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showCustomToast(data.message, 'success');
                
                if (editModal) {
                    editModal.hide();
                }
                
                form.reset();
                await loadDataFromServer();
            } else {
                let errorMessage = data.message || 'Failed to save';
                if (data.errors) {
                    errorMessage = Object.values(data.errors).flat().join('\n');
                }
                showCustomToast(errorMessage, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error saving data', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
    
    function preventEnterKeyOnForm(form) {
        if (!form) return;
        
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.removeEventListener('keypress', handleEnterKeyPress);
            input.addEventListener('keypress', handleEnterKeyPress);
        });
    }
    
    function handleEnterKeyPress(e) {
        if (e.target.tagName.toLowerCase() === 'textarea') {
            return true;
        }
        
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    }
    
    function handleAddModalClose(e) {
        if (addModal) {
            addModal.hide();
        }
    }
    
    function handleSearch(e) {
        currentFilters.search = e.target.value.toLowerCase();
        currentPage = 1;
        renderTable();
    }
    
    function handleTypeFilter(e) {
        currentFilters.type = e.target.value;
        currentPage = 1;
        renderTable();
    }
    
    function handleStatusFilter(e) {
        currentFilters.status = e.target.value;
        currentPage = 1;
        renderTable();
    }
    
    function handleReset(e) {
        document.getElementById('tableSearchInput').value = '';
        document.getElementById('typeFilter').value = '';
        document.getElementById('statusFilter').value = '';
        currentFilters = { search: '', type: '', status: '' };
        currentPage = 1;
        renderTable();
    }
    
    function handleRowsPerPageChange(e) {
        rowsPerPage = parseInt(e.target.value);
        currentPage = 1;
        renderTable();
    }
    
// Updated functions in media.js - Replace these functions

    function initDirectUploadModal() {
        const directModal = document.getElementById('directImageUploadModal');
        if (!directModal) return;
        
        // Update modal title and description
        const modalTitle = directModal.querySelector('.modal-title');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="fas fa-folder-open me-2"></i>Upload Image to EventActivity Folder';
        }
        
        const modalBody = directModal.querySelector('.modal-body');
        if (modalBody) {
            // Add info about the folder
            const infoAlert = modalBody.querySelector('.alert-info');
            if (!infoAlert) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-info mb-3';
                alertDiv.innerHTML = '<i class="fas fa-info-circle me-2"></i>Images will be uploaded to <strong>public/images/EventActivity/</strong> folder with the ID as filename (e.g., 1.jpg, 2.png). These images will take priority over database-stored images.';
                modalBody.insertBefore(alertDiv, modalBody.firstChild);
            }
        }
        
        directModal.addEventListener('show.bs.modal', function() {
            const select = document.getElementById('directImageId');
            if (select) {
                select.innerHTML = '<option value="">Loading...</option>';
                
                fetch('/admin/media/all', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '<option value="">Choose an item...</option>';
                    data.forEach(item => {
                        select.innerHTML += `<option value="${item.id}">${item.id} - ${escapeHtml(item.title)} (${item.type})</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading items:', error);
                    select.innerHTML = '<option value="">Error loading items</option>';
                });
            }
        });
        
        const form = document.getElementById('directImageUploadForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const id = document.getElementById('directImageId').value;
                const file = document.getElementById('directImageFile').files[0];
                
                if (!id || !file) {
                    showCustomToast('Please select an item and an image', 'error');
                    return;
                }
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
                
                const formData = new FormData();
                formData.append('id', id);
                formData.append('image', file);
                
                try {
                    const response = await fetch('/admin/media/upload-direct', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showCustomToast('Image uploaded successfully to images/EventActivity folder!', 'success');
                        
                        const modal = bootstrap.Modal.getInstance(directModal);
                        if (modal) modal.hide();
                        
                        form.reset();
                        await loadDataFromServer();
                    } else {
                        showCustomToast(data.message || 'Upload failed', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showCustomToast('Network error uploading image', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    }
    
    function initBatchUpload() {
        const batchModal = document.getElementById('batchUploadModal');
        if (!batchModal) return;
        
        // Update modal title and description
        const modalTitle = batchModal.querySelector('.modal-title');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="fas fa-layer-group me-2"></i>Batch Upload to EventActivity Folder';
        }
        
        const modalBody = batchModal.querySelector('.modal-body');
        if (modalBody) {
            // Add info about the folder
            const infoAlert = modalBody.querySelector('.alert-info');
            if (!infoAlert) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-info mb-3';
                alertDiv.innerHTML = '<i class="fas fa-info-circle me-2"></i><strong>Important:</strong> Filenames must be numeric IDs (e.g., 1.jpg, 2.png, 3.webp). Files will be saved to <strong>public/images/EventActivity/</strong> folder and will automatically be assigned to matching event/activity IDs.';
                modalBody.insertBefore(alertDiv, modalBody.firstChild);
            }
        }
        
        const batchImages = document.getElementById('batchImages');
        const batchUploadPreview = document.getElementById('batchUploadPreview');
        const fileList = document.getElementById('fileList');
        
        if (batchImages) {
            batchImages.addEventListener('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    batchUploadPreview.style.display = 'block';
                    fileList.innerHTML = '';
                    for (let i = 0; i < files.length; i++) {
                        const filename = files[i].name;
                        const isValid = /^\d+\.(jpg|jpeg|png|gif|webp)$/i.test(filename);
                        const icon = isValid ? '✅' : '❌';
                        const color = isValid ? 'style="color: green;"' : 'style="color: red;"';
                        fileList.innerHTML += `<div ${color}>${icon} ${filename} (${(files[i].size / 1024).toFixed(2)} KB) ${!isValid ? ' - Invalid filename! Must be numeric ID (e.g., 1.jpg)' : ''}</div>`;
                    }
                } else {
                    batchUploadPreview.style.display = 'none';
                }
            });
        }
        
        const batchUploadBtn = document.getElementById('batchUploadBtn');
        if (batchUploadBtn) {
            batchUploadBtn.addEventListener('click', async function() {
                const files = document.getElementById('batchImages').files;
                if (files.length === 0) {
                    showCustomToast('Please select files to upload', 'error');
                    return;
                }
                
                const formData = new FormData();
                let validFiles = 0;
                for (let i = 0; i < files.length; i++) {
                    const filename = files[i].name;
                    if (/^\d+\.(jpg|jpeg|png|gif|webp)$/i.test(filename)) {
                        formData.append('images[]', files[i]);
                        validFiles++;
                    }
                }
                
                if (validFiles === 0) {
                    showCustomToast('No valid files selected. Filenames must be numeric IDs (e.g., 1.jpg, 2.png)', 'error');
                    return;
                }
                
                const progressDiv = document.getElementById('batchUploadProgress');
                const progressBar = document.getElementById('uploadProgressBar');
                const uploadStatus = document.getElementById('uploadStatus');
                
                progressDiv.style.display = 'block';
                batchUploadBtn.disabled = true;
                batchUploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
                
                try {
                    const response = await fetch('/admin/media/batch-upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showCustomToast(data.message, 'success');
                        
                        const modal = bootstrap.Modal.getInstance(batchModal);
                        if (modal) modal.hide();
                        
                        await loadDataFromServer();
                        
                        document.getElementById('batchUploadForm').reset();
                        batchUploadPreview.style.display = 'none';
                    } else {
                        showCustomToast(data.message, 'error');
                        if (data.failed && data.failed.length > 0) {
                            console.error('Failed uploads:', data.failed);
                            let failedMsg = 'Failed uploads:\n';
                            data.failed.forEach(f => {
                                failedMsg += `- ${f.filename}: ${f.reason}\n`;
                            });
                            showCustomToast(failedMsg, 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showCustomToast('Network error during batch upload', 'error');
                } finally {
                    progressDiv.style.display = 'none';
                    batchUploadBtn.disabled = false;
                    batchUploadBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Upload All';
                    progressBar.style.width = '0%';
                }
            });
        }
    }
    
    function getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.content : '';
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function showCustomToast(message, type = 'success') {
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

        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }, 5000);
    }
    
    window.loadMediaData = loadDataFromServer;
    console.log('Media.js initialized successfully');
})();

// Add these functions to your media.js file

// Function to force refresh all images in the table
function forceRefreshTableImages() {
    const table = document.getElementById('mediaTable');
    if (!table) return;
    
    const images = table.querySelectorAll('img');
    images.forEach(img => {
        const originalSrc = img.src.split('?')[0];
        // Add timestamp to force reload
        img.src = originalSrc + '?t=' + new Date().getTime();
    });
}

// Override the loadDataFromServer function to prevent caching
async function loadDataFromServer() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    
    if (loadingIndicator) loadingIndicator.style.display = 'block';
    if (tableContainer) tableContainer.style.display = 'none';
    
    try {
        // Add timestamp to prevent caching
        const timestamp = new Date().getTime();
        const response = await fetch(`/admin/media/all?_=${timestamp}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        });
        
        allData = await response.json();
        console.log(`Loaded ${allData.length} records`);
        
        renderTable();
        
        if (loadingIndicator) loadingIndicator.style.display = 'none';
        if (tableContainer) tableContainer.style.display = 'block';
        
    } catch (error) {
        console.error('Error loading data:', error);
        if (loadingIndicator) {
            loadingIndicator.innerHTML = '<div class="alert alert-danger">Error loading data. Please refresh the page.</div>';
        }
    }
}

// Update the handleEditFormSubmit function
async function handleEditFormSubmit(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const id = form.dataset.id || form.action.split('/').pop();
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    try {
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showCustomToast(data.message, 'success');
            
            if (editModal) {
                editModal.hide();
            }
            
            // Force reload all data from server (bypass cache)
            await loadDataFromServer();
            
            // Additional force refresh for images
            setTimeout(() => {
                forceRefreshTableImages();
            }, 100);
            
            form.reset();
        } else {
            let errorMessage = data.message || 'Failed to save';
            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join('\n');
            }
            showCustomToast(errorMessage, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showCustomToast('Network error saving data', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Update handleStatusChange function
async function handleStatusChange(e) {
    const select = e.target;
    const id = select.dataset.id;
    const status = select.value;
    
    select.disabled = true;
    
    try {
        const timestamp = new Date().getTime();
        const response = await fetch(`/admin/media/${id}/status/${status}?_=${timestamp}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showCustomToast(data.message, 'success');
            await loadDataFromServer(); // Reload fresh data
        } else {
            showCustomToast(data.message || 'Failed to update status', 'error');
            await loadDataFromServer();
        }
    } catch (error) {
        console.error('Error:', error);
        showCustomToast('Network error updating status', 'error');
        await loadDataFromServer();
    } finally {
        select.disabled = false;
    }
}

// Update handleDeleteClick function
async function handleDeleteClick(id) {
    if (!confirm('⚠️ Are you sure you want to delete this item?\n\nThis action cannot be undone!')) {
        return;
    }
    
    try {
        const timestamp = new Date().getTime();
        const response = await fetch(`/admin/media/${id}?_=${timestamp}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showCustomToast(data.message, 'error');
            await loadDataFromServer();
        } else {
            showCustomToast(data.message || 'Failed to delete item', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showCustomToast('Network error deleting item', 'error');
    }
}