<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin')->except(['update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $items = QueryBuilder::for(User::class)
            ->allowedFilters(['email', 'name', 'is_admin', 'groups.id'])
            ->defaultSort('email')
            ->allowedSorts(['email', 'name'])
            ->paginate(15);

        $filterGroups = Group::orderBy('name')->pluck('name', 'id');

        return view('users.index')->with(compact([
            'items',
            'filterGroups',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $groupOptions = Group::orderBy('name')->pluck('name', 'id');

        return view('users.create')->with(compact(['groupOptions']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $input = $request->validated();
        $input['is_admin'] = $request->has('is_admin');

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user = User::create($input);
        $user->groups()->sync($request->get('groups'));

        flash(json_encode([
            'title' => 'User was created',
            'message' => 'User "'.$user->email.'" was created successfully.',
        ]), 'success');

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $groupOptions = Group::orderBy('name')->pluck('name', 'id');

        return view('users.edit')->with(compact([
            'user',
            'groupOptions',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $input = $request->validated();

        if (! $user->is(auth()->user())) {
            $input['is_admin'] = $request->has('is_admin');
        }

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user->update($input);
        $user->groups()->sync($request->get('groups'));

        flash(json_encode([
            'title' => 'User was updated',
            'message' => 'User was updated successfully.',
        ]), 'success');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserDestroyRequest $request): RedirectResponse
    {
        $user->delete();

        flash(json_encode([
            'title' => 'User was deleted',
            'message' => 'User was deleted successfully.',
        ]), 'success');

        return redirect()->route('users.index');
    }
}
