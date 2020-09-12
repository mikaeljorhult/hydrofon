<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdentifierDestroyRequest;
use App\Http\Requests\IdentifierStoreRequest;
use App\Http\Requests\IdentifierUpdateRequest;
use App\Models\Identifier;
use App\Models\Resource;
use Illuminate\Support\Str;

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
     *
     * @param \App\Models\Resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Resource $resource)
    {
        $this->authorize('view', [$resource, Identifier::class]);

        return view('identifiers.index')->with('identifiable', $resource);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Models\Resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Resource $resource)
    {
        $this->authorize('create', Identifier::class);

        return view('identifiers.create')->with('identifiable', $resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\IdentifierStoreRequest $request
     * @param \App\Models\Resource                             $resource
     *
     * @return void
     */
    public function store(IdentifierStoreRequest $request, Resource $resource)
    {
        $resource->identifiers()->create($request->all());

        flash('Identifier was added');

        // Redirect to index if sent from create form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/create')) {
            return redirect()->route('resources.identifiers.index', [$resource]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Resource   $resource
     * @param \App\Models\Identifier $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource, Identifier $identifier)
    {
        $this->authorize('update', $identifier);

        return view('identifiers.edit')
            ->with('identifiable', $resource)
            ->with('identifier', $identifier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\IdentifierUpdateRequest $request
     * @param \App\Models\Resource                              $resource
     * @param \App\Models\Identifier                            $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function update(IdentifierUpdateRequest $request, Resource $resource, Identifier $identifier)
    {
        $identifier->update($request->all());

        flash('Identifier was updated');

        // Redirect to index if sent from edit form, otherwise redirect back.
        if (Str::contains($request->headers->get('referer'), '/edit')) {
            return redirect()->route('resources.identifiers.index', [$resource]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Resource                               $resource
     * @param \App\Models\Identifier                             $identifier
     * @param \App\Http\Requests\IdentifierDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource, Identifier $identifier, IdentifierDestroyRequest $request)
    {
        $identifier->delete();

        flash('Identifier was removed');

        return redirect()->back();
    }
}
