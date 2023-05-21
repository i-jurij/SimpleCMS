<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masters extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'master_name',
        'sec_name',
        'master_fam',
        'master_phone_number',
        'spec',
        'data_priema',
        'data_uvoln',
    ];
}