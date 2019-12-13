<div>
    <table class="table">
        <thead>
            <th class="table-column-check">
                <input
                    type="checkbox"
                    {{ count($this->selectedRows) === count($buckets) ? 'checked="checked"' : '' }}
                    wire:click="$emit('selectAll', $event.target.checked)"
                />
            </th>
            <th><a href="{{ route('buckets.index', ['sort' => (request('sort') === 'name' || request()->has('sort') === false ? '-' : '') . 'name'] + request()->except('page')) }}">Name</a></th>
            <th>&nbsp;</th>
        </thead>

        <tbody>
            @forelse($buckets as $bucket)
                @if($this->isEditing === $bucket->id)
                    <tr>
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Name">
                            <input
                                value="{{ $bucket->name }}"
                                type="text"
                                class="field"
                                wire:model.debounce.500ms="editValues.name"
                            />

                            @error('editValues.name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            <a
                                class="btn btn-primary"
                                wire:click.prevent="$emit('save')"
                                wire:loading.attr="disabled"
                            >Save</a>

                            <a
                                class="btn"
                                wire:click.prevent="$set('isEditing', false)"
                            >Cancel</a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td data-title="&nbsp;">
                            <input
                                type="checkbox"
                                value="{{ $bucket->id }}"
                                {{ in_array($bucket->id, $this->selectedRows) ? 'checked="checked"' : '' }}
                                wire:click="$emit('select', {{ $bucket->id }}, $event.target.checked)"
                            />
                        </td>
                        <td data-title="Name">
                            <a href="{{ route('buckets.edit', $bucket) }}">{{ $bucket->name }}</a>
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            <div>
                                <a
                                    href="{{ route('buckets.edit', $bucket) }}"
                                    title="Edit"
                                    wire:click.prevent="$emit('edit', {{ $bucket->id }})"
                                >Edit</a>
                            </div>

                            <div>
                                {!! Form::model($bucket, ['route' => ['buckets.destroy', $bucket->id], 'method' => 'DELETE' ]) !!}
                                    <button
                                        type="submit"
                                        title="Delete"
                                        wire:click.prevent="$emit('delete', {{ $bucket->id }})"
                                        wire:loading.attr="disabled"
                                    >Delete</button>
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="3">No buckets was found.</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3">
                    <div class="flex justify-end">
                        <form>
                            <button
                                {{ count($this->selectedRows) === 0 ? 'disabled="disabled"' : '' }}
                                class="btn"
                                wire:click.prevent="$emit('delete', false, true)"
                            >Delete</button>
                        </form>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
