<?php

namespace App\Http\Controllers;

use App\Http\Requests\BucketDestroyRequest;
use App\Http\Requests\BucketStoreRequest;
use App\Http\Requests\BucketUpdateRequest;
use App\Models\Bucket;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
     */
    public function index(): View
    {
        $items = QueryBuilder::for(Bucket::class)
            ->allowedFilters('name')
            ->defaultSort('name')
            ->allowedSorts('name')
            ->paginate(15);

        return view('buckets.index')->with(compact(['items']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $resourceOptions = Resource::orderBy('name')->pluck('name', 'id');

        return view('buckets.create')->with(compact(['resourceOptions']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BucketStoreRequest $request): RedirectResponse
    {
        $bucket = Bucket::create($request->validated());
        $bucket->resources()->sync($request->get('resources'));

        flash(json_encode([
            'title' => 'Bucket was created',
            'message' => 'Bucket "'.$bucket->name.'" was created successfully.',
        ]), 'success');

        return redirect()->route('buckets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bucket $bucket): View
    {
        return view('buckets.show')->with('bucket', $bucket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bucket $bucket): View
    {
        $resourceOptions = Resource::orderBy('name')->pluck('name', 'id');

        return view('buckets.edit')->with(compact([
            'bucket',
            'resourceOptions',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BucketUpdateRequest $request, Bucket $bucket): RedirectResponse
    {
        $bucket->update($request->validated());
        $bucket->resources()->sync($request->get('resources'));

        flash(json_encode([
            'title' => 'Bucket was updated',
            'message' => 'Bucket was updated successfully.',
        ]), 'success');

        return redirect()->route('buckets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bucket $bucket, BucketDestroyRequest $request): RedirectResponse
    {
        $bucket->delete();

        flash(json_encode([
            'title' => 'Bucket was deleted',
            'message' => 'Bucket was deleted successfully.',
        ]), 'success');

        return redirect()->route('buckets.index');
    }
}
