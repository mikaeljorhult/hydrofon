<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Bucket;
use Hydrofon\Http\Requests\BucketDestroyRequest;
use Hydrofon\Http\Requests\BucketStoreRequest;
use Hydrofon\Http\Requests\BucketUpdateRequest;
use Illuminate\Http\Request;

class BucketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buckets = Bucket::orderByField(request()->get('order', 'name'))
                         ->paginate(15);

        return view('buckets.index')->with('buckets', $buckets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('buckets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\BucketStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BucketStoreRequest $request)
    {
        Bucket::create($request->all());

        return redirect('/buckets');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Hydrofon\Bucket $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Bucket $bucket)
    {
        return view('buckets.show')->with('bucket', $bucket);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Hydrofon\Bucket $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Bucket $bucket)
    {
        return view('buckets.edit')->with('bucket', $bucket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\BucketUpdateRequest $request
     * @param  \Hydrofon\Bucket                           $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BucketUpdateRequest $request, Bucket $bucket)
    {
        $bucket->update($request->all());

        return redirect('/buckets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Hydrofon\Bucket                            $bucket
     * @param \Hydrofon\Http\Requests\BucketDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bucket $bucket, BucketDestroyRequest $request)
    {
        $bucket->delete();

        return redirect('/buckets');
    }
}
