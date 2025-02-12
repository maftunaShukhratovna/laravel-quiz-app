<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable=[
        'result_id',
        'question_id'
    ];
}
