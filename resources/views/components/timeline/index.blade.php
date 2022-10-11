@props(['activities'])

<div>
    <h2 class="mb-4 text-base">Timeline</h2>

    <div class="flow-root">
        <ul role="list" class="-mb-8">
            @foreach($activities as $activity)
                @includeFirst(['partials.timeline.'.$activity->description, 'partials.timeline.activity'], ['item' => $activity, 'last' => $loop->last])
            @endforeach
        </ul>
    </div>
</div>
