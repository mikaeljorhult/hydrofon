<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Category;
use Hydrofon\Http\Requests\CategoryDestroyRequest;
use Hydrofon\Http\Requests\CategoryStoreRequest;
use Hydrofon\Http\Requests\CategoryUpdateRequest;
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
        session()->flash('index-referer-url', request()->fullUrl());

        return view('categories.index');
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
     * @param \Hydrofon\Http\Requests\CategoryStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->all());
        $category->groups()->sync($request->get('groups'));

        flash('Category "'.$category->name.'" was created');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\Category $category
     *
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
     * @param \Hydrofon\Http\Requests\CategoryUpdateRequest $request
     * @param \Hydrofon\Category                            $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->all());
        $category->groups()->sync($request->get('groups'));

        flash('Category "'.$category->name.'" was updated');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Category                             $category
     * @param \Hydrofon\Http\Requests\CategoryDestroyRequest $request
     *
     * @return void
     */
    public function destroy(Category $category, CategoryDestroyRequest $request)
    {
        // Make any direct descending category root to prevent deletion.
        $category->children->each(function ($child) {
            $child->makeRoot()->save();
        });

        $category->delete();

        flash('Category was deleted');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/categories');
    }
}
