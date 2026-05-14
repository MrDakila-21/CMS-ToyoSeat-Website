{{-- resources/views/admin/partials/about/business-components/organization-item.blade.php --}}
<div class="col-md-4 mb-4" data-id="{{ $member->id }}">
    <div class="card h-100">
        @if($member->image)
            <img src="{{ Storage::url($member->image) }}" class="card-img-top" alt="{{ $member->name }}" style="height: 250px; object-fit: cover;">
        @endif
        <div class="card-body text-center">
            <h5>{{ $member->name }}</h5>
            <p class="text-muted">{{ $member->position }}</p>
            <button class="btn btn-sm btn-warning" onclick="editOrganization({{ $member->id }})">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $member->id }})">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>