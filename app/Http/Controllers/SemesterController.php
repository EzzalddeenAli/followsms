<?php

namespace App\Http\Controllers;

use App\Models\semester;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SemesterController extends Controller
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

    public function listAll($id)
    {
        return semester::where('academic_year_id', $id)->with('academicYear')->get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = semester::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delSemester'], $this->panelInit->language['semesterDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delSemester'], $this->panelInit->language['semesterNotExist']);
        }
    }

    public function create()
    {
        $semester                   = new semester();
        $semester->name             = \Input::get('name');
        $semester->academic_year_id = \Input::get('academic_year_id');
        $semester->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addSemester']
            , $this->panelInit->language['semesterAddSuc']
            , [
                "id"            => $semester->id,
                'academic_year' => $semester->academic_year_id,
                "name"          => \Input::get('name'),
            ]
        );
    }

    public function fetch($id)
    {
        $semester = semester::where('id', $id)->first()->toArray();

        return $semester;
    }

    public function edit($id)
    {
        $semester                   = semester::find($id);
        $semester->name             = Input::get('name');
        $semester->academic_year_id = \Input::get('academic_year_id');
        $semester->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editSemester'],
            $this->panelInit->language['semesterEditSuc'],
            [
                "id"            => $semester->id,
                'academic_year' => $semester->academic_year_id,
                "name"          => \Input::get('name'),
            ]
        );
    }

}