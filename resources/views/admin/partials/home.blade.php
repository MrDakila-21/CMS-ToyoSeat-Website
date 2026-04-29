<div>
    <div class="alert alert-success">
        <h4 class="alert-heading">Welcome, Admin!</h4>
        <p>You have successfully logged in to the admin panel.</p>
        <hr>
        <p class="mb-0">Manage your homepage background image below.</p>
    </div>
    
    <!-- Homepage Background Image Management Card -->
    <div class="card content-card">
        <div class="card-header">
            <h5><i class="fas fa-image me-2"></i>Homepage Background Image</h5>
        </div>
        <div class="card-body">
            <form id="homepageImageForm" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Background Image</label>
                            <div id="currentImagePreview" class="border rounded p-2 text-center" style="min-height: 200px; background-color: #f8f9fa;">
                                <img id="previewImg" src="" alt="Current Background" style="max-width: 100%; max-height: 200px; display: none;">
                                <div id="noImagePlaceholder" class="text-muted py-5">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p>No background image uploaded yet</p>
                                    <small>Default GIF will be shown on website</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload New Background Image</label>
                            <input type="file" class="form-control" id="backgroundImage" name="background_image" accept="image/jpeg,image/png,image/gif,image/webp">
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle"></i> Accepted formats: JPG, PNG, GIF, WEBP. Max size: 5MB
                            </div>
                        </div>
                        
                        <div class="mb-3" id="newImagePreviewContainer" style="display: none;">
                            <label class="form-label fw-bold">New Image Preview</label>
                            <div class="border rounded p-2 text-center" style="min-height: 150px; background-color: #f8f9fa; position: relative;">
                                <img id="newPreviewImg" src="" alt="New Image Preview" style="max-width: 100%; max-height: 150px;">
                                <button type="button" id="removeNewImageBtn" class="btn btn-sm btn-danger mt-2" style="position: absolute; top: 5px; right: 5px;">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                <i class="fas fa-upload me-1"></i> Upload/Update Background Image
                            </button>
                            <button type="button" class="btn btn-danger ms-2" id="removeImageBtn">
                                <i class="fas fa-trash-alt me-1"></i> Remove Background Image
                            </button>
                            <a href="{{ url('/') }}" class="btn btn-secondary ms-2" target="_blank">
                                <i class="fas fa-eye me-1"></i> Preview Website
                            </a>
                        </div>

