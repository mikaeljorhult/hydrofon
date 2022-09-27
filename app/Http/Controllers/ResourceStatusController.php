<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusDestroyRequest;
use App\Http\Requests\StatusStoreRequest;
use App\Models\Resource;
use App\Models\Status;

class ResourceStatusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Resource  $resource
     * @param  \App\Http\Requests\StatusStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Resource $resource, StatusStoreRequest $request)
    {
        $validatedData = $request->validated();

        $resource->setStatus($validatedData['name'], $validatedData['reason'] ?? null);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource  $resource
     * @param  \App\Models\Status  $status
     * @param  \App\Http\Requests\StatusDestroyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource, Status $status, StatusDestroyRequest $request)
    {
        $status->delete();

        return redirect()->back();
    }
}
