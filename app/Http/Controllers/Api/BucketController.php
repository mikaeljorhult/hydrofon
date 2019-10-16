<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Bucket;
use Spatie\QueryBuilder\QueryBuilder;
use Hydrofon\Http\Controllers\Controller;
use Hydrofon\Http\Resources\BucketCollection;
use Hydrofon\Http\Requests\BucketStoreRequest;
use Hydrofon\Http\Requests\BucketUpdateRequest;
use Hydrofon\Http\Requests\BucketDestroyRequest;
use Hydrofon\Http\Resources\Bucket as BucketResource;

class BucketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Hydrofon\Http\Resources\BucketCollection
     */
    public function index()
    {
        $buckets = QueryBuilder::for(Bucket::class)
            ->allowedFilters('name')
            ->defaultSort('name')
            ->allowedSorts('name')
            ->paginate(15);

        return new BucketCollection($buckets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BucketStoreRequest $request)
    {
        BucketResource::withoutWrapping();

        $bucket = Bucket::create($request->all());
        $bucket->resources()->sync($request->get('resources'));

        return (new BucketResource($bucket))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Bucket $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Bucket $bucket)
    {
        BucketResource::withoutWrapping();

        return new BucketResource($bucket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\BucketUpdateRequest $request
     * @param \Hydrofon\Bucket                            $bucket
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BucketUpdateRequest $request, Bucket $bucket)
    {
        BucketResource::withoutWrapping();

        $bucket->update($request->all());
        $bucket->resources()->sync($request->get('resources'));

        return (new BucketResource($bucket))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Bucket                             $bucket
     * @param \Hydrofon\Http\Requests\BucketDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bucket $bucket, BucketDestroyRequest $request)
    {
        $bucket->delete();

        return response()->json(null, 204);
    }
}
