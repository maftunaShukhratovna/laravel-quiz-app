<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable=[
        'name'
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
