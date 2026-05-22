<div class="card content-card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>History Management</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#historyAddModal">
            <i class="fas fa-plus me-1"></i> Add New History Record
        </button>
    </div>
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           id="historySearchInput" 
                           class="form-control" 
                           placeholder="Search by title or description...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="historyYearFilter" class="form-select">
                    <option value="">All Years</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="historyStatusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="col-md-2">
                <button id="resetHistoryFilters" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </button>
            </div>
        </div>

        <!-- Loading indicator -->
        <div id="historyLoadingIndicator" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading data...</p>
        </div>

        <!-- Table container -->
        <div id="historyTableContainer"></div>
    </div>
</div>

<!-- Pagination Section -->
<div class="card-footer bg-white border-top">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Showing <span id="historyShowingStart">0</span> to <span id="historyShowingEnd">0</span> of <span id="historyTotalRecords">0</span> entries
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0" id="historyPagination"></ul>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small mb-0">Rows per page:</label>
            <select id="historyRowsPerPage" class="form-select form-select-sm" style="width: 75px;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="historyAddModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="historyAddForm" action="{{ route('admin.histories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New History Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        <small class="text-muted">Max size: 5MB. Allowed formats: JPEG, PNG, JPG, GIF, WEBP</small>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="#" alt="Preview" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="historyEditModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="historyEditForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit History Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="historyEditModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="historyViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View History Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="historyViewModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 350px;"></div>

<style>
    .table-responsive {
        overflow-x: auto;
        min-height: 400px;
    }
    #historyTable {
        min-width: 900px;
        margin-bottom: 0;
    }
    #historyTable tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    .status-select {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        width: 120px;
    }
    .pagination {
        gap: 5px;
        margin: 0;
    }
    .pagination .page-item .page-link {
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 13px;
        color: #0E334C;
        border: 1px solid #dee2e6;
    }
    .pagination .page-item.active .page-link {
        background: #3988BD;
        border-color: #3988BD;
        color: white;
    }
    .description-preview {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .history-image-preview {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
    }
    .history-image-preview:hover {
        opacity: 0.8;
    }
    
    /* Custom Toast Styles */
    .custom-toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-bottom: 10px;
        overflow: hidden;
        animation: slideInRight 0.3s ease;
        min-width: 280px;
        max-width: 350px;
    }
    .custom-toast.hide {
        animation: fadeOutRight 0.3s ease forwards;
    }
    .toast-content {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid;
    }
    .toast-content i {
        font-size: 18px;
    }
    .toast-content span {
        flex: 1;
        font-size: 14px;
        line-height: 1.4;
    }
    .success-toast .toast-content {
        border-left-color: #28a745;
        background: #d4edda;
        color: #155724;
    }
    .error-toast .toast-content {
        border-left-color: #dc3545;
        background: #f8d7da;
        color: #721c24;
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
    @keyframes fadeOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    .year-badge {
        background: #e9ecef;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #495057;
    }
</style>

<script>
(function() {
    'use strict';
    
    let addModal = null;
    let editModal = null;
    let viewModal = null;
    let allData = [];
    let currentPage = 1;
    let rowsPerPage = 10;
    let currentFilters = {
        search: '',
        year: '',
        status: ''
    };
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHistoryManagement);
    } else {
        initHistoryManagement();
    }
    
    function initHistoryManagement() {
        console.log('Initializing history management...');
        
        const addModalElement = document.getElementById('historyAddModal');
        if (addModalElement && typeof bootstrap !== 'undefined') {
            addModal = new bootstrap.Modal(addModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            
            addModalElement.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('historyAddForm');
                if (form) form.reset();
                document.getElementById('imagePreview').style.display = 'none';
            });
        }
        
        const editModalElement = document.getElementById('historyEditModal');
        if (editModalElement && typeof bootstrap !== 'undefined') {
            editModal = new bootstrap.Modal(editModalElement, {
                backdrop: 'static',
                keyboard: false
            });
        }
        
        const viewModalElement = document.getElementById('historyViewModal');
        if (viewModalElement && typeof bootstrap !== 'undefined') {
            viewModal = new bootstrap.Modal(viewModalElement);
        }
        
        loadDataFromServer();
        attachEventHandlers();
        
        // Image preview for add form
        const imageInput = document.querySelector('#historyAddForm input[name="image"]');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const preview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');
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
    }
    
    async function loadDataFromServer() {
        const loadingIndicator = document.getElementById('historyLoadingIndicator');
        const tableContainer = document.getElementById('historyTableContainer');
        
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (tableContainer) tableContainer.style.display = 'none';
        
        try {
            // Add timestamp to prevent AJAX caching
            const timestamp = Date.now();
            const response = await fetch(`/admin/histories/all?t=${timestamp}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            allData = await response.json();
            console.log(`Loaded ${allData.length} history records`);
            
            populateYearFilter();
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
    
    function populateYearFilter() {
        const yearFilter = document.getElementById('historyYearFilter');
        if (!yearFilter) return;
        
        const years = [...new Set(allData.map(item => {
            if (item.date) {
                return new Date(item.date).getFullYear();
            }
            return null;
        }).filter(year => year !== null))].sort((a, b) => b - a);
        
        while (yearFilter.options.length > 1) {
            yearFilter.remove(1);
        }
        
        years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        });
        
        console.log(`Populated ${years.length} years in filter`);
    }
    
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    function getYearFromDate(dateString) {
        if (!dateString) return null;
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return null;
        return date.getFullYear();
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
    
    function truncateDescription(description, maxLength = 80) {
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
        
        if (currentFilters.year) {
            filteredData = filteredData.filter(item => {
                const itemYear = getYearFromDate(item.date);
                return itemYear && itemYear.toString() === currentFilters.year;
            });
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
                <table class="table table-bordered table-striped align-middle" id="historyTable">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 200px;">Title</th>
                            <th style="width: 120px;">Date</th>
                            <th style="width: 100px;">Year</th>
                            <th>Description</th>
                            <th style="width: 100px;">Image</th>
                            <th style="width: 140px;">Created At</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        if (pageData.length === 0) {
            tableHtml += '<tr><td colspan="9" class="text-center text-muted py-4">No matching records found</td></tr>';
        } else {
            pageData.forEach(item => {
                // Use storage.php URL with cache-busting
                let imageSrc = '/images/default-image.png';
                if (item.image) {
                    // Add cache-busting using updated_at timestamp
                    const cacheBuster = item.updated_at ? new Date(item.updated_at).getTime() : Date.now();
                    imageSrc = `/storage.php?file=${encodeURIComponent(item.image)}&v=${cacheBuster}`;
                } else if (item.image_url && item.image_url !== '/images/default-image.png') {
                    imageSrc = item.image_url;
                }
                
                const imageHtml = `<img src="${imageSrc}" class="history-image-preview" alt="${escapeHtml(item.title)}" data-view-image="${imageSrc}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; cursor: pointer;">`;
                
                const statusSelect = `
                    <select class="form-select form-select-sm status-select" data-id="${item.id}">
                        <option value="published" ${item.status === 'published' ? 'selected' : ''}>Published</option>
                        <option value="archived" ${item.status === 'archived' ? 'selected' : ''}>Archived</option>
                    </select>
                `;
                
                const actions = `
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-info view-btn" data-id="${item.id}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning edit-btn" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger delete-btn" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                const formattedDate = formatDate(item.date);
                const itemYear = getYearFromDate(item.date);
                const formattedCreatedAt = formatDateTime(item.created_at);
                const truncatedDesc = truncateDescription(item.description, 80);
                
                tableHtml += `
                    <tr data-id="${item.id}" data-status="${item.status}">
                        <td class="text-center"><strong>${item.id}</strong></td>
                        <td><strong>${escapeHtml(item.title)}</strong></td>
                        <td>${formattedDate}</td>
                        <td class="text-center"><span class="year-badge">${itemYear || '-'}</span></td>
                        <td class="description-preview" title="${escapeHtml(item.description)}">${truncatedDesc}</td>
                        <td class="text-center">${imageHtml}</td>
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
        
        const container = document.getElementById('historyTableContainer');
        if (container) {
            container.innerHTML = tableHtml;
        }
        
        const showingStart = document.getElementById('historyShowingStart');
        const showingEnd = document.getElementById('historyShowingEnd');
        const totalRecordsSpan = document.getElementById('historyTotalRecords');
        
        if (showingStart) showingStart.textContent = totalRecords === 0 ? 0 : start + 1;
        if (showingEnd) showingEnd.textContent = Math.min(end, totalRecords);
        if (totalRecordsSpan) totalRecordsSpan.textContent = totalRecords;
        
        renderPagination(currentPage, totalPages);
        attachDynamicHandlers();
    }
    
    function renderPagination(currentPage, totalPages) {
        const paginationUl = document.getElementById('historyPagination');
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
        
        document.querySelectorAll('#historyPagination .page-link[data-pagination-link="true"]').forEach(link => {
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
                if (currentFilters.year) {
                    filteredData = filteredData.filter(item => {
                        const itemYear = getYearFromDate(item.date);
                        return itemYear && itemYear.toString() === currentFilters.year;
                    });
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
        
        document.querySelectorAll('.history-image-preview').forEach(img => {
            img.removeEventListener('click', handleImageView);
            img.addEventListener('click', handleImageView);
        });
        
        const container = document.getElementById('historyTableContainer');
        if (container) {
            container.removeEventListener('click', handleTableClick);
            container.addEventListener('click', handleTableClick);
        }
    }
    
    function handleImageView(e) {
        e.stopPropagation();
        let imageUrl = e.target.dataset.viewImage || e.target.src;
        
        // Ensure the image URL uses storage.php format if it's a stored image
        if (imageUrl && !imageUrl.startsWith('http') && !imageUrl.startsWith('/storage.php') && !imageUrl.startsWith('/images/')) {
            imageUrl = `/storage.php?file=${encodeURIComponent(imageUrl)}`;
        }
        
        if (imageUrl) {
            showImageModal(imageUrl);
        }
    }
    
    function handleTableClick(e) {
        const viewBtn = e.target.closest('.view-btn');
        if (viewBtn) {
            e.preventDefault();
            handleViewClick(viewBtn.dataset.id);
        }
        
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
            const response = await fetch(`/admin/histories/${id}/status/${status}`, {
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
    
    function handleViewClick(id) {
        const item = allData.find(i => i.id == id);
        if (!item) return;
        
        const modalBody = document.getElementById('historyViewModalBody');
        if (modalBody) {
            let imageSrc = '/images/default-image.png';
            if (item.image) {
                const cacheBuster = item.updated_at ? new Date(item.updated_at).getTime() : Date.now();
                imageSrc = `/storage.php?file=${encodeURIComponent(item.image)}&v=${cacheBuster}`;
            } else if (item.image_url && item.image_url !== '/images/default-image.png') {
                imageSrc = item.image_url;
            }
            
            const imageHtml = `
                <div class="text-center mb-3">
                    <img src="${imageSrc}" alt="${escapeHtml(item.title)}" style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            `;
            
            modalBody.innerHTML = `
                <div class="mb-3">
                    <label class="fw-bold">Title:</label>
                    <p class="mt-1">${escapeHtml(item.title)}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Date:</label>
                    <p class="mt-1">${formatDate(item.date)}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Year:</label>
                    <p class="mt-1"><span class="year-badge">${getYearFromDate(item.date) || '-'}</span></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Description:</label>
                    <p class="mt-1" style="white-space: pre-wrap;">${escapeHtml(item.description)}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Image:</label>
                    ${imageHtml}
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Status:</label>
                    <p class="mt-1"><span class="badge ${item.status === 'published' ? 'bg-success' : 'bg-secondary'}">${item.status}</span></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Created At:</label>
                    <p class="mt-1">${formatDateTime(item.created_at)}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Last Updated:</label>
                    <p class="mt-1">${formatDateTime(item.updated_at)}</p>
                </div>
            `;
        }
        
        if (viewModal) viewModal.show();
    }
    
    async function handleEditClick(id) {
    const modalBody = document.getElementById('historyEditModalBody');
    if (modalBody) {
        modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-3">Loading...</p></div>';
    }
    editModal.show();
    
    try {
        const response = await fetch(`/admin/histories/${id}/edit`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        console.log('Edit data received:', data);
        
        const formattedDate = data.date ? formatDate(data.date) : '';
        
        // FIX: Use storage.php URL for image display with cache-busting
        let imageSrc = '/images/default-image.png';
        let hasCustomImage = false;
        
        if (data.image) {
            // Use storage.php URL with cache-busting
            const cacheBuster = data.updated_at ? new Date(data.updated_at).getTime() : Date.now();
            imageSrc = `/storage.php?file=${encodeURIComponent(data.image)}&v=${cacheBuster}`;
            hasCustomImage = true;
        } else if (data.image_url && data.image_url !== '/images/default-image.png') {
            imageSrc = data.image_url;
            hasCustomImage = true;
        }
        
        console.log('Image source used in edit modal:', imageSrc);
        console.log('Has custom image:', hasCustomImage);
        
        modalBody.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="${escapeHtml(data.title)}" required maxlength="255">
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
            </div>
            <div class="mb-3">
                <label class="form-label">Change Image (Optional)</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                <small class="text-muted">Max size: 5MB. Leave empty to keep current image. Upload a new image to replace the current one.</small>
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
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                handleRemoveImage(data.id);
            });
        }
        
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
        
        const form = document.getElementById('historyEditForm');
        form.action = `/admin/histories/${data.id}`;
        
    } catch (error) {
        console.error('Error:', error);
        showCustomToast('Failed to load data', 'error');
        editModal.hide();
    }
}
    
    async function handleDeleteClick(id) {
        if (!confirm('⚠️ Are you sure you want to delete this history record?\n\nThis action cannot be undone!')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/histories/${id}`, {
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
                showCustomToast(data.message || 'Failed to delete history record', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showCustomToast('Network error deleting history record', 'error');
        }
    }
    
    function showImageModal(imageUrl) {
        // Ensure proper URL format
        let finalImageUrl = imageUrl;
        if (imageUrl && !imageUrl.startsWith('http') && !imageUrl.startsWith('/storage.php') && !imageUrl.startsWith('/images/')) {
            finalImageUrl = `/storage.php?file=${encodeURIComponent(imageUrl)}`;
        }
        
        const modalHtml = `
            <div class="modal fade" id="imageViewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Image Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${finalImageUrl}" alt="Full size image" style="max-width: 100%; max-height: 70vh;" 
                                 onerror="this.onerror=null; this.src='/images/default-image.png';">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const existingModal = document.getElementById('imageViewModal');
        if (existingModal) existingModal.remove();
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('imageViewModal'));
        modal.show();
        
        document.getElementById('imageViewModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }


    async function handleRemoveImage(historyId) {
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
        const response = await fetch(`/admin/histories/${historyId}/remove-image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
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
            
            // Update the global data to reflect image removal
            const itemIndex = allData.findIndex(item => item.id == historyId);
            if (itemIndex !== -1) {
                allData[itemIndex].image = null;
                allData[itemIndex].image_url = null;
                allData[itemIndex].updated_at = data.data.updated_at;
            }
            
            // Refresh the table to show updated image
            renderTable();
            
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
    
    function attachEventHandlers() {
        const searchInput = document.getElementById('historySearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                currentFilters.search = e.target.value.toLowerCase();
                currentPage = 1;
                renderTable();
            });
        }
        
        const yearFilter = document.getElementById('historyYearFilter');
        if (yearFilter) {
            yearFilter.addEventListener('change', function(e) {
                currentFilters.year = e.target.value;
                currentPage = 1;
                renderTable();
            });
        }
        
        const statusFilter = document.getElementById('historyStatusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function(e) {
                currentFilters.status = e.target.value;
                currentPage = 1;
                renderTable();
            });
        }
        
        const resetBtn = document.getElementById('resetHistoryFilters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                const searchInput = document.getElementById('historySearchInput');
                const yearFilter = document.getElementById('historyYearFilter');
                const statusFilter = document.getElementById('historyStatusFilter');
                if (searchInput) searchInput.value = '';
                if (yearFilter) yearFilter.value = '';
                if (statusFilter) statusFilter.value = '';
                currentFilters = { search: '', year: '', status: '' };
                currentPage = 1;
                renderTable();
            });
        }
        
        const rowsPerPageSelect = document.getElementById('historyRowsPerPage');
        if (rowsPerPageSelect) {
            rowsPerPageSelect.addEventListener('change', function(e) {
                rowsPerPage = parseInt(e.target.value);
                currentPage = 1;
                renderTable();
            });
        }
        
        const addForm = document.getElementById('historyAddForm');
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
                        if (addModal) addModal.hide();
                        this.reset();
                        document.getElementById('imagePreview').style.display = 'none';
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
        
        const editForm = document.getElementById('historyEditForm');
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
                        if (editModal) editModal.hide();
                        // Force reload data from server
                        await loadDataFromServer();
                        // Clear image cache for this specific record
                        if (data.data && data.data.id) {
                            clearImageCache(data.data.id);
                        }
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
        const container = document.getElementById('toastContainer');
        if (!container) return;
        
        const toastId = 'toast_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type === 'success' ? 'success-toast' : 'error-toast'}`;
        toast.id = toastId;
        
        const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
        
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${icon}"></i>
                <span>${escapeHtml(message)}</span>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }, 5000);
    }
    
    function clearImageCache(historyId) {
        // Find the history item and update its data
        const item = allData.find(i => i.id == historyId);
        if (item && item.updated_at) {
            // Force refresh of updated_at to bust cache
            const cacheBuster = Date.now();
            if (item.image) {
                // Preload the new image with cache-busting
                const newImageUrl = `/storage.php?file=${encodeURIComponent(item.image)}&v=${cacheBuster}`;
                const img = new Image();
                img.src = newImageUrl;
            }
        }
    }
    
    window.loadHistoryData = loadDataFromServer;
    console.log('History.js initialized');
})();
</script>