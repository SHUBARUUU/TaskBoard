<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id', //   Temporary code.
        'title',
        'description',
        'status',
        'priority',
        'due_date'
    ];
}
