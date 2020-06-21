<?php

namespace App\Http\Livewire;

use App\Booking;
use App\Helpers\Grid;
use App\Resource;
use App\Rules\Available;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Segel extends Component
{
    use AuthorizesRequests;

    public $resources;
    public $timestamps;
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

    public function setGrid($date, $type = null)
    {
        $grid = new Grid($date, $type);

        $this->type = $grid->type;
        $this->headings = $grid->headings;
        $this->steps = $grid->steps;
        $this->timestamps = $grid->timestamps;
        $this->dateString = $grid->dateString;
    }

    public function setTimestamps($timestamps)
    {
        $this->timestamps = (array) $timestamps;
        $this->changeTimestamps(0);
    }

    public function previousTimeScope()
    {
        $this->changeTimestamps($this->timestamps['duration'] * -1);
    }

    public function nextTimeScope()
    {
        $this->changeTimestamps($this->timestamps['duration']);
    }

    public function updatedType()
    {
        $this->setGrid(
            Carbon::createFromTimestamp($this->timestamps['start']),
            $this->type
        );
    }

    public function createBooking($values)
    {
        $this->authorize('create', Booking::class);

        $this->values = [
            'user_id'     => auth()->user()->isAdmin() && isset($values['user_id']) ? $values['user_id'] : auth()->id(),
            'resource_id' => $values['resource_id'],
            'start_time'  => Carbon::createFromTimestamp($this->roundTimestamp($values['start_time'])),
            'end_time'    => Carbon::createFromTimestamp($this->roundTimestamp($values['end_time'])),
        ];

        $validatedValues = $this->validate([
            'values.user_id'     => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'values.resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->values['start_time'], $this->values['end_time'], 0, 'resource_id'),
            ],
            'values.start_time'  => ['required', 'date', 'required_with:values.resource_id', 'before:values.end_time'],
            'values.end_time'    => ['required', 'date', 'required_with:values.resource_id', 'after:values.start_time'],
        ])['values'];

        Booking::create($validatedValues);

        $this->values = [];
    }

    public function updateBooking($values)
    {
        $booking = Booking::findOrFail($values['id']);
        $this->authorize('update', $booking);

        $this->values = [
            'user_id'     => auth()->user()->isAdmin() && isset($values['user_id']) ? $values['user_id'] : $booking->user_id,
            'resource_id' => isset($values['resource_id']) ? $values['resource_id'] : $booking->resource_id,
            'start_time'  => Carbon::createFromTimestamp($this->roundTimestamp($values['start_time'])),
            'end_time'    => Carbon::createFromTimestamp($this->roundTimestamp($values['end_time'])),
        ];

        $validatedValues = $this->validate([
            'values.user_id'     => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'values.resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->values['start_time'], $this->values['end_time'], $booking->id, 'resource_id'),
            ],
            'values.start_time'  => ['required', 'date', 'required_with:values.resource_id', 'before:values.end_time'],
            'values.end_time'    => ['required', 'date', 'required_with:values.resource_id', 'after:values.start_time'],
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
        $startDate = Carbon::createFromTimestamp($this->timestamps['start']);
        $endDate = Carbon::createFromTimestamp($this->timestamps['end']);

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
        $precision = ($this->timestamps['duration']) / $this->steps;

        return round($timestamp / $precision) * $precision;
    }

    private function changeTimestamps($difference)
    {
        $start = Carbon::createFromTimestamp($this->timestamps['start'] + $difference);
        $this->setGrid($start, $this->type);

        $this->emit('dateChanged', [
            'date'       => $this->dateString,
            'timestamps' => $this->timestamps,
            'url'        => route('calendar', [$start->format('Y-m-d')]),
        ]);
    }
}
