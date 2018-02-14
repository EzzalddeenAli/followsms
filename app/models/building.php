<?php

namespace App\Models;

use Baum\Node;

class building extends Node
{
    protected $table      = 'building';
    public    $timestamps = false;

    protected $parentColumn = 'building_id';

    // 'lft' column name
    protected $leftColumn = 'lft';

    // 'rgt' column name
    protected $rightColumn = 'rght';

    // 'depth' column name
    protected $depthColumn = 'depth';

    // guard attributes from mass-assignment
    protected $guarded = array('id', 'building_id', 'lft', 'rght', 'depth');

    public function buildingType()
    {
        return $this->belongsTo('App\Models\buildingType', 'building_type_id');
    }

    public function parentBuilding()
    {
        return $this->belongsTo('App\Models\building', 'building_id');
    }




}