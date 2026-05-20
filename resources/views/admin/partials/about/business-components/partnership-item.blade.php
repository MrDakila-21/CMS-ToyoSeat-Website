{{-- resources/views/admin/partials/about/business-components/partnership-item.blade.php --}}
<div class="col-md-3 mb-4" data-id="{{ $partner->id }}">
    <div class="card h-100 text-center">
        @if($partner->image)
            <img src="{{ $partner->image_url }}" class="card-img-top p-3" alt="{{ $partner->title }}" style="height: 150px; object-fit: contain;">
        @endif
        <div class="card-body">
            <h6>{{ Str::limit($partner->title, 100) }}</h6>
            <button class="btn btn-sm btn-warning mt-2" onclick="editPartnership({{ $partner->id }})">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-sm btn-danger mt-2" onclick="deleteItem({{ $partner->id }})">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>