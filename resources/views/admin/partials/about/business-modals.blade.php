{{-- resources/views/admin/partials/about/business-modals.blade.php --}}

<!-- Automotive Modal -->
<div class="modal fade" id="automotiveModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
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
                    <div class="mb-3" id="automotiveCurrentImageContainer" style="display: none;">
                        <label>Current Image</label>
                        <div class="current-image-wrapper position-relative d-inline-block">
                            <img id="automotiveCurrentImage" src="" class="img-fluid rounded" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeCurrentImage('automotive')" style="border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="mt-1">
                                <small class="text-muted" id="automotiveCurrentImageName"></small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image. Recommended size: 800x600px</small>
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
                <h5 class="modal-title" id="organizationModalLabel">Add Organization Chart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="organizationForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="organizationId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" id="organization_title" name="title" placeholder="Enter chart title (e.g., Company Organization Chart 2024)">
                        <small class="text-muted">Example: Organizational Structure, Management Team, etc. Leave blank if not needed.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Organization Chart Image</label>
                        <input type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/gif" id="organization_image">
                        <div class="form-text">Max 2MB. Allowed: JPG, PNG, GIF. Recommended: Upload organization chart image.</div>
                        
                        <div id="organizationCurrentImageContainer" class="mt-2" style="display: none;">
                            <label class="form-label small text-muted">Current Image:</label>
                            <div class="current-image-wrapper position-relative d-inline-block">
                                <img id="organizationCurrentImage" src="" alt="Current" class="img-thumbnail" style="max-height: 150px;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeCurrentImage('organization')" style="border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <p class="small text-muted mt-1" id="organizationCurrentImageName"></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="organizationForm" class="btn btn-primary">Save Organization Chart</button>
            </div>
        </div>
    </div>
</div>

<!-- Characteristic Modal -->
<div class="modal fade" id="characteristicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <label>Subtitle <span class="text-muted">(Optional)</span></label>
                        <input type="text" name="subtitle" id="characteristic_subtitle" class="form-control" placeholder="e.g., Industry Leader, Innovation Driven, Quality Focused">
                        <small class="text-muted">A short supporting text that appears below the title</small>
                    </div>
                    <div class="mb-3">
                        <label>Description *</label>
                        <textarea name="description" id="characteristic_description" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3" id="characteristicCurrentImageContainer" style="display: none;">
                        <label>Current Image/Icon</label>
                        <div class="current-image-wrapper position-relative d-inline-block">
                            <img id="characteristicCurrentImage" src="" class="img-fluid rounded" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeCurrentImage('characteristic')" style="border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="mt-1">
                                <small class="text-muted" id="characteristicCurrentImageName"></small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Image/Icon</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image. Recommended size: 200x200px</small>
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
<div class="modal fade" id="partnershipModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
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
                    <div class="mb-3" id="partnershipCurrentImageContainer" style="display: none;">
                        <label>Current Logo</label>
                        <div class="current-image-wrapper position-relative d-inline-block">
                            <img id="partnershipCurrentImage" src="" class="img-fluid rounded" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeCurrentImage('partnership')" style="border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="mt-1">
                                <small class="text-muted" id="partnershipCurrentImageName"></small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Logo</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image. Recommended size: 200x200px</small>
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