<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // return parent::toArray($request);
       return [
           'name' =>$this->name,
           'description' =>$this->description,
           'image' =>$this->image,
           'status' =>$this->status,
           'service_id' =>$this->service_id,
           'client_id' =>$this->client_id
       ];
    }
}
