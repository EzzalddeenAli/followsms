<?php

namespace App\Http\Controllers;

use App\Models\classAge;
use Illuminate\Support\Facades\Redirect;

class ClassAgesController extends Controller
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
        return classAge::where('academic_year_id', $id)->with('academicYear', 'classes')->get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = classAge::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delClassAge'], $this->panelInit->language['classAgeDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delClassAge'], $this->panelInit->language['classAgeNotExist']);
        }
    }

    public function create()
    {
        $classAge                   = new classAge();
        $classAge->academic_year_id = \Input::get('academic_year_id');
        $classAge->class_id         = \Input::get('class_id');

        $classAge->from = $this->panelInit->date_to_unix(\Input::get('from'));
        $classAge->to   = $this->panelInit->date_to_unix(\Input::get('to'));

        $classAge->years  = \Input::get('years');
        $classAge->months = \Input::get('months');
        $classAge->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addClassAge']
            , $this->panelInit->language['classAgeAddSuc']
            , [
                "id"            => $classAge->id,
                'academic_year' => $classAge->academic_year_id
            ]
        );
    }

    public function fetch($id)
    {
        $classAge = classAge::where('id', $id)->first()->toArray();

        return $classAge;
    }

    public function edit($id)
    {
        $classAge = classAge::find($id);

        $classAge->academic_year_id = \Input::get('academic_year_id');
        $classAge->class_id         = \Input::get('class_id');
        $classAge->from             = $this->panelInit->date_to_unix(\Input::get('from'));
        $classAge->to               = $this->panelInit->date_to_unix(\Input::get('to'));

        $classAge->years  = \Input::get('years');
        $classAge->months = \Input::get('months');

        $classAge->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editClassAge'],
            $this->panelInit->language['classAgeEditSuc'],
            [
                "id"            => $classAge->id,
                'academic_year' => $classAge->academic_year_id,
            ]
        );
    }

}