{{-- resources/views/admin/partials/about/business-components/organization-item.blade.php --}}
<div class="card mb-3" data-id="{{ $member->id }}">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5>{{ $member->name }}</h5>
                        @if($member->position)
                            <p class="text-muted mb-2">{{ $member->position }}</p>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-sm btn-warning" onclick="editOrganization({{ $member->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $member->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                @if($member->image)
                <div class="mt-3">
                    <div class="alert alert-info">
                        <i class="fas fa-image me-2"></i> Org Chart Image uploaded: 
                        <strong>{{ $member->original_filename ?? basename($member->image) }}</strong>
                        <a href="{{ $member->image_url }}" target="_blank" class="btn btn-sm btn-link">View</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>