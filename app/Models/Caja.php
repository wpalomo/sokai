<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    //
    protected $table="caja";
    protected $primaryKey ='id_caja';
    protected $fillable =['descrip_caja', 'grupo_caja', 'cuenta_contable' ];
}
