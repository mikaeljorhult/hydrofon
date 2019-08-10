<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Resource;
use Spatie\QueryBuilder\QueryBuilder;
use Hydrofon\Http\Controllers\Controller;
use Hydrofon\Http\Resources\ResourceCollection;
use Hydrofon\Http\Requests\ResourceStoreRequest;
use Hydrofon\Http\Requests\ResourceUpdateRequest;
use Hydrofon\Http\Resources\Resource as ResourceResource;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Hydrofon\Http\Resources\ResourceCollection
     */
    public function index()
    {
        $resources = QueryBuilder::for(Resource::class)
                                 ->allowedFilters(['name', 'is_facility', 'categories.id', 'groups.id'])
                                 ->defaultSort('name')
                                 ->allowedSorts(['name', 'description'])
                                 ->paginate(15);

        return new ResourceCollection($resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\ResourceStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ResourceStoreRequest $request)
    {
        ResourceResource::withoutWrapping();

        $input = $request->all();

        $resource = Resource::create($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        return (new ResourceResource($resource))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Resource $resource
     *
     * @return \Hydrofon\Http\Resources\Resource
     */
    public function show(Resource $resource)
    {
        ResourceResource::withoutWrapping();

        return new ResourceResource($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\ResourceUpdateRequest $request
     * @param \Hydrofon\Resource                            $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ResourceUpdateRequest $request, Resource $resource)
    {
        $input = $request->all();

        $resource->update($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        return (new ResourceResource($resource))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();

        return response()->json(null, 204);
    }
}
