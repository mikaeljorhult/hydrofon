<thead>
    <th class="table-column-check">
        <input
            type="checkbox"
            {{ count($this->selectedRows) === count($items) ? 'checked="checked"' : '' }}
            wire:click="$emit('selectAll', $event.target.checked)"
        />
    </th>

    @foreach($this->tableHeaders as $header => $title)
        <th {!! substr($header, 0, 3) === 'is_' ? 'class="text-center"' : '' !!}>
            <a href="{{ $this->tableBaseUrl.'?'.http_build_query(['sort' => (request('sort') === $header || ($this->tableDefaultSort === $header && !request()->has('sort')) ? '-' : '') . $header] + request()->only(['filter', 'sort'])) }}">{{ $title }}</a>
        </th>
    @endforeach

    <th>&nbsp;</th>
</thead>
