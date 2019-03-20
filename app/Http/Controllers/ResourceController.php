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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = QueryBuilder::for(Resource::class)
                                 ->allowedFilters('name')
                                 ->defaultSort('name')
                                 ->allowedSorts(['name', 'description'])
                                 ->paginate(15);

        return view('resources.index')->with('resources', $resources);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

        flash('Resource was created');

        return redirect('/resources');
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

        flash('Resource was updated');

        return redirect('/resources');
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

        return redirect('/resources');
    }
}
