<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Booking extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'resource'   => $this->resource_id,
            'created_by' => $this->created_by_id,
            'start'      => (int) $this->start_time->format('U'),
            'end'        => (int) $this->end_time->format('U'),
        ];
    }
}
