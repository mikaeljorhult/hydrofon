<?php

namespace Hydrofon\Http\ViewComposers;

use Illuminate\View\View;

class ObjectListSelectedComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('selected', session('objects') ?: []);
    }
}
