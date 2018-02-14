<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class semester extends Model
{
    protected $table      = 'semester';
    public    $timestamps = false;

    public function academicYear()
    {
        return $this->belongsTo('\academic_year', 'academic_year_id');
    }
}