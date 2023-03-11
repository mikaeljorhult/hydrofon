<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\IdentifierDestroyRequest;
use App\Http\Requests\IdentifierStoreRequest;
use App\Http\Requests\IdentifierUpdateRequest;
use App\Models\Identifier;
use App\Models\User;
use Illuminate\Support\Str;

class UserIdentifierController extends Controller
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
     */
    public function index(User $user): View
    {
        $this->authorize('view', [$user, Identifier::class]);

        return view('identifiers.index')->with('identifiable', $user);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user): View
    {
        $this->authorize('create', Identifier::class);

        return view('identifiers.create')->with('identifiable', $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdentifierStoreRequest $request, User $user): RedirectResponse
    {
        $user->identifiers()->create($request->validated());

        laraflash()
            ->message()
            ->title('Identifier was added')
            ->content('Identifier was added for "'.$user->name.'" successfully.')
            ->success();

        // Redirect to index if sent from create form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/create')) {
            return redirect()->route('users.identifiers.index', [$user]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Identifier $identifier): View
    {
        $this->authorize('update', $identifier);

        return view('identifiers.edit')
            ->with('identifiable', $user)
            ->with('identifier', $identifier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdentifierUpdateRequest $request, User $user, Identifier $identifier): RedirectResponse
    {
        $identifier->update($request->validated());

        laraflash()
            ->message()
            ->title('Identifier was updated')
            ->content('Identifier was updated successfully.')
            ->success();

        // Redirect to index if sent from edit form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/edit')) {
            return redirect()->route('users.identifiers.index', [$user]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Identifier $identifier, IdentifierDestroyRequest $request): RedirectResponse
    {
        $identifier->delete();

        laraflash()
            ->message()
            ->title('Identifier was removed')
            ->content('Identifier was removed successfully.')
            ->success();

        return redirect()->back();
    }
}
