<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table="roles";
    protected $primaryKey = "id_roles";
    protected $fillable=['nombre', 'value', 'ver', 'editar', 'crear', 'eliminar', 'id_user'];
    public $timestamps = false;
}
