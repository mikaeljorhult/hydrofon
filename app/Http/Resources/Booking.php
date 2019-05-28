<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Booking extends JsonResource
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
        $booking = [
            'type'       => 'booking',
            'id'         => (int) $this->id,
            'user'       => (int) $this->user_id,
            'resource'   => (int) $this->resource_id,
            'created_by' => (int) $this->created_by_id,
            'start'      => (int) $this->start_time->format('U'),
            'end'        => (int) $this->end_time->format('U'),
        ];

        if ($this->relationLoaded('user')) {
            $booking['title'] = $this->user->name;
        }

        return $booking;
    }
}
