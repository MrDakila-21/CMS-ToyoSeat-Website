<div class="card mb-3" data-id="{{ $char->id }}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                @if($char->image)
                    <img src="{{ $char->image_url }}" class="img-fluid rounded w-100" alt="{{ $char->title }}" style="object-fit: cover; height: 150px;">
                @else
                    <div class="bg-light text-center p-4 rounded w-100 d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
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