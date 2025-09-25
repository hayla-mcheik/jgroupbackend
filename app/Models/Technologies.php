<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technologies extends Model
{
    protected $table='technologies';
    protected $fillable=['title','description','image','color','links'];
}
