<?php

namespace App\Http\Resources\V1;

use App\Models\Agenda;
use App\Http\Resources\V1\AgendaDetailResource;
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
            'agendaDetail'=>AgendaDetailResource::collection($this->whenLoaded('agenda_detail'))
        ];
    }
}
// whenLoaded hanya akan dipanggil kalau relasi sudah dipanggil di instance (di kasus ini instance $agenda)
// for show method always include relation for agenda_detail