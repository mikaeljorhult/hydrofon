<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Category;
use Hydrofon\Http\Controllers\Controller;
use Hydrofon\Http\Requests\CategoryDestroyRequest;
use Hydrofon\Http\Requests\CategoryStoreRequest;
use Hydrofon\Http\Requests\CategoryUpdateRequest;
use Hydrofon\Http\Resources\Category as CategoryResource;
use Hydrofon\Http\Resources\CategoryCollection;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Hydrofon\Http\Resources\CategoryCollection
     */
    public function index()
    {
        $categories = QueryBuilder::for(Category::class)
                                  ->with(['parent'])
                                  ->leftJoin('categories as parent', 'parent.id', '=', 'categories.parent_id')
                                  ->allowedFilters('categories.name', 'categories.parent_id')
                                  ->allowedSorts(['categories.name', 'parent.name'])
                                  ->defaultSort('categories.name')
                                  ->select('categories.*')
                                  ->paginate(15);

        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\CategoryStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryStoreRequest $request)
    {
        CategoryResource::withoutWrapping();

        $category = Category::create($request->all());
        $category->groups()->sync($request->get('groups'));

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Category $category
     *
     * @return \Hydrofon\Http\Resources\CategoryResource
     */
    public function show(Category $category)
    {
        CategoryResource::withoutWrapping();

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\CategoryUpdateRequest $request
     * @param \Hydrofon\Category                            $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->all());
        $category->groups()->sync($request->get('groups'));

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Category                             $category
     * @param \Hydrofon\Http\Requests\CategoryDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, CategoryDestroyRequest $request)
    {
        // Make any direct descending category root to prevent deletion.
        $category->children->each(function ($child) {
            $child->makeRoot()->save();
        });

        $category->delete();

        return response()->json(null, 204);
    }
}
