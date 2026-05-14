{{-- resources/views/admin/partials/about/business-modals.blade.php --}}

<!-- Automotive Modal -->
<div class="modal fade" id="automotiveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="automotiveModalLabel">Add Automotive Seat Cover</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="automotiveForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="automotiveId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title *</label>
                        <input type="text" name="title" id="automotive_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description *</label>
                        <textarea name="description" id="automotive_description" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended size: 800x600px</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Organization Modal -->
<div class="modal fade" id="organizationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="organizationModalLabel">Add Organization Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="organizationForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="organizationId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name *</label>
                        <input type="text" name="name" id="organization_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Position *</label>
                        <input type="text" name="position" id="organization_position" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Photo</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended size: 400x400px</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Characteristic Modal -->
<div class="modal fade" id="characteristicModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="characteristicModalLabel">Add Characteristic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="characteristicForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="characteristicId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title *</label>
                        <input type="text" name="title" id="characteristic_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description *</label>
                        <textarea name="description" id="characteristic_description" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image/Icon</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended size: 200x200px</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Partnership Modal -->
<div class="modal fade" id="partnershipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="partnershipModalLabel">Add Partnership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="partnershipForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="partnershipId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title/Company Name *</label>
                        <input type="text" name="title" id="partnership_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Logo</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended size: 200x200px</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>