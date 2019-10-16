<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Group;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Hydrofon\Http\Controllers\Controller;
use Hydrofon\Http\Resources\GroupCollection;
use Hydrofon\Http\Requests\GroupStoreRequest;
use Hydrofon\Http\Requests\GroupUpdateRequest;
use Hydrofon\Http\Requests\GroupDestroyRequest;
use Hydrofon\Http\Resources\Group as GroupResource;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Hydrofon\Http\Resources\GroupCollection
     */
    public function index()
    {
        $groups = QueryBuilder::for(Group::class)
                              ->allowedFilters('name')
                              ->defaultSort('name')
                              ->allowedSorts('name')
                              ->paginate(15);

        return new GroupCollection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\GroupStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GroupStoreRequest $request)
    {
        GroupResource::withoutWrapping();

        $group = Group::create($request->all());

        return (new GroupResource($group))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Group $group
     *
     * @return \Hydrofon\Http\Resources\Group
     */
    public function show(Group $group)
    {
        GroupResource::withoutWrapping();

        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\GroupUpdateRequest $request
     * @param \Hydrofon\Group                            $group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        GroupResource::withoutWrapping();

        $group->update($request->all());

        return (new GroupResource($group))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Group                             $group
     * @param \Hydrofon\Http\Requests\GroupDestroyRequest $reqest
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupDestroyRequest $reqest)
    {
        $group->delete();

        return response()->json(null, 204);
    }
}
