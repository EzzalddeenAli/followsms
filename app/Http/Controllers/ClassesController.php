<?php

namespace App\Http\Controllers;

use App\Models\division;

class ClassesController extends Controller
{

    var $data   = [];
    var $panelInit;
    var $layout = 'dashboard';

    /**
     * ClassesController constructor.
     */
    public function __construct()
    {
//        if (app('request')->header('Authorization') != "") {
//            $this->middleware('jwt.auth');
//        } else {
//            $this->middleware('authApplication');
//        }

        $this->panelInit                      = new \DashboardInit();
        $this->data['panelInit']              = $this->panelInit;
        $this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
        $this->data['users']                  = $this->panelInit->getAuthUser();
        if (!isset($this->data['users']->id)) {
            return \Redirect::to('/');
        }
        if ($this->data['users']->role != "admin" AND $this->data['users']->role != "teacher") exit;

        if (!$this->panelInit->hasThePerm('classes')) {
            exit;
        }
    }

    public function listAll()
    {
        $toReturn            = [];
        $teachers            = \User::where('role', 'teacher')->get()->toArray();
        $toReturn['classes'] = [];
        $classes             = \classes::with(['division', 'stage'])->where('classAcademicYear', $this->panelInit->selectAcYear);

        if (isset($this->data['users']) && $this->data['users']->role == "teacher") {
            $classes = $classes->where('classTeacher', 'LIKE', '%"' . $this->data['users']->id . '"%');
        }

        $classes = $classes->get();


        $toReturn['teachers'] = [];
        foreach ($teachers as $teacherKey => $teacherValue) {
            $toReturn['teachers'][$teacherValue['id']] = $teacherValue;
        }

        $classes->map(function ($class) use ($toReturn) {
            if (!empty($class->classTeacher)) {
                $teachersIds  = json_decode($class->classTeacher, true);
                $teacherNames = [];
                if (!empty($teachersIds)) {
                    foreach ($teachersIds as $teacherId)
                        if (isset($toReturn['teachers'][$teacherId]['fullName'])) {
                            $teacherNames[] = $toReturn['teachers'][$teacherId]['fullName'];
                        }
                }
                $class->classTeacher = implode($teacherNames, ", ");
            }

            return $class;
        });

        $toReturn['classes']   = $classes->toArray();
        $toReturn['divisions'] = division::where('active', 1)->get()->toArray();

        return $toReturn;
    }

    public function delete($id)
    {
        if ($this->data['users']->role != "admin") exit;

        if ($postDelete = \classes::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delClass'], $this->panelInit->language['classDeleted']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delClass'], $this->panelInit->language['classNotExist']);
        }
    }

    public function create()
    {
        if ($this->data['users']->role != "admin") exit;

        $classes                    = new \classes();
        $classes->className         = \Input::get('className');
        $classes->classTeacher      = json_encode(\Input::get('classTeacher'));
        $classes->classAcademicYear = $this->panelInit->selectAcYear;
        $classes->division_id       = \Input::get('division_id');
        $classes->stage_id          = \Input::get('stage_id');
//        $classes->classSubjects     = json_encode(\Input::get('classSubjects'));
//        if (\Input::has('dormitoryId')) {
//            $classes->dormitoryId = \Input::get('dormitoryId');
//        }
        $classes->save();

        $classes->classTeacher = "";
        $teachersList          = \User::whereIn('id', \Input::get('classTeacher'))->get();
        foreach ($teachersList as $teacher) {
            $classes->classTeacher .= $teacher->fullName . ", ";
        }

        return $this->panelInit->apiOutput(true, $this->panelInit->language['addClass'], $this->panelInit->language['classCreated'], $classes->toArray());
    }

    function fetch($id)
    {
        $classDetail                  = \classes::where('id', $id)->first()->toArray();
        $classDetail['classTeacher']  = json_decode($classDetail['classTeacher']);

        return $classDetail;
    }

    function edit($id)
    {
        if ($this->data['users']->role != "admin") exit;

        $classes                = \classes::find($id);
        $classes->className     = \Input::get('className');
        $classes->classTeacher  = json_encode(\Input::get('classTeacher'));
        $classes->division_id       = \Input::get('division_id');
        $classes->stage_id          = \Input::get('stage_id');

        $classes->save();

        $classes->classTeacher = "";
        $teachersList          = \User::whereIn('id', \Input::get('classTeacher'))->get();
        foreach ($teachersList as $teacher) {
            $classes->classTeacher .= $teacher->fullName . ", ";
        }
        $classes->classSubjects = json_decode($classes->classSubjects);

        return $this->panelInit->apiOutput(true, $this->panelInit->language['editClass'], $this->panelInit->language['classUpdated'], $classes->toArray());
    }

}
