<?php

namespace Hydrofon\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IdentifierCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
