@forelse($users as $user)
<tr id="user-row-{{ $user->id }}">
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->display_name }}</td>
    <td>
        @if($user->account_type === 'superadmin')
            <span class="badge bg-danger">Super Admin</span>
        @else
            <span class="badge bg-info">Admin</span>
        @endif
    </td>
    <td>
        @if($user->is_active)
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-secondary">Inactive</span>
        @endif
    </td>
    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
    <td style="width: 130px; white-space: nowrap;">
        <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})" title="Edit" style="width: 32px;">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})" title="Delete" style="width: 32px;">
            <i class="fas fa-trash"></i>
        </button>
        <button class="btn btn-secondary btn-sm" onclick="toggleUserStatus({{ $user->id }})" title="Toggle Status" style="width: 32px;">
            <i class="fas fa-power-off"></i>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">
        <i class="fas fa-users-slash me-2"></i>No users found
    </td>
</tr>
@endforelse