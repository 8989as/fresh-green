<?php

namespace Webkul\Contact\Models;

use Illuminate\Database\Eloquent\Model;

class LandscapeRequest extends Model
{
    protected $table = 'landscape_requests';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'city',
        'landscape_area',
        'message',
    ];
}
