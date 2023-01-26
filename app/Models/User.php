<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Blog;
use App\Models\Lost;
use App\Models\Found;
use App\Models\Agenda;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blog(){
        return $this->hasMany(Blog::class);
    }
    public function lost(){
        return $this->hasMany(Lost::class);
    }
    public function found(){
        return $this->hasMany(Found::class);
    }
    public function agenda(){
        return $this->hasMany(Agenda::class);
    }
    public function balance(){
        return $this->hasMany(Balance::class);
    }
    public function userValidation(){
        return $this->hasMany(UserValidation::class);
    }

}
