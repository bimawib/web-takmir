<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class LostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->user->name,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'note'=>$this->note,
            'contact'=>$this->contact,
            'isReturned'=>$this->is_returned,
            'lostDate'=>$this->date
        ];
    }
}
