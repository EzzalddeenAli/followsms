<?php

namespace App\Http\Controllers;

use App\Models\division;
use Illuminate\Support\Facades\Redirect;

class DivisionController extends Controller
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
        return division::get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = division::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delDivision'], $this->panelInit->language['divisionDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delDivision'], $this->panelInit->language['divisionNotExist']);
        }
    }

    public function create()
    {
        $division          = new division();
        $division->ar_name = \Input::get('ar_name');
        $division->en_name = \Input::get('en_name');
        $division->active = (int)\Input::get('active');
        $division->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addDivision']
            , $this->panelInit->language['divisionAddSuc']
            , [
                "id"      => $division->id,
                "ar_name" => \Input::get('ar_name'),
                "en_name" => \Input::get('en_name'),
            ]
        );
    }

    public function fetch($id)
    {
        $division = division::where('id', $id)->first()->toArray();

        return $division;
    }

    public function edit($id)
    {
        $division          = division::find($id);
        $division->ar_name = \Input::get('ar_name');
        $division->en_name = \Input::get('en_name');
        $division->active = (int)\Input::get('active');
        $division->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editDivision'],
            $this->panelInit->language['divisionEditSuc'],
            [
                "id"            => $division->id,
                "ar_name" => \Input::get('ar_name'),
                "en_name" => \Input::get('en_name'),
            ]
        );
    }

}