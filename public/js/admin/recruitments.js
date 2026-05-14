(function() {
    'use strict';
    
    let addModal = null;
    let editModal = null;
    let allData = [];
    let currentPage = 1;
    let rowsPerPage = 10;
    let currentFilters = {
        search: '',
        status: ''
    };
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRecruitmentManagement);
    } else {
        initRecruitmentManagement();
    }
    
    function initRecruitmentManagement() {
        console.log('Initializing recruitment management...');
        
        const addModalElement = document.getElementById('recruitmentAddModal');
        if (addModalElement && typeof bootstrap !== 'undefined') {
            addModal = new bootstrap.Modal(addModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            addModalElement.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('recruitmentAddForm');
                if (form) form.reset();
            });
        }
        
        const editModalElement = document.getElementById('recruitmentEditModal');
        if (editModalElement && typeof bootstrap !== 'undefined') {
            editModal = new bootstrap.Modal(editModalElement, {
                backdrop: 'static',
                keyboard: false
            });
        }
        
        loadDataFromServer();
        attachEventHandlers();
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.closest('.modal')) {
                if (e.target.tagName.toLowerCase() !== 'textarea') {
                    e.preventDefault();
                }
            }
        });
    }
    
    async function loadDataFromServer() {
        const loadingIndicator = document.getElementById('recruitmentLoadingIndicator');
        const tableContainer = document.getElementById('recruitmentTableContainer');
        
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (tableContainer) tableContainer.style.display = 'none';
        
        try {
            const response = await fetch('/admin/recruitments/all', {
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
    
    function truncateDescription(description, maxLength = 100) {
        if (!description) return '';
        if (description.length <= maxLength) return escapeHtml(description);
        return escapeHtml(description.substring(0, maxLength)) + '...';
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
                <table class="table table-bordered table-striped align-middle" id="recruitmentTable">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Job Title</th>
                            <th>Description</th>
                            <th style="width: 140px;">Created At</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        if (pageData.length === 0) {
            tableHtml += '<tr><td colspan="6" class="text-center text-muted py-4">No matching records found</td></tr>';
        } else {
            pageData.forEach(item => {
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
                
                const formattedCreatedAt = formatDateTime(item.created_at);
                const truncatedDesc = truncateDescription(item.description, 80);
                
                tableHtml += `
                    <tr data-id="${item.id}" data-status="${item.status}">
                        <td class="text-center"><strong>${item.id}</strong></td>
                        <td><strong>${escapeHtml(item.title)}</strong></td>
                        <td class="description-preview" title="${escapeHtml(item.description)}">${truncatedDesc}</td>
                        <td>${formattedCreatedAt}</td>
                        <td>${statusSelect}</td>
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
        
        document.getElementById('recruitmentTableContainer').innerHTML = tableHtml;
        
        document.getElementById('recruitmentShowingStart').textContent = totalRecords === 0 ? 0 : start + 1;
        document.getElementById('recruitmentShowingEnd').textContent = Math.min(end, totalRecords);
        document.getElementById('recruitmentTotalRecords').textContent = totalRecords;
        
        renderPagination(currentPage, totalPages);
        attachDynamicHandlers();
    }
    
    function renderPagination(currentPage, totalPages) {
        const paginationUl = document.getElementById('recruitmentPagination');
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
            if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        
        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}" data-pagination-link="true">${i}</a>
            </li>`;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}" data-pagination-link="true">${totalPages}</a></li>`;
        }
        
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}" data-pagination-link="true">Next &raquo;</a>
        </li>`;
        
        paginationUl.innerHTML = html;
        
        document.querySelectorAll('#recruitmentPagination .page-link[data-pagination-link="true"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                let filteredData = [...allData];
                if (currentFilters.search) {
                    filteredData = filteredData.filter(item => 
                        item.title.toLowerCase().includes(currentFilters.search) || 
                        item.description.toLowerCase().includes(currentFilters.search)
                    );
                }
                if (currentFilters.status) {
                    filteredData = filteredData.filter(item => item.status === currentFilters.status);
                }
                const totalPages = Math.ceil(filteredData.length / rowsPerPage);
                if (page >= 1 && page <= totalPages && page !== currentPage) {
                    currentPage = page;
                    renderTable();
                }
            });
        });
    }
    
    function attachDynamicHandlers() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.removeEventListener('change', handleStatusChange);
            select.addEventListener('change', handleStatusChange);
        });
        
        const container = document.getElementById('recruitmentTableContainer');
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
            const response = await fetch(`/admin/recruitments/${id}/status/${status}`, {
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
                if (itemIndex !== -1) allData[itemIndex].status = status;
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
        const modalBody = document.getElementById('recruitmentEditModalBody');
        if (modalBody) {
            modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-3">Loading...</p></div>';
        }
        editModal.show();
        
        try {
            const response = await fetch(`/admin/recruitments/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            modalBody.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Job Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="${escapeHtml(data.title)}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5" required>${escapeHtml(data.description)}</textarea>
                </div>
            `;
            const form = document.getElementById('recruitmentEditForm');
            form.action = `/admin/recruitments/${data.id}`;
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Failed to load data', 'error');
            editModal.hide();
        }
    }
    
    async function handleDeleteClick(id) {
        if (!confirm('⚠️ Are you sure you want to delete this job post?\n\nThis action cannot be undone!')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/recruitments/${id}`, {
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
                showCustomToast(data.message || 'Failed to delete job post', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error deleting job post', 'error');
        }
    }
    
    function attachEventHandlers() {
        const searchInput = document.getElementById('recruitmentSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                currentFilters.search = e.target.value.toLowerCase();
                currentPage = 1;
                renderTable();
            });
        }
        
        const statusFilter = document.getElementById('recruitmentStatusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function(e) {
                currentFilters.status = e.target.value;
                currentPage = 1;
                renderTable();
            });
        }
        
        const resetBtn = document.getElementById('resetRecruitmentFilters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                document.getElementById('recruitmentSearchInput').value = '';
                document.getElementById('recruitmentStatusFilter').value = '';
                currentFilters = { search: '', status: '' };
                currentPage = 1;
                renderTable();
            });
        }
        
        const rowsPerPageSelect = document.getElementById('recruitmentRowsPerPage');
        if (rowsPerPageSelect) {
            rowsPerPageSelect.addEventListener('change', function(e) {
                rowsPerPage = parseInt(e.target.value);
                currentPage = 1;
                renderTable();
            });
        }
        
        const addForm = document.getElementById('recruitmentAddForm');
        if (addForm) {
            addForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                
                try {
                    const formData = new FormData(this);
                    const response = await fetch(this.action, {
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
                        addModal.hide();
                        this.reset();
                        await loadDataFromServer();
                    } else {
                        showCustomToast(data.message || 'Failed to save', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showCustomToast('Network error saving data', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
        
        const editForm = document.getElementById('recruitmentEditForm');
        if (editForm) {
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                
                try {
                    const formData = new FormData(this);
                    const response = await fetch(this.action, {
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
                        editModal.hide();
                        this.reset();
                        await loadDataFromServer();
                    } else {
                        showCustomToast(data.message || 'Failed to save', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showCustomToast('Network error saving data', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
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
        toast.className = `floating-toast ${type === 'success' ? 'success-toast' : 'error-toast'}`;
        
        const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
        
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
    
    window.loadRecruitmentData = loadDataFromServer;
    console.log('Recruitments.js initialized');
})();