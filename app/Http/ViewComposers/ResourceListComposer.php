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
        $categories = Category::with(['groups'])
                              ->orderBy('name')
                              ->get();

        $rootResources = Resource::with(['groups'])
                                 ->orderBy('name')
                                 ->doesntHave('categories')
                                 ->get(['id', 'name', 'is_facility']);

        $jsResources = Resource::with(['categories', 'groups'])
                               ->whereHas('categories', function ($query) use ($categories) {
                                   $query->whereIn('id', $categories->pluck('id')->toArray());
                               })
                               ->orDoesntHave('categories')
                               ->orderBy('name')
                               ->get(['id', 'name', 'is_facility'])
                               ->values()
                               ->map(function ($resource) {
                                   return new ResourceResource($resource);
                               });

        $jsCategories = $categories
            ->map(function ($category) {
                return new CategoryResource($category);
            });

        $view->with([
            'categories'   => $categories->load('resources', 'resources.groups')->toTree(),
            'resources'    => $rootResources,
            'jsCategories' => $jsCategories->toArray(),
            'jsResources'  => $jsResources->toArray(),
        ]);
    }
}
