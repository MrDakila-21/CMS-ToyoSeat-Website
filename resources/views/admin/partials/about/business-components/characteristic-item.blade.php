{{-- resources/views/admin/partials/about/business-components/characteristic-item.blade.php --}}
<div class="card mb-3" data-id="{{ $char->id }}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 d-flex align-items-center justify-content-center">
                @if($char->image && $char->image_url)
                    <img src="{{ $char->image_url }}" class="img-fluid rounded" alt="{{ $char->title }}" style="object-fit: contain; max-height: 120px; width: auto;">
                @else
                    <img src="/images/default-image.png" class="img-fluid rounded" alt="Default Image" style="object-fit: contain; max-height: 120px; width: auto; background: #f8f9fa;">
                @endif
            </div>
            <div class="col-md-9">
                <h5>{{ $char->title }}</h5>
                @if($char->subtitle)
                    <h6 class="text-muted mb-2">{{ $char->subtitle }}</h6>
                @endif
                <p>{{ Str::limit($char->description, 100) }}</p>
                <button class="btn btn-sm btn-warning" onclick="editCharacteristic({{ $char->id }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $char->id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>