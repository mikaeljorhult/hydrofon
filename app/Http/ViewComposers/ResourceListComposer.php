<?php

namespace Hydrofon\Http\ViewComposers;

use Hydrofon\Category;
use Hydrofon\Http\Resources\Category as CategoryResource;
use Hydrofon\Http\Resources\Resource as ResourceResource;
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
        $categories = $this->categories();
        $resources = $this->resources();

        $jsCategories = $categories
            ->nested('categories')
            ->except([
                'categories',
                'resources',
            ])
            ->map(function ($category) {
                return new CategoryResource($category);
            });

        $jsResources = $categories
            ->nested('resources', 'categories', false)
            ->merge($resources)
            ->map(function ($resource) {
                return new ResourceResource($resource);
            });

        $view->with([
            'categories'   => $categories,
            'resources'    => $resources,
            'jsCategories' => $jsCategories,
            'jsResources'  => $jsResources,
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
                       ->get(['id', 'name', 'is_facility']);
    }
}
