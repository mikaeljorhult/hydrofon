<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\ResourceDestroyRequest;
use Hydrofon\Http\Requests\ResourceStoreRequest;
use Hydrofon\Http\Requests\ResourceUpdateRequest;
use Hydrofon\Resource;
use Spatie\QueryBuilder\QueryBuilder;

class ResourceController extends Controller
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

        return view('resources.index');
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

        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\ResourceStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceStoreRequest $request)
    {
        $input = $request->all();
        $input['is_facility'] = $request->has('is_facility');

        $resource = Resource::create($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        flash('Resource "'.$resource->name.'" was created');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/resources');
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        return view('resources.show')->with('resource', $resource);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\Resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        return view('resources.edit')->with('resource', $resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\ResourceUpdateRequest $request
     * @param \Hydrofon\Resource                            $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ResourceUpdateRequest $request, Resource $resource)
    {
        $input = $request->all();
        $input['is_facility'] = $request->has('is_facility');

        $resource->update($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        flash('Resource "'.$resource->name.'" was updated');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/resources');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Resource                             $resource
     * @param \Hydrofon\Http\Requests\ResourceDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource, ResourceDestroyRequest $request)
    {
        $resource->delete();

        flash('Resource was deleted');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/resources');
    }
}
