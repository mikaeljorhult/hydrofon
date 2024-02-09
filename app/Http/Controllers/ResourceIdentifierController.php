<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdentifierDestroyRequest;
use App\Http\Requests\IdentifierStoreRequest;
use App\Http\Requests\IdentifierUpdateRequest;
use App\Models\Identifier;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResourceIdentifierController extends Controller
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
    public function index(Resource $resource): View
    {
        $this->authorize('view', [$resource, Identifier::class]);

        return view('identifiers.index')->with('identifiable', $resource);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Resource $resource): View
    {
        $this->authorize('create', Identifier::class);

        return view('identifiers.create')->with('identifiable', $resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdentifierStoreRequest $request, Resource $resource): RedirectResponse
    {
        $resource->identifiers()->create($request->validated());

        flash(json_encode([
            'title' => 'Identifier was added',
            'message' => 'Identifier was added for "'.$resource->name.'" successfully.',
        ]), 'success');

        // Redirect to index if sent from create form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/create')) {
            return redirect()->route('resources.identifiers.index', [$resource]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource, Identifier $identifier): View
    {
        return view('identifiers.show')->with([
            'resource' => $resource,
            'identifier' => $identifier,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource, Identifier $identifier): View
    {
        $this->authorize('update', $identifier);

        return view('identifiers.edit')
            ->with('identifiable', $resource)
            ->with('identifier', $identifier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdentifierUpdateRequest $request, Resource $resource, Identifier $identifier): RedirectResponse
    {
        $identifier->update($request->validated());

        flash(json_encode([
            'title' => 'Identifier was updated',
            'message' => 'Identifier was updated successfully.',
        ]), 'success');

        // Redirect to index if sent from edit form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/edit')) {
            return redirect()->route('resources.identifiers.index', [$resource]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource, Identifier $identifier, IdentifierDestroyRequest $request): RedirectResponse
    {
        $identifier->delete();

        flash(json_encode([
            'title' => 'Identifier was removed',
            'message' => 'Identifier was removed successfully.',
        ]), 'success');

        return redirect()->back();
    }
}
