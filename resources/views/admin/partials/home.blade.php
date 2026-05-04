<div class="content-card">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-images me-2"></i>Homepage Slideshow Management</h5>
        </div>
        <div class="card-body">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Upload Section -->
            <div class="mb-4">
                <h6 class="mb-3"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Multiple Images</h6>
                <form action="{{ route('admin.homepage.uploadMultiple') }}" method="POST" enctype="multipart/form-data" id="multipleImagesForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="multipleImages" class="form-label">Select Images (Max 10, up to 5MB each)</label>
                                <input type="file" class="form-control" id="multipleImages" name="images[]" multiple accept="image/jpeg,image/png,image/gif,image/webp" required>
                                <div class="form-text">You can select multiple images at once. Hold Ctrl/Cmd to select multiple.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100" id="uploadMultipleBtn">
                                    <i class="fas fa-upload me-1"></i> Upload Images
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <hr class="my-4">

            <!-- Manage Slideshow Section -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Manage Slideshow Images</h6>
                    <form action="{{ route('admin.homepage.present') }}" method="POST" id="presentSlidesForm">
                        @csrf
                        <button type="submit" class="btn btn-success" id="presentSlidesBtn">
                            <i class="fas fa-check-circle me-1"></i> Present Selected Images/Save
                        </button>
                    </form>
                </div>
                
                <div id="slidesContainer" class="row">
                    @php
                        use App\Models\HomepageSlide;
                        $slides = HomepageSlide::orderBy('order', 'asc')->get();
                    @endphp
                    
                    @if($slides->count() > 0)
                        @foreach($slides as $index => $slide)
                            <div class="col-md-3 col-sm-6 mb-3 slide-item" data-id="{{ $slide->id }}" data-order="{{ $slide->order }}">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $slide->image_path) }}" class="card-img-top" alt="Slide {{ $index + 1 }}" style="height: 150px; object-fit: cover; width: 100%;">
                                        <div class="position-absolute top-0 inset-e-0 m-2">
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input slide-checkbox" 
                                                       name="slide_ids[]" 
                                                       value="{{ $slide->id }}"
                                                       form="presentSlidesForm"
                                                       {{ $slide->is_active ? 'checked' : '' }}
                                                       style="width: 20px; height: 20px;">
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.homepage.deleteSlide', $slide->id) }}" method="POST" class="d-inline delete-slide-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm position-absolute top-0 inset-e-0 m-2" onclick="return confirm('Are you sure you want to delete this image?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @if($slide->is_active)
                                            <span class="position-absolute bottom-0 inset-e-0 m-2 badge bg-success">
                                                <i class="fas fa-play me-1"></i>Active
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted">Order: {{ $slide->order }}</small>
                                        <div class="mt-1">
                                            <small class="text-info">
                                                <i class="fas fa-arrows-alt"></i> Drag to reorder (Coming Soon)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No images uploaded yet. Use the upload form above to add images.
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Instructions:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Check the images you want to present in the slideshow</li>
                        <li>Click "Present Selected Images" to update the homepage slideshow</li>
                        <li>The order of checked images in the database determines slide sequence</li>
                        <li>If no images are selected, the default background image will be used</li>
                        <li>To reorder images, you'll need to use the database or we can implement a reorder feature</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simple confirmation for form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for present form if no images selected
    const presentForm = document.getElementById('presentSlidesForm');
    if (presentForm) {
        presentForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.slide-checkbox:checked');
            if (checkedBoxes.length === 0) {
                if (!confirm('No images selected. This will clear the slideshow and use the default background image on the homepage. Are you sure?')) {
                    e.preventDefault();
                }
            } else {
                if (!confirm(`Are you sure you want to present ${checkedBoxes.length} image(s) as the slideshow?`)) {
                    e.preventDefault();
                }
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>