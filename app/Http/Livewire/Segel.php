<?php

namespace Hydrofon\Http\Livewire;

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

    public function render()
    {
        return view('livewire.segel');
    }
}
