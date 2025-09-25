<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    protected $table='hero_banners';
    protected $fillable=['video','image','titleone','titletwo','titlethree'];
}
