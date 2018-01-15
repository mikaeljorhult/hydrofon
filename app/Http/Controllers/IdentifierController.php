<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Identifier;
use Hydrofon\User;
use Illuminate\Http\Request;

class IdentifierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Hydrofon\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
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
        return view('identifiers.create')->with('user', $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \Hydrofon\User $user
     *
     * @return void
     */
    public function store(Request $request, User $user)
    {
        $user->identifiers()->create($request->all());

        // Redirect to index if sent from create form, otherwise redirect back.
        if (str_contains($request->headers->get('referer'), '/create')) {
            return redirect()->route('users.identifiers.index', [$user]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Hydrofon\Identifier $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Identifier $identifier)
    {
        return view('identifiers.show')->with('identifier', $identifier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\User $user
     * @param \Hydrofon\Identifier $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Identifier $identifier)
    {
        return view('identifiers.edit')
            ->with('user', $user)
            ->with('identifier', $identifier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Hydrofon\User $user
     * @param \Hydrofon\Identifier $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Identifier $identifier)
    {
        $identifier->update($request->all());

        // Redirect to index if sent from edit form, otherwise redirect back.
        if (str_contains($request->headers->get('referer'), '/edit')) {
            return redirect()->route('users.identifiers.index', [$user]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\User $user
     * @param \Hydrofon\Identifier $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Identifier $identifier)
    {
        $identifier->delete();

        return redirect()->back();
    }
}
