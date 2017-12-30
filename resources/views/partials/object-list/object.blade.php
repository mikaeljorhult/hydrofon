<li class="objectlist-object">
    <label>
        <input type="checkbox"
               name="objects[]"
               value="{{ $object->id }}"
               {{ in_array($object->id, $selected) ? 'checked="checked"' : '' }}
        />
        {{ $object->name }}
    </label>
</li>