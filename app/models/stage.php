<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class stage extends Model
{
    protected $table      = 'stage';
    public    $timestamps = false;

    public function division()
    {
        return $this->belongsTo('App\Models\division', 'division_id');
    }
}