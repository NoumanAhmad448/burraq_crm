@empty($hide_labl)
    <label>Choose Group</label>
@endempty
<select name="group_id" class="form-control select">
    @foreach ($groups as $group)
        <option value="{{ $group->id }}">
            {{ humanize($group->group_name) }}
        </option>
    @endforeach
</select>
