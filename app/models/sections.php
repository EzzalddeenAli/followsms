<?php

use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    public    $timestamps = false;
    protected $table      = 'sections';

    public function parentBuilding()
    {
        return $this->belongsTo('App\Models\building', 'building_id');
    }
}
