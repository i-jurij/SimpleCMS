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

    public function getImageAttribute($value)
    {
        return mb_str_replace('images/', '', $value);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class)->withDefault();
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function masters()
    {
        return $this->belongsToMany(Master::class);
    }
}
