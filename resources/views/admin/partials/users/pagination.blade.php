@if ($users->hasPages())
    <nav>
        <ul class="pagination justify-content-end">
            {{-- Previous Page Link --}}
            @if ($users->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <button class="page-link" onclick="loadPage({{ $users->currentPage() - 1 }})">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($users->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $users->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item">
                                <button class="page-link" onclick="loadPage({{ $page }})">{{ $page }}</button>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($users->hasMorePages())
                <li class="page-item">
                    <button class="page-link" onclick="loadPage({{ $users->currentPage() + 1 }})">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif

<script>
function loadPage(page) {
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    
    fetch(`/admin/users/filter?status=${status}&type=${type}&page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('users-table-body').innerHTML = data.html;
            document.getElementById('pagination-links').innerHTML = data.pagination;
        } else {
            showToast('error', 'Failed to load page');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred');
    });
}
</script>