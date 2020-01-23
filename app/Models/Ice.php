<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ice extends Model
{
    protected $table="ice";
    protected $primaryKey = "id_ice";
    protected $fillable =['nombre'];
    public $timestamps= false;
}
