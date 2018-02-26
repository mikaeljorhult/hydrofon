<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\UserDestroyRequest;
use Hydrofon\Http\Requests\UserStoreRequest;
use Hydrofon\Http\Requests\UserUpdateRequest;
use Hydrofon\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderByField(request()->get('order', 'email'))
                     ->paginate(15);

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\UserStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $input = $request->all();

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user = User::create($input);
        $user->groups()->sync($request->get('groups'));

        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\UserUpdateRequest $request
     * @param \Hydrofon\User                            $user
     *
     * @return void
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $input = $request->all();

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user->update($input);
        $user->groups()->sync($request->get('groups'));

        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\User                             $user
     * @param \Hydrofon\Http\Requests\UserDestroyRequest $request
     *
     * @return void
     */
    public function destroy(User $user, UserDestroyRequest $request)
    {
        $user->delete();

        return redirect('/users');
    }
}
