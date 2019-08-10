<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Group extends JsonResource
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
            'type'   => 'group',
            'id'     => (int) $this->id,
            'name'   => $this->name,
        ];
    }
}
