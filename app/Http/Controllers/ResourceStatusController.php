<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StatusDestroyRequest;
use App\Http\Requests\StatusStoreRequest;
use App\Models\Resource;
use App\Models\Status;

class ResourceStatusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Resource $resource, StatusStoreRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $resource->setStatus($validatedData['name'], $validatedData['reason'] ?? null);

        activity()
            ->performedOn($resource)
            ->event($validatedData['name'])
            ->withProperties([
                'reason' => $validatedData['reason'] ?? null,
            ])
            ->log('flagged');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource, Status $status, StatusDestroyRequest $request): RedirectResponse
    {
        $status->delete();

        activity()
            ->performedOn($resource)
            ->event($status->name)
            ->log('deflagged');

        return redirect()->back();
    }
}
