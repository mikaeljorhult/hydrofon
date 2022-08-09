<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryDestroyRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = QueryBuilder::for(Category::class)
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

        session()->flash('index-referer-url', request()->fullUrl());

        return view('categories.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->validated());
        $category->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Category was created')
            ->content('Category "'.$category->name.'" was created successfully.')
            ->success();

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        return view('categories.edit')->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryUpdateRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->validated());
        $category->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Category was updated')
            ->content('Category was updated successfully.')
            ->success();

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @param  \App\Http\Requests\CategoryDestroyRequest  $request
     * @return void
     */
    public function destroy(Category $category, CategoryDestroyRequest $request)
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

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/categories');
    }
}
