<?php

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $user = User::where('id',$this->user_id)->first();
        
        return [
            'id'=>$this->id,
            'name'=>$this->user->name,
            'slug'=>$this->slug,
            'title'=>$this->title,
            'body'=>$this->body,
            'image'=>$this->image,
            'isVerified'=>$this->is_verified,
            'publishedAt'=>$this->published_at
        ];
    }
}
