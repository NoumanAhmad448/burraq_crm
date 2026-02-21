@empty($hide_labl)
    <label>Choose Group</label>
@endempty
<select name="group_id" class="form-control select">
    <option value="">-- All Groups --</option>
    @foreach ($groups as $group)
        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
            {{ humanize($group->group_name) }}
        </option>
    @endforeach
</select>
