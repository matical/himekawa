<?php

namespace himekawa\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WatchedAppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'         => $this->name,
            'slug'         => $this->slug,
            'package_name' => $this->package_name,
            'image'        => url($this->image),
            'apps'         => AvailableAppResource::collection($this->whenLoaded('availableApps')),
        ];
    }
}
