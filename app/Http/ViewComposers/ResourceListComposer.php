<?php

namespace Hydrofon\Http\ViewComposers;

use Hydrofon\Category;
use Hydrofon\Resource;
use Illuminate\View\View;

class ResourceListComposer
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
            'resources'  => $this->resources(),
        ]);
    }

    /**
     * Get all categories without parents.
     *
     * @return \Illuminate\Support\Collection
     */
    private function categories()
    {
        return Category::with(['categories.resources', 'resources'])
                       ->whereNull('parent_id')
                       ->orderBy('name')
                       ->get(['id', 'name']);
    }

    /**
     * Get all resources without categories.
     *
     * @return \Illuminate\Support\Collection
     */
    private function resources()
    {
        return Resource::orderBy('name')
                       ->doesntHave('categories')
                       ->get(['id', 'name']);
    }
}
