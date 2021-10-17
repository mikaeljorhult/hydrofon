@php
    $status = $item->status();

    switch($status->name) {
        case 'approved':
            $statusClasses = 'bg-green-100 text-green-800';
            break;
        case 'pending':
            $statusClasses = 'bg-yellow-100 text-yellow-800';
            break;
        case 'rejected':
            $statusClasses = 'bg-red-100 text-red-800';
            break;
    }
@endphp

<span
    class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium {{ $statusClasses }}"
    title="{{ $status->reason }}"
>
    {{ $status->name }}
</span>
