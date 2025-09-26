<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class contact extends Pivot
{
    protected $table='contact';
    protected $fillable=['name','email','message'];
}
