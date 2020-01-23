<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = "empleado";
    protected $primaryKey = "id_empleado";
    protected $fillable = ['tipo_dni', 'dni', 'primer_nombre', 'segundo_nombre', 'apellido_paterno',
    'apellido_materno', 'fecha_nacimiento', 'edad', 'foto', 'lugar_nacimiento', 'nacionalidad', 
    'estado_civil', 'sexo', 'direccion_residencia', 'telefono', 'celular', 'email', 'tipo_sangre', 
    'profesion', 'discapacidad', 'discap_porcentaje', 'tipo_iden_discap', 'num_iden_discap', 'num_iess', 
    'num_libreta_militar', 'banco', 'tipo_cuenta', 'num_cuenta', 'num_cargas', 'estado', 'observaciones'];
}
