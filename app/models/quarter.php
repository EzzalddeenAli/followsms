<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quarter extends Model
{
    protected $table      = 'quarter';
    public    $timestamps = false;

    public function semester()
    {
        return $this->belongsTo('App\Models\semester', 'semester_id');
    }
}