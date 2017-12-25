<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Object;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @param  \Hydrofon\Object $object
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Object $object)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Hydrofon\Object $object
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Object $object)
    {
        //
    }
}
