<?php

namespace Hydrofon\Http\ViewComposers;

use Hydrofon\Category;
use Hydrofon\Resource;
use Illuminate\View\View;
use Hydrofon\Http\Resources\Category as CategoryResource;
use Hydrofon\Http\Resources\Resource as ResourceResource;

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
        $categories = $this->getCategories();
        $rootResources = $this->getRootResources();

        $flatCategories = $categories->toFlatTree();
        $jsCategories = $this->getJavaScriptCategories($flatCategories);
        $jsResources = $this->getJavaScriptResources($flatCategories);

        $view->with([
            'categories'   => $categories->load('resources', 'resources.groups')->toTree(),
            'resources'    => $rootResources,
            'jsCategories' => $jsCategories->toArray(),
            'jsResources'  => $jsResources->toArray(),
        ]);
    }

    /**
     * Retrieve all categories within same group as current user.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCategories()
    {
        return Category::with(['groups'])
                       ->orderBy('name')
                       ->get();
    }

    /**
     * Retrieve resources within same group as current user that dosen't belong to any categories.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRootResources()
    {
        return Resource::with(['groups'])
                       ->orderBy('name')
                       ->whereDoesntHave('categories', function ($query) {
                           $query->withoutGlobalScopes();
                       })
                       ->get(['id', 'name', 'is_facility']);
    }

    /**
     * Map all categories to JavaScript resources.
     *
     * @param $categories
     *
     * @return \Illuminate\Support\Collection
     */
    private function getJavaScriptCategories($categories)
    {
        return $categories->map(function ($category) {
            return new CategoryResource($category);
        });
    }

    /**
     * Retrieve resources and map them to JavaScript resources.
     *
     * @param $categories
     *
     * @return \Illuminate\Support\Collection
     */
    private function getJavaScriptResources($categories)
    {
        return Resource::with(['categories', 'groups'])
                       ->whereHas('categories', function ($query) use ($categories) {
                           $query->whereIn('id', $categories->pluck('id')->toArray());
                       })
                       ->orWhereDoesntHave('categories', function ($query) {
                           $query->withoutGlobalScopes();
                       })
                       ->orderBy('name')
                       ->get(['id', 'name', 'is_facility'])
                       ->values()
                       ->map(function ($resource) {
                           return new ResourceResource($resource);
                       });
    }
}
