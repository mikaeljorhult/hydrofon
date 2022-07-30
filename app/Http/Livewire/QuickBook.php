<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\Models\Resource;
use App\Rules\Available;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Component;

class QuickBook extends Component
{
    public $start_time;

    public $end_time;

    public $resource_id;

    public $user_id;

    public $availableResources;

    private $notificationSent;

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

    public function dehydrate()
    {
        // Dispatch error notification if validation failed.
        if ($this->errorBag->isNotEmpty() && $this->notificationSent === false) {
            $this->dispatchBrowserEvent('notify', [
                'title' => 'Booking could not be created',
                'body' => $this->errorBag->first(),
                'level' => 'error',
            ]);

            $this->notificationSent = true;
        }
    }

    public function loadResources()
    {
        $this->availableResources = $this->getAvailableResources();
    }

    public function book()
    {
        $this->notificationSent = false;

        $validated = $this->validate([
            'user_id' => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->start_time, $this->end_time),
            ],
            'start_time' => ['required', 'date', 'required_with:resource_id', 'before:end_time'],
            'end_time' => ['required', 'date', 'required_with:resource_id', 'after:start_time'],
        ]);

        Booking::create($validated);

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
