<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Bucket;
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
     * @param  \Illuminate\Http\Request $request
     * @param  \Hydrofon\Bucket         $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bucket $bucket)
    {
        $bucket->update($request->all());

        return redirect('/buckets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Hydrofon\Bucket $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bucket $bucket)
    {
        $bucket->delete();

        return redirect('/buckets');
    }
}
