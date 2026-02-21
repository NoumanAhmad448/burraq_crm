<label>Choose Group</label>
<select name="group_id" class="form-control select">
    @foreach ($groups as $group)
        <option value="{{ $group->id }}">
            {{ $group->group_name }} -
        </option>
    @endforeach
</select>
