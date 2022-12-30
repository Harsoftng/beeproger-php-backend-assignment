<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $table = 'todos';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
}
