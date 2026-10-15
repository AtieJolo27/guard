<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guards extends Model
{
    protected $primaryKey = 'guard_id';

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }
}
