<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendaDetailResource extends JsonResource
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
            'agendaName'=>$this->agenda_name,
            'startTime'=>$this->start_time,
            'endTime'=>$this->end_time,
            'location'=>$this->location,
            'keynoteSpeaker'=>$this->keynote_speaker,
            'note'=>$this->note
        ];
    }
}
