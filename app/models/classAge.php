<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class classAge extends Model
{
    protected $table      = 'class_ages';
    public    $timestamps = false;

    public function academicYear()
    {
        return $this->belongsTo('\academic_year', 'academic_year_id');
    }

    public function classes()
    {
        return $this->belongsTo('\classes', 'class_id');
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