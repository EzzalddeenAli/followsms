<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class week extends Model
{
    protected $table      = 'week';
    public    $timestamps = false;

    public function quarter()
    {
        return $this->belongsTo('App\Models\quarter', 'quarter_id');
    }

    public function getFromAttribute($from)
    {
        $dashboard = new \DashboardInit();

        return $dashboard->unix_to_date($from);
    }

    public function getToAttribute($to)
    {
        $dashboard = new \DashboardInit();

        return $dashboard->unix_to_date($to);
    }
}