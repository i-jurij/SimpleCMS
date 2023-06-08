<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'page_id',
        'category_id',
        'image',
        'name',
        'description',
        'price',
        'duration',
    ];

    public function getNameAttribute($value)
    {
        if (function_exists('mb_ucfirst')) {
            return mb_ucfirst($value);
        } else {
            return $value;
        }
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}