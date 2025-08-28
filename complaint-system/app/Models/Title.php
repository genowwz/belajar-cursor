<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $fillable = [
        'name',
        'min_complaints',
        'max_complaints',
        'color',
        'description',
    ];
}
