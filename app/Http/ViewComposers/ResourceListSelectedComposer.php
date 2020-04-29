<?php

namespace Hydrofon\Http\ViewComposers;

use Illuminate\View\View;

class ResourceListSelectedComposer
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
        $view->with([
            'expanded' => session('expanded') ? array_map('intval', session('expanded')) : [],
            'selected' => session('resources') ? array_map('intval', session('resources')) : [],
        ]);
    }
}
