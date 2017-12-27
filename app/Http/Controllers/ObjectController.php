<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\ObjectDestroyRequest;
use Hydrofon\Http\Requests\ObjectStoreRequest;
use Hydrofon\Http\Requests\ObjectUpdateRequest;
use Hydrofon\Object;

class ObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objects = Object::orderBy('name')->get();

        return view('objects.index')->with('objects', $objects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('objects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\ObjectStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ObjectStoreRequest $request)
    {
        Object::create($request->all());

        return redirect('/objects');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Hydrofon\Object $object
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Object $object)
    {
        return view('objects.show')->with('object', $object);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Hydrofon\Object $object
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Object $object)
    {
        return view('objects.edit')->with('object', $object);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\ObjectUpdateRequest $request
     * @param \Hydrofon\Object $object
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ObjectUpdateRequest $request, Object $object)
    {
        $object->update($request->all());

        return redirect('/objects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Object $object
     * @param \Hydrofon\Http\Requests\ObjectDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Object $object, ObjectDestroyRequest $request)
    {
        $object->delete();

        return redirect('/objects');
    }
}
