<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    protected $primaryKey = 'student_id';
    protected $table = 'students';

    protected $fillable = [
        'student_id',
        'student_name',
        'sr_code',
        'program_id',
        'gsuite',
        'password',

    ];

    public function programs(){
        return $this->belongsTo(Programs::class, 'program_id');
    }
    public function records(){
        return $this->hasMany(Records::class, 'record_id', 'record_id');
    }
}
