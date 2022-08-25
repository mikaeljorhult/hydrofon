<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupDestroyRequest;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use App\Models\Group;
use App\Models\User;
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
        $items = QueryBuilder::for(Group::class)
                             ->allowedFilters('name')
                             ->defaultSort('name')
                             ->allowedSorts('name')
                             ->paginate(15);

        return view('groups.index')->with(compact(['items']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userOptions = User::orderBy('name')->pluck('name', 'id');

        return view('groups.create')->with(compact(['userOptions']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GroupStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupStoreRequest $request)
    {
        $group = Group::create($request->validated());
        $group->approvers()->sync($request->get('approvers'));

        laraflash()
            ->message()
            ->title('Group was created')
            ->content('Group "'.$group->name.'" was created successfully.')
            ->success();

        return redirect()->route('groups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return view('groups.show')->with('group', $group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $userOptions = User::orderBy('name')->pluck('name', 'id');

        return view('groups.edit')->with(compact([
            'group',
            'userOptions',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GroupUpdateRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        $group->update($request->validated());
        $group->approvers()->sync($request->get('approvers'));

        laraflash()
            ->message()
            ->title('Group was updated')
            ->content('Group was updated successfully.')
            ->success();

        return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @param  \App\Http\Requests\GroupDestroyRequest  $reqest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupDestroyRequest $reqest)
    {
        $group->delete();

        laraflash()
            ->message()
            ->title('Group was deleted')
            ->content('Group was deleted successfully.')
            ->success();

        return redirect()->route('groups.index');
    }
}
