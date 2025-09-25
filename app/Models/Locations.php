<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
            protected $table='locations';
            protected $fillable=['name','email','phone','description','image','position_x','position_y','color'];
}
