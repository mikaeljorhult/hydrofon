<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryDestroyRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $items = QueryBuilder::for(Category::class)
                             ->with(['parent'])
                             ->addSelect([
                                 'parent_name' => Category::from('categories AS parent')
                                                          ->whereColumn('id', 'categories.parent_id')
                                                          ->select('name')
                                                          ->take(1),
                             ])
                             ->allowedFilters('name', 'parent_id')
                             ->allowedSorts(['name', 'parent_name'])
                             ->defaultSort('name')
                             ->paginate(15);

        $filterParent = Category::orderBy('name')->pluck('name', 'id');

        return view('categories.index')->with(compact([
            'items',
            'filterParent',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parentOptions = Category::orderBy('name')->pluck('name', 'id');
        $groupOptions = Group::orderBy('name')->pluck('name', 'id');

        return view('categories.create')->with(compact([
            'parentOptions',
            'groupOptions',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());
        $category->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Category was created')
            ->content('Category "'.$category->name.'" was created successfully.')
            ->success();

        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $parentOptions = Category::where('id', '!=', $category->id)->orderBy('name')->pluck('name', 'id');
        $groupOptions = Group::orderBy('name')->pluck('name', 'id');

        return view('categories.edit')->with(compact([
            'category',
            'parentOptions',
            'groupOptions',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());
        $category->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Category was updated')
            ->content('Category was updated successfully.')
            ->success();

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, CategoryDestroyRequest $request): RedirectResponse
    {
        // Make any direct descending category root to prevent deletion.
        $category->children->each(function ($child) {
            $child->makeRoot()->save();
        });

        $category->delete();

        laraflash()
            ->message()
            ->title('Category was deleted')
            ->content('Category was deleted successfully.')
            ->success();

        return redirect()->route('categories.index');
    }
}
