<td class="text-center">
    <div class="d-flex justify-content-center gap-4 flex-wrap" style="gap: 3px;">

        <a href="{{ route('admin.group.modules', $group->id) }}" class="btn btn-sm btn-primary">
            <i class="fa fa-file-text-o"></i> Manage Modules
        </a>
        <x-admin>
            <a href="{{ route('admin.groups.edit', $group->id) }}" class="btn btn-primary btn-sm ml-2">
                <i class="fa fa-pencil"></i>
                Edit
            </a>
            <a href="{{ route('admin.group.students', $group->id) }}" class="btn btn-primary btn-sm ml-2">
                <i class="fa fa-graduation-cap"></i> Group Students</a>

            <a href="{{ route('admin.logs.groups', $group->id) }}" class="btn btn-primary btn-sm ml-2">
                <i class="fa fa-history"></i> Group Logs
            </a>

            <form method="POST" action="{{ route('admin.groups.destroy', $group->id) }}" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm ml-2"
                    onclick="return confirm('Are you sure you want to delete this group?')">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </form>

        </x-admin>
    </div>
</td>
