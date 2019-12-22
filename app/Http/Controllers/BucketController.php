<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Bucket;
use Hydrofon\Http\Requests\BucketDestroyRequest;
use Hydrofon\Http\Requests\BucketStoreRequest;
use Hydrofon\Http\Requests\BucketUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;

class BucketController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session()->flash('index-referer-url', request()->fullUrl());

        return view('buckets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

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
        $bucket = Bucket::create($request->all());
        $bucket->resources()->sync($request->get('resources'));

        flash('Bucket "'.$bucket->name.'" was created');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/buckets');
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
        return view('buckets.show')->with('bucket', $bucket);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\Bucket $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Bucket $bucket)
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        return view('buckets.edit')->with('bucket', $bucket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\BucketUpdateRequest $request
     * @param \Hydrofon\Bucket                            $bucket
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BucketUpdateRequest $request, Bucket $bucket)
    {
        $bucket->update($request->all());
        $bucket->resources()->sync($request->get('resources'));

        flash('Bucket "'.$bucket->name.'" was updated');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/buckets');
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

        flash('Bucket was deleted');

        return ($backUrl = session()->get('index-referer-url'))
            ? redirect()->to($backUrl)
            : redirect('/buckets');
    }
}
