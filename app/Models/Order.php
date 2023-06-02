<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'client_id',
        'service_id',
        'master_id',
        'status',
        'created',
        'end_dt',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function masters()
    {
        return $this->hasMany(Masters::class);
    }
    /*
    public function service()
    {
        return $this->hasMany(Service::class);
    }
    */
}
