<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table="rol";
    protected $primaryKey = "id_rol";
    protected $fillable=['nombre', 'descripcion', 'fcrea', 'fmodifica', 'ucrea', 'umodifica'];
}
