<?php

namespace App\Models;

use App\Models\User;
use App\Models\AgendaDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    // protected $casts = [
    //     'date'=>'d-m-Y'
    // ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'image',
        'location',
        'date'
    ];

    public function agenda_detail(){
        return $this->hasMany(AgendaDetail::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
