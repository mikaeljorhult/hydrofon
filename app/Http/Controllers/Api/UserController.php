<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Http\Controllers\Controller;
use Hydrofon\Http\Requests\UserDestroyRequest;
use Hydrofon\Http\Requests\UserStoreRequest;
use Hydrofon\Http\Requests\UserUpdateRequest;
use Hydrofon\Http\Resources\User as UserResource;
use Hydrofon\Http\Resources\UserCollection;
use Hydrofon\User;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Hydrofon\Http\Resources\UserCollection
     */
    public function index()
    {
        $users = QueryBuilder::for(User::class)
                             ->allowedFilters([
                                 'name',
                                 'email',
                                 Filter::exact('is_admin')
                             ])
                             ->defaultSort('name')
                             ->allowedSorts(['name', 'email'])
                             ->paginate(15);

        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\UserStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        UserResource::withoutWrapping();
        $currentUser = auth()->user();

        $user = User::create(array_merge($request->all(), [
            'user_id'       => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $currentUser->id,
            'created_by_id' => $currentUser->id,
        ]));

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\User $user
     *
     * @return \Hydrofon\Http\Resources\User
     */
    public function show(User $user)
    {
        UserResource::withoutWrapping();

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\UserUpdateRequest $request
     * @param \Hydrofon\User                            $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        UserResource::withoutWrapping();
        $currentUser = auth()->user();

        $user->update(array_merge($request->all(), [
            'user_id' => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $user->user_id,
        ]));

        return (new UserResource($user))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\User                             $user
     * @param \Hydrofon\Http\Requests\UserDestroyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserDestroyRequest $request)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
