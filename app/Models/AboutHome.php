<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutHome extends Model
{
    protected $table='about_homes';
    protected $fillable=['image','description'];
}
