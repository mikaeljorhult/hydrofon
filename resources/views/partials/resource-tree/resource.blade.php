@can('list', $resource)
    <li class="resourcelist-resource mx-1 my-1">
        <label>
            <x-forms.checkbox
                class="mr-1"
                name="resources[]"
                value="{{ $resource->id }}"
                :checked="in_array($resource->id, $selected)"
                x-model.number="selected"
            />

            {{ $resource->name }}
        </label>
    </li>
@endcan
