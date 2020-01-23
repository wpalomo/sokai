<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formapago extends Model
{
    //
    protected $table="formapago";
    protected $primaryKey = "id";
    protected $fillable=['id', 'descrpFormaPago'];
}
