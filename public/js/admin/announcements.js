/**
 * Announcements Management Module
 */

(function() {
    'use strict';
    
    let addModal = null;
    let editModal = null;
    let directUploadModal = null;
    let allData = [];
    let currentPage = 1;
    let rowsPerPage = 10;
    let currentFilters = {
        search: '',
        status: ''
    };
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnnouncementManagement);
    } else {
        initAnnouncementManagement();
    }
    
    function initAnnouncementManagement() {
        console.log('Initializing announcement management...');
        
        // Initialize Add Modal
        const addModalElement = document.getElementById('announcementAddModal');
        if (addModalElement && typeof bootstrap !== 'undefined') {
            addModal = new bootstrap.Modal(addModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            addModalElement.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('announcementAddForm');
                if (form) {
                    form.reset();
                    form.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                }
            });
        }
        
        // Initialize Edit Modal
        const editModalElement = document.getElementById('announcementEditModal');
        if (editModalElement && typeof bootstrap !== 'undefined') {
            editModal = new bootstrap.Modal(editModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            editModalElement.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('announcementEditForm');
                if (form) {
                    form.reset();
                }
            });
        }
        
        // Initialize Direct Upload Modal
        const directUploadModalElement = document.getElementById('announcementDirectUploadModal');
        if (directUploadModalElement && typeof bootstrap !== 'undefined') {
            directUploadModal = new bootstrap.Modal(directUploadModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            directUploadModalElement.addEventListener('show.bs.modal', function() {
                loadAnnouncementsForDirectUpload();
            });
        }
        
        loadDataFromServer();
        attachEventHandlers();
        
        // Prevent Enter key in modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.closest('.modal')) {
                if (e.target.tagName.toLowerCase() === 'textarea') {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    }
    
    async function loadDataFromServer() {
        const loadingIndicator = document.getElementById('announcementLoadingIndicator');
        const tableContainer = document.getElementById('announcementTableContainer');
        
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (tableContainer) tableContainer.style.display = 'none';
        
        try {
            // Add timestamp to prevent caching
            const timestamp = new Date().getTime();
            const response = await fetch(`/admin/announcements/all?_=${timestamp}`, {
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
    
    function forceRefreshTableImages() {
        const table = document.getElementById('announcementTable');
        if (!table) return;
        
        const images = table.querySelectorAll('img');
        images.forEach(img => {
            const originalSrc = img.src.split('?')[0];
            img.src = originalSrc + '?t=' + new Date().getTime();
        });
    }
    
    function renderTable() {
        let filteredData = [...allData];
        
        if (currentFilters.search) {
            filteredData = filteredData.filter(item => 
                item.title.toLowerCase().includes(currentFilters.search) || 
                (item.description && item.description.toLowerCase().includes(currentFilters.search))
            );
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
                <table class="table table-bordered table-striped align-middle" id="announcementTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th style="width: 80px;">Image</th>
                            <th>Title</th>
                            <th style="width: 110px;">Date</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 140px;">Created At</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        if (pageData.length === 0) {
            tableHtml += '<tr><td colspan="7" class="text-center text-muted py-4">No matching records found</td>\n                </tr>';
        } else {
            pageData.forEach(item => {
                const imageHtml = item.image_url 
                    ? `<img src="${item.image_url}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" loading="lazy">`
                    : '<span class="badge bg-secondary">No Image</span>';
                
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
                const formattedDate = formatDate(item.date);
                const formattedCreatedAt = formatDateTime(item.created_at);
                
                tableHtml += `
                    <tr data-id="${item.id}" data-status="${item.status}">
                        <td class="text-center"><strong>${item.id}</strong></td>
                        <td>${imageHtml}</td>
                        <td>${escapeHtml(item.title)}</td>
                        <td>${formattedDate}</td>
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
        
        document.getElementById('announcementTableContainer').innerHTML = tableHtml;
        
        document.getElementById('announcementShowingStart').textContent = totalRecords === 0 ? 0 : start + 1;
        document.getElementById('announcementShowingEnd').textContent = Math.min(end, totalRecords);
        document.getElementById('announcementTotalRecords').textContent = totalRecords;
        
        renderPagination(currentPage, totalPages);
        attachDynamicHandlers();
    }
    
    function renderPagination(currentPage, totalPages) {
        const paginationUl = document.getElementById('announcementPagination');
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
        const paginationLinks = document.querySelectorAll('#announcementPagination .page-link[data-pagination-link="true"]');
        
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
        
        const container = document.getElementById('announcementTableContainer');
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
            const timestamp = new Date().getTime();
            const response = await fetch(`/admin/announcements/${id}/status/${status}?_=${timestamp}`, {
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
                await loadDataFromServer();
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
        const modalBody = document.getElementById('announcementEditModalBody');
        if (modalBody) {
            modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-3">Loading...</p></div>';
        }
        
        editModal.show();
        
        try {
            const timestamp = new Date().getTime();
            const response = await fetch(`/admin/announcements/${id}/edit?_=${timestamp}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
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
    const modalBody = document.getElementById('announcementEditModalBody');
    
    let formattedDate = '';
    if (data.date) {
        const date = new Date(data.date);
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
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="5" required>${escapeHtml(data.description)}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control" value="${formattedDate}" required>
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
            <input type="file" name="image" id="editImageInput" class="form-control" accept="image/*">
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
    
    // Attach image preview handler for new upload
    const editImageInput = document.getElementById('editImageInput');
    if (editImageInput) {
        // Remove existing listeners to prevent duplicates
        const newEditImageInput = editImageInput.cloneNode(true);
        editImageInput.parentNode.replaceChild(newEditImageInput, editImageInput);
        
        newEditImageInput.addEventListener('change', function(e) {
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
    
    const form = document.getElementById('announcementEditForm');
    form.action = `/admin/announcements/${data.id}`;
    form.enctype = 'multipart/form-data';
    form.dataset.id = data.id;
    
    preventEnterKeyOnForm(form);
}

async function handleRemoveImage(announcementId) {
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
        const timestamp = new Date().getTime();
        const response = await fetch(`/admin/announcements/${announcementId}/remove-image?_=${timestamp}`, {
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
            const itemIndex = allData.findIndex(item => item.id == announcementId);
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
        if (!confirm('⚠️ Are you sure you want to delete this announcement?\n\nThis action cannot be undone!')) {
            return;
        }
        
        try {
            const timestamp = new Date().getTime();
            const response = await fetch(`/admin/announcements/${id}?_=${timestamp}`, {
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
                showCustomToast(data.message || 'Failed to delete announcement', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error deleting announcement', 'error');
        }
    }
    
    function attachEventHandlers() {
        const searchInput = document.getElementById('announcementSearchInput');
        if (searchInput) {
            searchInput.removeEventListener('keyup', handleSearch);
            searchInput.addEventListener('keyup', handleSearch);
        }
        
        const statusFilter = document.getElementById('announcementStatusFilter');
        if (statusFilter) {
            statusFilter.removeEventListener('change', handleStatusFilter);
            statusFilter.addEventListener('change', handleStatusFilter);
        }
        
        const resetBtn = document.getElementById('resetAnnouncementFilters');
        if (resetBtn) {
            resetBtn.removeEventListener('click', handleReset);
            resetBtn.addEventListener('click', handleReset);
        }
        
        const rowsPerPageSelect = document.getElementById('announcementRowsPerPage');
        if (rowsPerPageSelect) {
            rowsPerPageSelect.removeEventListener('change', handleRowsPerPageChange);
            rowsPerPageSelect.addEventListener('change', handleRowsPerPageChange);
        }
        
        const addForm = document.getElementById('announcementAddForm');
        if (addForm) {
            addForm.removeEventListener('submit', handleAddFormSubmit);
            addForm.addEventListener('submit', handleAddFormSubmit);
            preventEnterKeyOnForm(addForm);
            
            const addModalCloseBtns = document.querySelectorAll('#announcementAddModal [data-bs-dismiss="modal"]');
            addModalCloseBtns.forEach(btn => {
                btn.removeEventListener('click', handleAddModalClose);
                btn.addEventListener('click', handleAddModalClose);
            });
        }
        
        const editForm = document.getElementById('announcementEditForm');
        if (editForm) {
            editForm.removeEventListener('submit', handleEditFormSubmit);
            editForm.addEventListener('submit', handleEditFormSubmit);
            preventEnterKeyOnForm(editForm);
        }
        
        const directUploadBtn = document.getElementById('announcementDirectUploadBtn');
        if (directUploadBtn) {
            directUploadBtn.removeEventListener('click', handleDirectUpload);
            directUploadBtn.addEventListener('click', handleDirectUpload);
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
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
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
                
                form.reset();
                await loadDataFromServer();
                
                // Force refresh images
                setTimeout(() => {
                    forceRefreshTableImages();
                }, 100);
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
    
    async function handleDirectUpload() {
        const announcementId = document.getElementById('announcementSelect').value;
        const imageFile = document.getElementById('announcementDirectImage').files[0];
        
        if (!announcementId) {
            showCustomToast('Please select an announcement', 'error');
            return;
        }
        
        if (!imageFile) {
            showCustomToast('Please select an image to upload', 'error');
            return;
        }
        
        const uploadBtn = document.getElementById('announcementDirectUploadBtn');
        const originalText = uploadBtn.innerHTML;
        
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
        
        const formData = new FormData();
        formData.append('id', announcementId);
        formData.append('image', imageFile);
        
        try {
            const response = await fetch('/admin/announcements/upload-direct', {
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
                showCustomToast('Image uploaded successfully!', 'success');
                
                if (directUploadModal) {
                    directUploadModal.hide();
                }
                
                document.getElementById('announcementDirectUploadForm').reset();
                document.getElementById('currentImagePreview').style.display = 'none';
                document.getElementById('currentImageDisplay').innerHTML = '';
                
                await loadDataFromServer();
                
                // Force refresh images
                setTimeout(() => {
                    forceRefreshTableImages();
                }, 100);
            } else {
                showCustomToast(data.message || 'Upload failed', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error uploading image', 'error');
        } finally {
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = originalText;
        }
    }
    
    async function loadAnnouncementsForDirectUpload() {
        const select = document.getElementById('announcementSelect');
        if (!select) return;
        
        select.innerHTML = '<option value="">Loading announcements...</option>';
        select.disabled = true;
        
        try {
            const timestamp = new Date().getTime();
            const response = await fetch(`/admin/announcements/all?_=${timestamp}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Cache-Control': 'no-cache'
                }
            });
            
            const data = await response.json();
            
            select.innerHTML = '<option value="">Select an announcement...</option>';
            
            data.forEach(announcement => {
                const option = document.createElement('option');
                option.value = announcement.id;
                option.textContent = `ID: ${announcement.id} - ${announcement.title.substring(0, 50)}${announcement.title.length > 50 ? '...' : ''} (${announcement.status})`;
                select.appendChild(option);
            });
            
            select.disabled = false;
            
            select.addEventListener('change', function() {
                const selectedId = this.value;
                if (selectedId) {
                    const selectedAnnouncement = data.find(a => a.id == selectedId);
                    if (selectedAnnouncement && selectedAnnouncement.image_url && !selectedAnnouncement.image_url.includes('default-image.png')) {
                        document.getElementById('currentImagePreview').style.display = 'block';
                        document.getElementById('currentImageDisplay').innerHTML = `<img src="${selectedAnnouncement.image_url}" style="max-width: 150px; border-radius: 4px; margin-top: 10px;">`;
                    } else {
                        document.getElementById('currentImagePreview').style.display = 'none';
                        document.getElementById('currentImageDisplay').innerHTML = '';
                    }
                } else {
                    document.getElementById('currentImagePreview').style.display = 'none';
                }
            });
            
        } catch (error) {
            console.error('Error loading announcements:', error);
            select.innerHTML = '<option value="">Error loading announcements</option>';
            select.disabled = false;
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
    
    function handleStatusFilter(e) {
        currentFilters.status = e.target.value;
        currentPage = 1;
        renderTable();
    }
    
    function handleReset(e) {
        document.getElementById('announcementSearchInput').value = '';
        document.getElementById('announcementStatusFilter').value = '';
        currentFilters = { search: '', status: '' };
        currentPage = 1;
        renderTable();
    }
    
    function handleRowsPerPageChange(e) {
        rowsPerPage = parseInt(e.target.value);
        currentPage = 1;
        renderTable();
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
    
    window.loadAnnouncementData = loadDataFromServer;
    console.log('Announcements.js initialized successfully');
})();