<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $tbale='settings';
    protected $fillable=['logo','twitter','facebook','youtube','linkedin','instagram'];
}
