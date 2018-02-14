<?php

namespace App\Http\Controllers;

use App\Models\building;
use Illuminate\Support\Facades\Redirect;

class BuildingController extends Controller
{
    public $data;
    public $panelInit;

    public function __construct()
    {
//        if (app('request')->header('Authorization') != "") {
//            $this->middleware('jwt.auth');
//        } else {
//            $this->middleware('authApplication');
//        }

        $this->panelInit         = new \DashboardInit();
        $this->data['panelInit'] = $this->panelInit;
        $this->data['users']     = $this->panelInit->getAuthUser();
        if (!isset($this->data['users']->id)) {
            return Redirect::to('/');
        }
        if ($this->data['users']->role != "admin") exit;

        if (!$this->panelInit->hasThePerm('academicyears')) {
            exit;
        }
    }

    public function listAll()
    {
        building::rebuild();
        return building::with(['buildingType', 'parentBuilding'])->get()->toArray();
    }

    public function listParents()
    {
        return building::where('building_id', '')
                       ->orWhere(function ($query) {
                           $query->whereNull('building_id');
                       })
                       ->get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = building::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delBuilding'], $this->panelInit->language['buildingDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delBuilding'], $this->panelInit->language['buildingNotExist']);
        }
    }

    public function create()
    {
        $building                   = new building();
        $building->ar_name          = \Input::get('ar_name');
        $building->en_name          = \Input::get('en_name');
        $building->description      = \Input::get('description');
        $building->building_type_id = \Input::get('building_type_id');
        $building->building_id      = \Input::get('building_id');
        $building->active = (int)\Input::get('active');

        $building->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addBuilding']
            , $this->panelInit->language['buildingAddSuc']
            , [
                "id"      => $building->id,
                "ar_name" => \Input::get('ar_name'),
                "en_name" => \Input::get('en_name'),
            ]
        );
    }

    public function fetch($id)
    {
        $building = building::where('id', $id)->first()->toArray();

        return $building;
    }

    public function edit($id)
    {
        $building                   = building::find($id);
        $building->ar_name          = \Input::get('ar_name');
        $building->en_name          = \Input::get('en_name');
        $building->description      = \Input::get('description');
        $building->building_type_id = \Input::get('building_type_id');
        $building->building_id      = \Input::get('building_id');
        $building->active           = (int)\Input::get('active');
        $building->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editBuilding'],
            $this->panelInit->language['buildingEditSuc'],
            [
                "id"      => $building->id,
                "ar_name" => \Input::get('ar_name'),
                "en_name" => \Input::get('en_name'),
            ]
        );
    }

}