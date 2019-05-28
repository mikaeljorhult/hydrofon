<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'type'  => 'user',
            'id'    => (int) $this->id,
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }
}
