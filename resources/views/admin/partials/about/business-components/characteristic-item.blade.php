{{-- resources/views/admin/partials/about/business-components/characteristic-item.blade.php --}}
<div class="card mb-3" data-id="{{ $char->id }}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                @if($char->image)
                    <img src="{{ Storage::url($char->image) }}" class="img-fluid rounded" alt="{{ $char->title }}">
                @endif
            </div>
            <div class="col-md-9">
                <h5>{{ $char->title }}</h5>
                <p>{{ $char->description }}</p>
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