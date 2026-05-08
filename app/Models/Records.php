<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Records extends Model
{
    protected $primaryKey = 'record_id';
    protected $table ='records';
    protected $fillable = [
        'student_id',
        'violation_id',
    ];

    public function students(){
        return $this->belongsTo(Students::class, 'student_id', 'student_id');
    }
    public function violations(){
        return $this->belongsTo(Violations::class, 'violation_id', 'violation_id');
    }
}
