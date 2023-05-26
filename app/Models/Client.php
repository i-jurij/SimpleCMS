<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'contacts'];

    protected $casts = [
        'contacts' => 'array',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
