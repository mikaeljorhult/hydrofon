<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ResourceListComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $categories = $this->getCategories()->toTree();
        $rootResources = $this->getRootResources();

        $view->with([
            'categories' => $categories,
            'resources' => $rootResources,
        ]);
    }

    /**
     * Retrieve all categories within same group as current user.
     */
    private function getCategories(): Collection
    {
        return Category::with(['resources'])
                       ->orderBy('name')
                       ->tree()
                       ->get();
    }

    /**
     * Retrieve resources within same group as current user that dosen't belong to any categories.
     */
    private function getRootResources(): Collection
    {
        return Resource::orderBy('name')
                       ->whereDoesntHave('categories', function ($query) {
                           $query->withoutGlobalScopes();
                       })
                       ->get(['id', 'name', 'is_facility']);
    }
}
