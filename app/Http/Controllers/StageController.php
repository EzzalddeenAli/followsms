<?php

namespace App\Http\Controllers;

use App\Models\stage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class StageController extends Controller
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

    }

    public function listAll($id)
    {
        return stage::where('division_id', $id)->with('division')->get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = stage::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delStage'], $this->panelInit->language['stageDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delStage'], $this->panelInit->language['stageNotExist']);
        }
    }

    public function create()
    {
        $stage             = new stage();
        $stage->ar_name    = Input::get('ar_name');
        $stage->en_name    = Input::get('en_name');
        $stage->division_id = \Input::get('division_id');
        $stage->active     = (int)\Input::get('active');
        $stage->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addStage']
            , $this->panelInit->language['stageAddSuc']
            , [
                "id"         => $stage->id,
                "ar_name"    => \Input::get('ar_name'),
                "en_name"    => \Input::get('en_name'),
                "division_id" => \Input::get('division_id'),
            ]
        );
    }

    public function fetch($id)
    {
        $stage = stage::where('id', $id)->first()->toArray();

        return $stage;
    }

    public function edit($id)
    {
        $stage             = stage::find($id);
        $stage->ar_name    = Input::get('ar_name');
        $stage->en_name    = Input::get('en_name');
        $stage->division_id = \Input::get('division_id');
        $stage->active     = (int)\Input::get('active');
        $stage->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editStage'],
            $this->panelInit->language['stageEditSuc'],
            [
                "id"      => $stage->id,
                "ar_name" => \Input::get('ar_name'),
                "en_name" => \Input::get('en_name'),
                "division_id" => \Input::get('division_id'),
            ]
        );
    }

}