{{-- resources/views/admin/partials/about/business-components/organization-item.blade.php --}}
<div class="col-md-6 mb-4" data-id="{{ $member->id }}">
    <div class="card h-100">
        @if($member->image)
            <img src="{{ $member->image_url }}" class="card-img-top" alt="{{ $member->title ?? 'Organization Chart' }}" style="width: 100%; object-fit: contain; padding: 20px;">
        @else
            <div class="bg-light text-center p-5">
                <i class="fas fa-building fa-4x text-muted"></i>
                <p class="mt-2 text-muted">No Image Uploaded</p>
            </div>
        @endif
        <div class="card-body text-center">
            @if($member->title)
                <h5>{{ $member->title }}</h5>
            @else
                <h5 class="text-muted">Organization Chart</h5>
            @endif
            <button class="btn btn-sm btn-warning" onclick="editOrganization({{ $member->id }})">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $member->id }})">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>