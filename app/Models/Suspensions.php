<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspensions extends Model
{
    protected $primaryKey = 'suspension_id';
    protected $table = 'suspensions';
    protected $fillable = [
        'record_id',
        'num_of_days_suspended',
        'remaining_days',
    ];

    public function records(){
        return $this->hasMany(Records::class, 'record_id', 'record_id');
    }
}
