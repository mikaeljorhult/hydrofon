<?php

namespace Hydrofon\Http\Livewire;

use Livewire\Component;

class Segel extends Component
{
    public $resources;
    public $timestamps;

    public function mount($resources, $timestamps)
    {
        $this->resources  = $resources;
        $this->timestamps = $timestamps;
    }

    public function render()
    {
        return view('livewire.segel');
    }
}
