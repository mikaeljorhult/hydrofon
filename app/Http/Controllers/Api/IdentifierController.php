<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Identifier;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Hydrofon\Http\Controllers\Controller;
use Hydrofon\Http\Resources\IdentifierCollection;
use Hydrofon\Http\Requests\IdentifierStoreRequest;
use Hydrofon\Http\Requests\IdentifierUpdateRequest;
use Hydrofon\Http\Requests\IdentifierDestroyRequest;
use Hydrofon\Http\Resources\Identifier as IdentifierResource;

class IdentifierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Hydrofon\Http\Resources\IdentifierCollection
     */
    public function index()
    {
        $identifiers = QueryBuilder::for(Identifier::class)
                                   ->allowedFilters('value')
                                   ->defaultSort('value')
                                   ->allowedSorts('value')
                                   ->paginate(15);

        return new IdentifierCollection($identifiers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\IdentifierStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(IdentifierStoreRequest $request)
    {
        IdentifierResource::withoutWrapping();

        $model = app('Hydrofon\\'.ucfirst($request->get('identifiable_type')));
        $identifiable = $model::findOrFail($request->get('identifiable_id'));

        $identifier = $identifiable->identifiers()->create($request->all());

        return (new IdentifierResource($identifier))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Identifier $identifier
     *
     * @return \Hydrofon\Http\Resources\Identifier
     */
    public function show(Identifier $identifier)
    {
        IdentifierResource::withoutWrapping();

        return new IdentifierResource($identifier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\IdentifierUpdateRequest $request
     * @param \Hydrofon\Identifier                            $identifier
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(IdentifierUpdateRequest $request, Identifier $identifier)
    {
        IdentifierResource::withoutWrapping();

        $identifier->update($request->all());

        return (new IdentifierResource($identifier))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Identifier $identifier
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Identifier $identifier, IdentifierDestroyRequest $request)
    {
        $identifier->delete();

        return response()->json(null, 204);
    }
}
