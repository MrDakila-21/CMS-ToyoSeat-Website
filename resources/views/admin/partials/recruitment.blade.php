<div class="card content-card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Recruitment Management</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recruitmentAddModal">
            <i class="fas fa-plus me-1"></i> Add New Job Post
        </button>
    </div>
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           id="recruitmentSearchInput" 
                           class="form-control" 
                           placeholder="Search by title or description...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="recruitmentStatusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="col-md-2">
                <button id="resetRecruitmentFilters" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </button>
            </div>
        </div>

        <!-- Loading indicator -->
        <div id="recruitmentLoadingIndicator" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading data...</p>
        </div>

        <!-- Table container -->
        <div id="recruitmentTableContainer"></div>
    </div>
</div>

<!-- Pagination Section -->
<div class="card-footer bg-white border-top">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Showing <span id="recruitmentShowingStart">0</span> to <span id="recruitmentShowingEnd">0</span> of <span id="recruitmentTotalRecords">0</span> entries
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0" id="recruitmentPagination"></ul>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small mb-0">Rows per page:</label>
            <select id="recruitmentRowsPerPage" class="form-select form-select-sm" style="width: 75px;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="recruitmentAddModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="recruitmentAddForm" action="{{ route('admin.recruitments.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Job Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Job Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
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
<div class="modal fade" id="recruitmentEditModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="recruitmentEditForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="recruitmentEditModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
        min-height: 400px;
    }
    #recruitmentTable {
        min-width: 800px;
        margin-bottom: 0;
    }
    #recruitmentTable tbody tr:hover {
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
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<script src="{{ asset('js/admin/recruitments.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof loadRecruitmentData === 'function') {
            loadRecruitmentData();
        }
    });
</script>