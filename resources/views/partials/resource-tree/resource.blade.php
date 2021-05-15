@can('list', $resource)
    <li class="resourcelist-resource">
        <label>
            <x-forms.checkbox
                name="resources[]"
                value="{{ $resource->id }}"
                :checked="in_array($resource->id, $selected)"
                x-model.number.debounce.1000ms="selected"
            />

            {{ $resource->name }}
        </label>
    </li>
@endcan
