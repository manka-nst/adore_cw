<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
        'link' =>$this->link,
        'job' =>$this->job,
        'status' =>$this->status,
        'facebook_icon'=>$this->facebook_icon,
        'twitter_icon'=>$this->twitter_icon,
        'linkedIn'=>$this->linkedIn_icon,
        'image' =>$this->image,


    ];
    }
}
