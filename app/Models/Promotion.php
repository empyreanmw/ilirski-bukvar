<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'uuid',
        'enrolled',
        'rejected',
        'email',
        'first_name',
        'last_name',
        'street',
        'city',
        'phone_number',
    ];

    protected $casts = [
        'enrolled' => 'boolean',
        'rejected' => 'boolean',
    ];
}
