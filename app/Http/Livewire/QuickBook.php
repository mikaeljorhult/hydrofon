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

    public function render()
    {
        return view('livewire.quick-book');
    }

    public function loadResources()
    {
        $this->availableResources = $this->getAvailableResources();
    }

    public function book()
    {
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
    }

    private function getAvailableResources()
    {
        return Resource::whereDoesntHave('bookings', function (Builder $query) {
            $query->between($this->start_time, $this->end_time);
        })->orderBy('name')->get();
    }
}
