<?php

namespace App\Http\Controllers;

use App\Http\Requests\BucketDestroyRequest;
use App\Http\Requests\BucketStoreRequest;
use App\Http\Requests\BucketUpdateRequest;
use App\Models\Bucket;
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
        $buckets = QueryBuilder::for(Bucket::class)
                               ->allowedFilters('name')
                               ->defaultSort('name')
                               ->allowedSorts('name')
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
     * @param  \App\Http\Requests\BucketStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BucketStoreRequest $request)
    {
        $bucket = Bucket::create($request->validated());
        $bucket->resources()->sync($request->get('resources'));

        laraflash()
            ->message()
            ->title('Bucket was created')
            ->content('Bucket "'.$bucket->name.'" was created successfully.')
            ->success();

        return redirect()->route('buckets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bucket  $bucket
     * @return \Illuminate\Http\Response
     */
    public function show(Bucket $bucket)
    {
        return view('buckets.show')->with('bucket', $bucket);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bucket  $bucket
     * @return \Illuminate\Http\Response
     */
    public function edit(Bucket $bucket)
    {
        return view('buckets.edit')->with('bucket', $bucket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BucketUpdateRequest  $request
     * @param  \App\Models\Bucket  $bucket
     * @return \Illuminate\Http\Response
     */
    public function update(BucketUpdateRequest $request, Bucket $bucket)
    {
        $bucket->update($request->validated());
        $bucket->resources()->sync($request->get('resources'));

        laraflash()
            ->message()
            ->title('Bucket was updated')
            ->content('Bucket was updated successfully.')
            ->success();

        return redirect()->route('buckets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bucket  $bucket
     * @param  \App\Http\Requests\BucketDestroyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bucket $bucket, BucketDestroyRequest $request)
    {
        $bucket->delete();

        laraflash()
            ->message()
            ->title('Bucket was deleted')
            ->content('Bucket was deleted successfully.')
            ->success();

        return redirect()->route('buckets.index');
    }
}
