<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'is_spend',
        'spend_balance',
        'incoming_balance',
        'total_balance',
        'note',
        'date'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    } // fillable only to is_owner
}
