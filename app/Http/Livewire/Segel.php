<?php

namespace Hydrofon\Http\Livewire;

use Carbon\Carbon;
use Hydrofon\Resource;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Segel extends Component
{
    public $resources;
    public $timestamps;
    public $steps;

    public function mount($resources, $timestamps)
    {
        $this->resources  = $resources;
        $this->timestamps = $timestamps;
        $this->steps      = 48;
    }

    public function setResources($id)
    {
        $this->resources = $id;
    }

    private function getResources()
    {
        $date = Carbon::createFromTimestamp($this->timestamps['start']);

        return count($this->resources) > 0
            ? Resource::whereIn('id', $this->resources)
                      ->with([
                          'bookings' => function ($query) use ($date) {
                              $query
                                  ->with('user')
                                  ->between($date, $date->copy()->endOfDay())
                                  ->orderBy('start_time');
                          },
                      ])
                      ->get()
            : new Collection();
    }

    public function render()
    {
        return view('livewire.segel')->with([
            'items' => $this->getResources(),
        ]);
    }
}
