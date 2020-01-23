<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    //
    protected $table="user";
    protected $primaryKey = "id";
    protected $fillable=['id', 'emisor_id', 'rol_id', 'username', 'password', 'email', 'nombre', 'apellidos', 'salt', 'is_active', 'createdAt', 'updatedAt', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
