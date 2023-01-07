<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'image'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user(){
        return $this->belongsTo(User::class);
    } // nama kolom foreign key bisa dikasih sebagai parameter kedua, ex (User::class,'user_id','id')
    // the belongsTo find the foreign key by method name (in this case user) and adding _id so its user_id
}
