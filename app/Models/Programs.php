<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programs extends Model
{
    protected $primaryKey = 'program_id';
    protected $table = 'programs';
    
    protected function students(){
        return $this->hasOne(Programs::class, 'program_id');
    }

    public function programs(){
        return $this->hasMany(Students::class, 'student_id');
    }
}
