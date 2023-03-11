<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceDestroyRequest;
use App\Http\Requests\ResourceStoreRequest;
use App\Http\Requests\ResourceUpdateRequest;
use App\Models\Category;
use App\Models\Flag;
use App\Models\Group;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ResourceController extends Controller
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
        $items = QueryBuilder::for(Resource::class)
                             ->allowedFilters([
                                 'name',
                                 'is_facility',
                                 'categories.id',
                                 'groups.id',
                                 AllowedFilter::scope('flags', 'currentStatus'),
                             ])
                             ->defaultSort('name')
                             ->allowedSorts(['name', 'description', 'is_facility'])
                             ->paginate(15);

        $filterCategories = Category::orderBy('name')->pluck('name', 'id');
        $filterGroups = Group::orderBy('name')->pluck('name', 'id');
        $filterFlags = Flag::pluck('name', 'abbr');

        return view('resources.index')->with(compact([
            'items',
            'filterCategories',
            'filterGroups',
            'filterFlags',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categoryOptions = Category::orderBy('name')->pluck('name', 'id');
        $groupOptions = Group::orderBy('name')->pluck('name', 'id');

        return view('resources.create')->with(compact([
            'categoryOptions',
            'groupOptions',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResourceStoreRequest $request): RedirectResponse
    {
        $input = $request->validated();
        $input['is_facility'] = $request->has('is_facility');

        $resource = Resource::create($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Resource was created')
            ->content('Resource "'.$resource->name.'" was created successfully.')
            ->success();

        return redirect()->route('resources.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource): View
    {
        $resource->load([
            'activities.causer:id,name' => function ($query) {
                $query->oldest();
            },
        ]);

        return view('resources.show')->with('resource', $resource);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource): View
    {
        $categoryOptions = Category::orderBy('name')->pluck('name', 'id');
        $groupOptions = Group::orderBy('name')->pluck('name', 'id');

        return view('resources.edit')->with(compact([
            'resource',
            'categoryOptions',
            'groupOptions',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceUpdateRequest $request, Resource $resource): RedirectResponse
    {
        $input = $request->validated();
        $input['is_facility'] = $request->has('is_facility');

        $resource->update($input);
        $resource->categories()->sync($request->get('categories'));
        $resource->groups()->sync($request->get('groups'));

        laraflash()
            ->message()
            ->title('Resource was updated')
            ->content('Resource was updated successfully.')
            ->success();

        return redirect()->route('resources.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource, ResourceDestroyRequest $request): RedirectResponse
    {
        $resource->delete();

        laraflash()
            ->message()
            ->title('Resource was deleted')
            ->content('Resource was deleted successfully.')
            ->success();

        return redirect()->route('resources.index');
    }
}
