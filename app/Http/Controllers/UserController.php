<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\UserDestroyRequest;
use Hydrofon\Http\Requests\UserStoreRequest;
use Hydrofon\Http\Requests\UserUpdateRequest;
use Hydrofon\User;
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
        $this->middleware('auth');
        $this->middleware('admin')->except(['update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = QueryBuilder::for(User::class)
                             ->allowedFilters(['email', 'name', 'is_admin', 'groups.id'])
                             ->defaultSort('email')
                             ->allowedSorts(['email', 'name'])
                             ->paginate(15);

        session()->flash('index-referer-url', request()->fullUrl());

        return view('users.index')->with('users', $users);
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
        $input['is_admin'] = $request->has('is_admin');

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user = User::create($input);
        $user->groups()->sync($request->get('groups'));

        flash('User "'.$user->email.'" was created');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/users');
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
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

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

        if (! $user->is(auth()->user())) {
            $input['is_admin'] = $request->has('is_admin');
        }

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user->update($input);
        $user->groups()->sync($request->get('groups'));

        flash('User "'.$user->email.'" was updated');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/users');
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

        flash('User was deleted');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/users');
    }
}
