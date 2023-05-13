<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    protected $fillable = [
        'alias',
        'title',
        'description',
        'keywords',
        'robots',
        'content',
        'single_page',
        'img',
        'publish',
    ];
}
