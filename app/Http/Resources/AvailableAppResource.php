<?php

namespace himekawa\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableAppResource extends JsonResource
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
            'version_code'  => $this->version_code,
            'version_name'  => $this->version_name,
            'size'          => $this->size,
            'hash'          => $this->hash,
            'url'           => $this->url(),
            'downloaded_on' => $this->created_at->toDateTimeString(),
        ];
    }
}