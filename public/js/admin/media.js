/**
 * Media Management Module
 * Handles all event/activity management functionality
 */

(function() {
    'use strict';
    
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
        
        const editModalElement = document.getElementById('mediaEditModal');
        if (editModalElement && typeof bootstrap !== 'undefined') {
            editModal = new bootstrap.Modal(editModalElement);
        }
        
        loadDataFromServer();
        attachEventHandlers();
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
    
    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return '-';
        
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        
        return `${year}-${month}-${day}`;
    }
    
    // Helper function to format datetime
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
        // Filter data
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
        
        // Paginate
        const totalRecords = filteredData.length;
        const totalPages = Math.ceil(totalRecords / rowsPerPage);
        
        if (currentPage > totalPages) currentPage = totalPages || 1;
        if (currentPage < 1) currentPage = 1;
        
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageData = filteredData.slice(start, end);
        
        // Build table HTML
        let tableHtml = `
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" id="mediaTable">
                    <thead>
                        <tr>
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
            tableHtml += '<tr><td colspan="7" class="text-center text-muted py-4">No matching records found</td></tr>';
        } else {
            pageData.forEach(item => {
                const imageHtml = item.image_url 
                    ? `<img src="${item.image_url}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">`
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
                    <button type="button" class="btn btn-sm btn-warning edit-btn" data-id="${item.id}">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                `;
                
                // Format dates properly
                const formattedEventDate = formatDate(item.event_date);
                const formattedCreatedAt = formatDateTime(item.created_at);
                
                tableHtml += `
                    <tr data-id="${item.id}" data-type="${item.type}" data-status="${item.status}">
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
        
        // Update pagination info
        document.getElementById('showingStart').textContent = totalRecords === 0 ? 0 : start + 1;
        document.getElementById('showingEnd').textContent = Math.min(end, totalRecords);
        document.getElementById('totalRecords').textContent = totalRecords;
        
        // Render pagination
        renderPagination(currentPage, totalPages);
        
        // Attach event handlers to new elements
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
        
        // Previous button
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}" data-pagination-link="true">&laquo; Previous</a>
        </li>`;
        
        // Page numbers - show limited pages
        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage + 1 < maxVisible) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        // First page button if needed
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1" data-pagination-link="true">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}" data-pagination-link="true">${i}</a>
            </li>`;
        }
        
        // Last page button if needed
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}" data-pagination-link="true">${totalPages}</a></li>`;
        }
        
        // Next button
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}" data-pagination-link="true">Next &raquo;</a>
        </li>`;
        
        paginationUl.innerHTML = html;
        
        // Attach click handlers to pagination links
        attachPaginationHandlers();
    }
    
    function attachPaginationHandlers() {
        // Get all pagination links
        const paginationLinks = document.querySelectorAll('#tablePagination .page-link[data-pagination-link="true"]');
        
        paginationLinks.forEach(link => {
            // Remove any existing listeners to avoid duplicates
            link.removeEventListener('click', handlePaginationClick);
            // Add new click listener
            link.addEventListener('click', handlePaginationClick);
        });
        
        console.log(`Attached pagination handlers to ${paginationLinks.length} links`);
    }
    
    function handlePaginationClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const page = parseInt(this.getAttribute('data-page'));
        
        // Get current total pages
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
        
        // Validate page number
        if (isNaN(page)) return;
        if (page < 1 || page > totalPages) return;
        if (page === currentPage) return;
        
        console.log(`Changing page from ${currentPage} to ${page}`);
        
        // Update current page and re-render
        currentPage = page;
        renderTable();
    }
    
    function attachDynamicHandlers() {
        // Status change handlers
        document.querySelectorAll('.status-select').forEach(select => {
            select.removeEventListener('change', handleStatusChange);
            select.addEventListener('change', handleStatusChange);
        });
        
        // Edit/Delete buttons (using event delegation on container)
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
                // Update data in allData array
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
        // Format date for input field (YYYY-MM-DD)
        let formattedDate = '';
        if (data.event_date) {
            const date = new Date(data.event_date);
            if (!isNaN(date.getTime())) {
                formattedDate = date.toISOString().split('T')[0];
            }
        }
        
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
                ${data.image_url ? `<img src="${data.image_url}" style="max-width: 200px; display: block; margin-bottom: 10px; border-radius: 4px;">` : '<p class="text-muted">No image uploaded</p>'}
                <label class="form-label mt-2">Change Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="form-text text-muted">Max size: 2MB. Leave empty to keep current image.</small>
            </div>
        `;
        
        const form = document.getElementById('mediaEditForm');
        form.action = `/admin/media/${data.id}`;
        form.enctype = 'multipart/form-data';
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
        // Search input
        const searchInput = document.getElementById('tableSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                currentFilters.search = this.value.toLowerCase();
                currentPage = 1;
                renderTable();
            });
        }
        
        // Type filter
        const typeFilter = document.getElementById('typeFilter');
        if (typeFilter) {
            typeFilter.addEventListener('change', function() {
                currentFilters.type = this.value;
                currentPage = 1;
                renderTable();
            });
        }
        
        // Status filter
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                currentFilters.status = this.value;
                currentPage = 1;
                renderTable();
            });
        }
        
        // Reset button
        const resetBtn = document.getElementById('resetFilters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                document.getElementById('tableSearchInput').value = '';
                document.getElementById('typeFilter').value = '';
                document.getElementById('statusFilter').value = '';
                currentFilters = { search: '', type: '', status: '' };
                currentPage = 1;
                renderTable();
            });
        }
        
        // Rows per page
        const rowsPerPageSelect = document.getElementById('rowsPerPage');
        if (rowsPerPageSelect) {
            rowsPerPageSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });
        }
        
        // Form submissions
        const addForm = document.getElementById('mediaAddForm');
        if (addForm) {
            addForm.removeEventListener('submit', handleFormSubmit);
            addForm.addEventListener('submit', handleFormSubmit);
        }
        
        const editForm = document.getElementById('mediaEditForm');
        if (editForm) {
            editForm.removeEventListener('submit', handleFormSubmit);
            editForm.addEventListener('submit', handleFormSubmit);
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
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
                
                // Close modal
                const modal = form.closest('.modal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();
                
                // Reload data
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
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = `custom-toast alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.style.minWidth = '250px';
        toast.style.animation = 'slideInRight 0.3s ease-out';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'} me-2"></i>
                <span>${escapeHtml(message)}</span>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 3000);
    }
    
    // Add CSS animation for toast
    const style = document.createElement('style');
    style.textContent = `
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
        .custom-toast {
            animation: slideInRight 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);
    
    window.loadMediaData = loadDataFromServer;
    console.log('Media.js initialized successfully');
})();