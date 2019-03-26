<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'parent'      => optional($this->parent)->id,
            'name'        => $this->name,
        ];
    }
}
