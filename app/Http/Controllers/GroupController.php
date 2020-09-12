<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Requests\GroupDestroyRequest;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
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
        $groups = QueryBuilder::for(Group::class)
                              ->allowedFilters('name')
                              ->defaultSort('name')
                              ->allowedSorts('name')
                              ->paginate(15);

        session()->flash('index-referer-url', request()->fullUrl());

        return view('groups.index')->with('groups', $groups);
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

        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\GroupStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(GroupStoreRequest $request)
    {
        $group = Group::create($request->all());

        flash('Group "'.$group->name.'" was created');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/groups');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Group $group
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
     * @param \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        return view('groups.edit')->with('group', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\GroupUpdateRequest $request
     * @param \App\Models\Group                            $group
     *
     * @return \Illuminate\Http\Response
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        $group->update($request->all());

        flash('Group "'.$group->name.'" was updated');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Group                             $group
     * @param \App\Http\Requests\GroupDestroyRequest $reqest
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupDestroyRequest $reqest)
    {
        $group->delete();

        flash('Group was deleted');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/groups');
    }
}
