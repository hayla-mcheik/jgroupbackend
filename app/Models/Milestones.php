<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestones extends Model
{
    protected $table='milestones';
    protected $fillable=['title','description','date','image'];
}
