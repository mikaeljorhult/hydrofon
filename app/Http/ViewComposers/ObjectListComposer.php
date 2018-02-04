<?php

namespace Hydrofon\Http\ViewComposers;

use Hydrofon\Category;
use Hydrofon\Object;
use Illuminate\View\View;

class ObjectListComposer
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
            'categories' => $this->categories(),
            'objects'    => $this->objects(),
        ]);
    }

    /**
     * Get all categories without parents.
     *
     * @return \Illuminate\Support\Collection
     */
    private function categories()
    {
        return Category::whereNull('parent_id')
                       ->orderBy('name')
                       ->get();
    }

    /**
     * Get all objects without categories.
     *
     * @return \Illuminate\Support\Collection
     */
    private function objects()
    {
        return Object::doesntHave('categories')
                     ->orderBy('name')
                     ->get();
    }
}
