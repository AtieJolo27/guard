<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationDescriptions extends Model
{
    protected $primaryKey = 'description_id';

    public function violations(){
        return $this->belongsTo(Violations::class, 'violation_id', 'violation_id');
    }
    public function violation_descriptions(){
        return $this->belongsTo(Descriptions::class, 'description_id', 'description_id');
    }
}
