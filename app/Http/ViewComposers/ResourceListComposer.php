<?php

namespace App\Http\ViewComposers;

use App\Category;
use App\Resource;
use Illuminate\View\View;

class ResourceListComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $categories = $this->getCategories()->toTree();
        $rootResources = $this->getRootResources();

        $view->with([
            'categories' => $categories,
            'resources'  => $rootResources,
        ]);
    }

    /**
     * Retrieve all categories within same group as current user.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCategories()
    {
        return Category::with(['resources'])
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
        return Resource::orderBy('name')
                       ->whereDoesntHave('categories', function ($query) {
                           $query->withoutGlobalScopes();
                       })
                       ->get(['id', 'name', 'is_facility']);
    }
}
