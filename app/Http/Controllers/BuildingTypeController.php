<?php

namespace App\Http\Controllers;

use App\Models\buildingType;
use Illuminate\Support\Facades\Redirect;

class BuildingTypeController extends Controller
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
        return buildingType::get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = buildingType::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delBuildingType'], $this->panelInit->language['buildingTypeDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delBuildingType'], $this->panelInit->language['buildingTypeNotExist']);
        }
    }

    public function create()
    {
        $buildingType          = new buildingType();
        $buildingType->ar_name = \Input::get('ar_name');
        $buildingType->en_name = \Input::get('en_name');
        $buildingType->icon    = \Input::get('icon');
        $buildingType->active  = (int)\Input::get('active');
        $buildingType->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addBuildingType']
            , $this->panelInit->language['buildingTypeAddSuc']
            , [
                "id"      => $buildingType->id,
                "ar_name" => \Input::get('ar_name'),
                "en_name" => \Input::get('en_name'),
            ]
        );
    }

    public function fetch($id)
    {
        $buildingType = buildingType::where('id', $id)->first()->toArray();

        return $buildingType;
    }

    public function edit($id)
    {
        $buildingType          = buildingType::find($id);
        $buildingType->ar_name = \Input::get('ar_name');
        $buildingType->en_name = \Input::get('en_name');
        $buildingType->icon    = \Input::get('icon');
        $buildingType->active  = (int)\Input::get('active');
        $buildingType->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editBuildingType'],
            $this->panelInit->language['buildingTypeEditSuc'],
            [
                "id"      => $buildingType->id,
                "ar_name" => \Input::get('ar_name'),
                "en_name" => \Input::get('en_name'),
            ]
        );
    }

}