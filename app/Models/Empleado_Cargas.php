<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado_Cargas extends Model
{
    protected $table = "cargas_empleado";
    protected $primaryKey = "id_carga";
    protected $fillable = ['car_dni', 'car_nombre', 'car_fecha_nacimiento', 'car_edad', 'car_ocupacion', 
    'car_parentezco', 'car_discapacidad', 'car_discap_porcentaje', 'idempleado'];
}