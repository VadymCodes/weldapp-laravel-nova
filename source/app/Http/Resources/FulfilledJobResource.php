<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FulfilledJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fulfilled' => (bool) $this->fulfilled,
            'driver_rating' => $this->driver_rating,
            'driver_comment' => $this->driver_comment,
            'mechanic_rating' => $this->mechanic_rating,
            'mechanic_comment' => $this->mechanic_comment,
            'job' => new JobResource($this->whenLoaded('job')),
            'bid' => new BidResource($this->whenLoaded('bid'))
        ];
    }
}
