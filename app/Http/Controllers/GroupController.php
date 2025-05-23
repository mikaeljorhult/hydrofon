<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupDestroyRequest;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
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
     */
    public function create(): View
    {
        $userOptions = User::orderBy('name')->pluck('name', 'id');

        return view('groups.create')->with(compact(['userOptions']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupStoreRequest $request): RedirectResponse
    {
        $group = Group::create($request->validated());
        $group->approvers()->sync($request->get('approvers'));

        flash(json_encode([
            'title' => 'Group was created',
            'message' => 'Group "'.$group->name.'" was created successfully.',
        ]), 'success');

        return redirect()->route('groups.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group): View
    {
        return view('groups.show')->with('group', $group);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group): View
    {
        $userOptions = User::orderBy('name')->pluck('name', 'id');

        return view('groups.edit')->with(compact([
            'group',
            'userOptions',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupUpdateRequest $request, Group $group): RedirectResponse
    {
        $group->update($request->validated());
        $group->approvers()->sync($request->get('approvers'));

        flash(json_encode([
            'title' => 'Group was updated',
            'message' => 'Group was updated successfully.',
        ]), 'success');

        return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, GroupDestroyRequest $reqest): RedirectResponse
    {
        $group->delete();

        flash(json_encode([
            'title' => 'Group was deleted',
            'message' => 'Group was deleted successfully.',
        ]), 'success');

        return redirect()->route('groups.index');
    }
}
