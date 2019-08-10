<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Identifier extends JsonResource
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
            'type'              => 'identifier',
            'id'                => (int) $this->id,
            'value'             => $this->value,
            'identifiable_type' => $this->identifiable_type,
            'identifiable_id'   => $this->identifiable_id,
        ];
    }
}
