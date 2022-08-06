<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\Models\Resource;
use App\Rules\Available;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Livewire\Component;

class QuickBook extends Component
{
    public $start_time;

    public $end_time;

    public $resource_id;

    public $user_id;

    public $availableResources;

    protected $validationAttributes = [
        'resource_id' => 'resource',
        'user_id' => 'user',
    ];

    public function mount()
    {
        $this->fill([
            'start_time' => now()->minutes(0)->format('Y-m-d H:i'),
            'end_time' => now()->minutes(0)->addHours(2)->format('Y-m-d H:i'),
            'resource_id' => null,
            'user_id' => auth()->id(),
            'availableResources' => collect(),
        ]);
    }

    public function loadResources()
    {
        $this->availableResources = $this->getAvailableResources();
    }

    public function book()
    {
        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatchBrowserEvent('notify', [
                        'title' => 'Booking could not be created',
                        'body' => $validator->errors()->first(),
                        'level' => 'error',
                    ]);
                }
            });
        })->validate([
            'user_id' => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->start_time, $this->end_time),
            ],
            'start_time' => ['required', 'date', 'required_with:resource_id', 'before:end_time'],
            'end_time' => ['required', 'date', 'required_with:resource_id', 'after:start_time'],
        ]);

        $booking = Booking::create($validated);

        $this->dispatchBrowserEvent('booking-created', [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->format('U'),
            'end_time' => $booking->end_time->format('U'),
        ]);

        $this->dispatchBrowserEvent('notify', [
            'title' => 'Booking was created',
            'body' => 'The booking was created successfully.',
            'level' => 'success',
        ]);
    }

    private function getAvailableResources()
    {
        return Resource::whereDoesntHave('bookings', function (Builder $query) {
            $query->between($this->start_time, $this->end_time);
        })->orderBy('name')->get();
    }
}
