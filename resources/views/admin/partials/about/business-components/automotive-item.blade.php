{{-- resources/views/admin/partials/about/business-components/automotive-item.blade.php --}}
<div class="card mb-3" data-id="{{ $item->id }}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                @if($item->image)
                    <img src="{{ $item->image_url }}" class="img-fluid rounded" alt="{{ $item->title }}">
                @else
                    <div class="bg-light text-center p-4 rounded">No Image</div>
                @endif
            </div>
            <div class="col-md-9">
                <h5>{{ $item->title }}</h5>
                <p>{{ Str::limit($item->description, 200) }}</p>
                <button class="btn btn-sm btn-warning" onclick="editAutomotive({{ $item->id }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $item->id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>