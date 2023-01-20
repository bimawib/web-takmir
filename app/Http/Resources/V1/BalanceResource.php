<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
            'note'=>$this->note,
            'isSpend'=>$this->is_spend,
            'spendBalance'=>$this->spend_balance,
            'incomingBalance'=>$this->incoming_balance,
            'totalBalance'=>$this->total_balance,
            'date'=>$this->date,
            'createdAt'=>$this->created_at
        ];
    }
}
