<?php

use Illuminate\Database\Eloquent\Model;

class classes extends Model {
	public $timestamps = false;
	protected $table = 'classes';

    public function division()
    {
        return $this->belongsTo('App\Models\division', 'division_id');
    }

    public function stage()
    {
        return $this->belongsTo('App\Models\stage', 'stage_id');
    }
}