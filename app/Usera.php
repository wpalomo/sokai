<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usera extends Authenticatable
{
    use Notifiable;
    protected $table = "user";
    protected $primaryKey = "id"; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombres', 'apellidos', 'password', 'email', 'estado', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_rol', 'id_empresa', 'id_establecimiento', 'id_punto_emision'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
