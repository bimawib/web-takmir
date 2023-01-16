<?php

namespace App\Models;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaDetail extends Model
{
    use HasFactory;

    // protected $casts = [
    //     'start_time' => 'hh:mm',
    //     'end_time' => 'hh:mm'
    // ];

    protected $fillable = [
        'agenda_id',
        'agenda_name',
        'start_time',
        'end_time',
        'location',
        'keynote_speaker',
        'note'
    ];

    public function agenda(){
        return $this->belongsTo(Agenda::class);
    }
}

