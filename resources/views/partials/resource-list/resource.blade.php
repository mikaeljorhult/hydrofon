@can('list', $resource)
    <li class="resourcelist-resource">
        <label>
            <input type="checkbox"
                   name="resources[]"
                   value="{{ $resource->id }}"
                   {{ in_array($resource->id, $selected) ? 'checked="checked"' : '' }}
            />
            {{ $resource->name }}
        </label>
    </li>
@endcan