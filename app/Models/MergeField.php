<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MergeField extends Model
{
    use HasFactory;

    protected $fillable = [
        'list_id', 'name', 'type'
    ];
}
