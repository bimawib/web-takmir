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
            'enumeratorName'=>$this->user->name,
            'name'=>$this->name,
            'note'=>$this->note,
            'isSpend'=>$this->is_spend,
            'spendBalance'=>$this->spend_balance,
            'incomingBalance'=>$this->incoming_balance,
            'totalBalance'=>$this->total_balance,
            'createdAt'=>$this->created_at
        ];
    }
}
