<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceDestroyRequest;
use App\Http\Requests\ResourceStoreRequest;
use App\Http\Requests\ResourceUpdateRequest;
use App\Models\Resource;
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
        $resources = QueryBuilder::for(Resource::class)
                                 ->allowedFilters(['name', 'is_facility', 'categories.id', 'groups.id'])
                                 ->defaultSort('name')
                                 ->allowedSorts(['name', 'description', 'is_facility'])
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
     * @param  \App\Http\Requests\ResourceStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceStoreRequest $request)
    {
        $input = $request->validated();
        $input['is_facility'] = $request->has('is_facility');

        $resource = Resource::create($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Resource was created')
            ->content('Resource "'.$resource->name.'" was created successfully.')
            ->success();

        return redirect()->route('resources.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        return view('resources.show')->with('resource', $resource);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        return view('resources.edit')->with('resource', $resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ResourceUpdateRequest  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(ResourceUpdateRequest $request, Resource $resource)
    {
        $input = $request->validated();
        $input['is_facility'] = $request->has('is_facility');

        $resource->update($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Resource was updated')
            ->content('Resource was updated successfully.')
            ->success();

        return redirect()->route('resources.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource  $resource
     * @param  \App\Http\Requests\ResourceDestroyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource, ResourceDestroyRequest $request)
    {
        $resource->delete();

        laraflash()
            ->message()
            ->title('Resource was deleted')
            ->content('Resource was deleted successfully.')
            ->success();

        return redirect()->route('resources.index');
    }
}
