<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Violations extends Model
{
    protected $primaryKey = 'violation_id';

    public function records(){
        return $this->hasMany(Records::class, 'violation_id', 'violation_id');
    }

    public function violation_descriptions(){
        return $this->hasMany(ViolationDescriptions::class, 'description_id', 'description_id');
    }
}
