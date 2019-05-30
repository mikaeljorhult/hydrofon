<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\IdentifierDestroyRequest;
use Hydrofon\Http\Requests\IdentifierStoreRequest;
use Hydrofon\Http\Requests\IdentifierUpdateRequest;
use Hydrofon\Identifier;
use Hydrofon\User;
use Illuminate\Support\Str;

class IdentifierController extends Controller
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
     * @param \Hydrofon\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $this->authorize('view', [$user, Identifier::class]);

        return view('identifiers.index')->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Hydrofon\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $this->authorize('create', Identifier::class);

        return view('identifiers.create')->with('user', $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\IdentifierStoreRequest $request
     * @param \Hydrofon\User                                 $user
     *
     * @return void
     */
    public function store(IdentifierStoreRequest $request, User $user)
    {
        $user->identifiers()->create($request->all());

        flash('Identifier was added');

        // Redirect to index if sent from create form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/create')) {
            return redirect()->route('users.identifiers.index', [$user]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\User       $user
     * @param \Hydrofon\Identifier $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Identifier $identifier)
    {
        $this->authorize('update', $identifier);

        return view('identifiers.edit')
            ->with('user', $user)
            ->with('identifier', $identifier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\IdentifierUpdateRequest $request
     * @param \Hydrofon\User                                  $user
     * @param \Hydrofon\Identifier                            $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function update(IdentifierUpdateRequest $request, User $user, Identifier $identifier)
    {
        $identifier->update($request->all());

        flash('Identifier was updated');

        // Redirect to index if sent from edit form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/edit')) {
            return redirect()->route('users.identifiers.index', [$user]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\User                                   $user
     * @param \Hydrofon\Identifier                             $identifier
     * @param \Hydrofon\Http\Requests\IdentifierDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Identifier $identifier, IdentifierDestroyRequest $request)
    {
        $identifier->delete();

        flash('Identifier was removed');

        return redirect()->back();
    }
}
