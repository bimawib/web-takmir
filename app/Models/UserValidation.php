<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'validation_code',
        'expiration_date'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
