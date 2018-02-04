<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Group;
use Hydrofon\Http\Requests\GroupDestroyRequest;
use Hydrofon\Http\Requests\GroupStoreRequest;
use Hydrofon\Http\Requests\GroupUpdateRequest;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::orderBy('name')->paginate(15);

        return view('groups.index')->with('groups', $groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\GroupStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(GroupStoreRequest $request)
    {
        Group::create($request->all());

        return redirect('/groups');
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return view('groups.show')->with('group', $group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('groups.edit')->with('group', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\GroupUpdateRequest $request
     * @param \Hydrofon\Group                            $group
     *
     * @return \Illuminate\Http\Response
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        $group->update($request->all());

        return redirect('/groups');
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

        return redirect('/groups');
    }
}
