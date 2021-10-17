<thead>
    <th class="table-column-check">
        <x-forms.checkbox
            :checked="$this->items->isNotEmpty() && count($this->selectedRows) === $this->items->count()"
            wire:click="$emit('selectAll', $event.target.checked)"
        />
    </th>

    @foreach($this->headers as $header => $title)
        <th {!! substr($header, 0, 3) === 'is_' ? 'class="text-center"' : '' !!}>
            <a href="{{ $this->tableBaseUrl.'?'.http_build_query(['sort' => (request('sort') === $header || ($this->tableDefaultSort === $header && !request()->has('sort')) ? '-' : '') . $header] + request()->only(['filter', 'sort'])) }}">{{ $title }}</a>
        </th>
    @endforeach

    <th>&nbsp;</th>
</thead>
