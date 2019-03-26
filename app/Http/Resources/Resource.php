<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => (int) $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'is_facility' => $this->is_facility,
            'categories'  => $this->categories->pluck('id'),
        ];
    }
}
