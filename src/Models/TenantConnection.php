<?php

namespace Miracuthbert\Multitenancy\Models;

use Illuminate\Database\Eloquent\Model;

class TenantConnection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'database',
        'host',
        'port',
        'username',
        'password',
    ];
}
