<?php

namespace App\Http\Livewire;

use App\Helpers\Grid;
use App\Models\Booking;
use App\Models\Resource;
use App\Rules\Available;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Segel extends Component
{
    use AuthorizesRequests;

    public $resources;

    public $start;

    public $end;

    public $duration;

    public $dateString;

    public $type;

    public $steps;

    public $headings;

    public $values;

    public function mount($resources, $date)
    {
        $this->resources = $resources;
        $this->setGrid($date, 'day');
    }

    public function render()
    {
        return view('livewire.segel')->with([
            'items' => $this->getResources(),
        ]);
    }

    public function setExpanded($id)
    {
        session()->put('expanded', $id);
    }

    public function setResources($id)
    {
        $this->resources = $id;

        session()->put('resources', $id);
    }

    public function setTimestamps($start, $end = null, $duration = null)
    {
        $this->start = $start;
        $this->duration = $duration ?? $this->duration;
        $this->end = $end ?? $this->start + $this->duration;
        $this->changeTimestamps(0);
    }

    public function setGrid($date, $type = null)
    {
        $grid = new Grid($date, $type);

        $this->type = $grid->type;
        $this->headings = $grid->headings;
        $this->steps = $grid->steps;
        $this->start = $grid->timestamps['start'];
        $this->end = $grid->timestamps['end'];
        $this->duration = $grid->timestamps['duration'];
        $this->dateString = $grid->dateString;
    }

    public function previousTimeScope()
    {
        $this->changeTimestamps($this->duration * -1);
    }

    public function nextTimeScope()
    {
        $this->changeTimestamps($this->duration);
    }

    public function updatedType()
    {
        $this->setGrid(
            Carbon::createFromTimestamp($this->start),
            $this->type
        );
    }

    public function createBooking($values)
    {
        $this->authorize('create', Booking::class);

        $this->values = [
            'user_id' => auth()->user()->isAdmin() && isset($values['user_id']) ? $values['user_id'] : auth()->id(),
            'resource_id' => Arr::wrap($values['resource_id']),
            'start_time' => Carbon::createFromTimestamp($this->roundTimestamp($values['start_time'])),
            'end_time' => Carbon::createFromTimestamp($this->roundTimestamp($values['end_time'])),
        ];

        $validated = $this->validate([
            'values.user_id' => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'values.resource_id' => ['required', 'array'],
            'values.resource_id.*' => [
                Rule::exists('resources', 'id'),
                new Available($this->values['start_time'], $this->values['end_time'], 0, 'resource_id'),
            ],
            'values.start_time' => ['required', 'date', 'required_with:values.resource_id', 'before:values.end_time'],
            'values.end_time' => ['required', 'date', 'required_with:values.resource_id', 'after:values.start_time'],
        ])['values'];

        $bookings = collect($validated['resource_id'])
            ->map(function ($value) use ($validated) {
                $booking = [
                    'resource_id' => $value,
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (isset($validated['user_id'])) {
                    $booking['user_id'] = $validated['user_id'];
                }

                return $booking;
            });

        Booking::insert($bookings->toArray());

        $this->values = [];
    }

    public function updateBooking($values)
    {
        $booking = Booking::findOrFail($values['id']);
        $this->authorize('update', $booking);

        $this->values = [
            'user_id' => auth()->user()->isAdmin() && isset($values['user_id']) ? $values['user_id'] : $booking->user_id,
            'resource_id' => isset($values['resource_id']) ? $values['resource_id'] : $booking->resource_id,
            'start_time' => Carbon::createFromTimestamp($this->roundTimestamp($values['start_time'])),
            'end_time' => Carbon::createFromTimestamp($this->roundTimestamp($values['end_time'])),
        ];

        $validatedValues = $this->validate([
            'values.user_id' => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'values.resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->values['start_time'], $this->values['end_time'], $booking->id, 'resource_id'),
            ],
            'values.start_time' => ['required', 'date', 'required_with:values.resource_id', 'before:values.end_time'],
            'values.end_time' => ['required', 'date', 'required_with:values.resource_id', 'after:values.start_time'],
        ])['values'];

        $booking->update($validatedValues);

        $this->values = [];
    }

    public function deleteBooking($values)
    {
        $booking = Booking::findOrFail($values['id']);
        $this->authorize('delete', $booking);

        $booking->delete();
    }

    private function getResources()
    {
        $startDate = Carbon::createFromTimestamp($this->start);
        $endDate = Carbon::createFromTimestamp($this->end);

        return count($this->resources) > 0
            ? Resource::whereIn('id', $this->resources)
                      ->with([
                          'bookings' => function ($query) use ($startDate, $endDate) {
                              $query
                                  ->with('user')
                                  ->between($startDate, $endDate)
                                  ->orderBy('start_time');
                          },
                      ])
                      ->get()
            : new Collection();
    }

    private function roundTimestamp($timestamp)
    {
        $precision = ($this->duration) / $this->steps;

        return round($timestamp / $precision) * $precision;
    }

    private function changeTimestamps($difference)
    {
        $start = Carbon::createFromTimestamp($this->start + $difference);
        $this->setGrid($start, $this->type);

        $this->emit('dateChanged', [
            'date' => $this->dateString,
            'start' => $this->start,
            'end' => $this->end,
            'duration' => $this->duration,
            'url' => route('calendar', [$start->format('Y-m-d')]),
        ]);
    }
}
