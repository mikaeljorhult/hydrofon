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
        return Category::whereNull('parent_id')
                       ->orderBy('name')
                       ->get();
    }

    /**
     * Get all resources without categories.
     *
     * @return \Illuminate\Support\Collection
     */
    private function resources()
    {
        return Resource::doesntHave('categories')
                       ->orderBy('name')
                       ->get();
    }
}
