<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
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
     * @param  \App\Http\Requests\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $input = $request->validated();
        $input['is_admin'] = $request->has('is_admin');

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user = User::create($input);
        $user->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('User was created')
            ->content('User "'.$user->email.'" was created successfully.')
            ->success();

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return void
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $input = $request->validated();

        if (! $user->is(auth()->user())) {
            $input['is_admin'] = $request->has('is_admin');
        } else {
            unset($input['is_admin']);
        }

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user->update($input);
        $user->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('User was updated')
            ->content('User was updated successfully.')
            ->success();

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Http\Requests\UserDestroyRequest  $request
     * @return void
     */
    public function destroy(User $user, UserDestroyRequest $request)
    {
        $user->delete();

        laraflash()
            ->message()
            ->title('User was deleted')
            ->content('User was deleted successfully.')
            ->success();

        return redirect()->route('users.index');
    }
}
