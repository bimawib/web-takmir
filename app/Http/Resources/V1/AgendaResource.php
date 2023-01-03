<?php

namespace App\Http\Resources\V1;

use App\Models\Agenda;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
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
            'slug'=>$this->slug,
            'name'=>$this->user->name,
            'title'=>$this->title,
            'location'=>$this->location,
            'date'=>$this->date,
            'image'=>$this->image,
            'agendaDetail'=>$this->agenda_detail
        ];
    }
}
