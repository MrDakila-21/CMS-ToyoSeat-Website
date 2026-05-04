<div class="card content-card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="fas fa-photo-film me-2"></i>Events &amp; Activities Management</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mediaAddModal">
            <i class="fas fa-plus me-1"></i> Add New
        </button>
    </div>
    <div class="card-body">
        @if(isset($events) && $events->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th style="width: 90px;">Image</th>
                        <th>Title</th>
                        <th style="width: 110px;">Type</th>
                        <th style="width: 120px;">Date</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 170px;">Created At</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->image && Storage::disk('public')->exists($item->image))
                                    <img src="{{ Storage::url($item->image) }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td>{{ $item->title }}</td>
                            <td>
                                @if($item->type === 'event')
                                    <span class="badge bg-primary">Event</span>
                                @else
                                    <span class="badge bg-success">Activity</span>
                                @endif
                            </td>
                            <td>{{ optional($item->event_date)->format('Y-m-d') }}</td>
                            <td>
                                <select class="form-select form-select-sm status-select" data-id="{{ $item->id }}">
                                    <option value="published" {{ $item->status === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft" {{ $item->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="archived" {{ $item->status === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </td>
                            <td>{{ optional($item->created_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning edit-btn" data-id="{{ $item->id }}">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            No events or activities found. Click "Add New" to create your first entry.
        </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="mediaAddModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="mediaAddForm" action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Event/Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="event">Event</option>
                            <option value="activity">Activity</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text">Max size: 2MB. Allowed: JPG, PNG, GIF, WEBP</div>
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
<div class="modal fade" id="mediaEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="mediaEditForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event/Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mediaEditModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include the external JavaScript file -->
<script src="{{ asset('js/admin/media.js') }}"></script>