<?php

namespace App\Http\Controllers;

use App\Models\building;

class sectionsController extends Controller
{

    var $data   = [];
    var $panelInit;
    var $layout = 'dashboard';

    public function __construct()
    {
        if (app('request')->header('Authorization') != "") {
            $this->middleware('jwt.auth');
        } else {
            $this->middleware('authApplication');
        }

        $this->panelInit                      = new \DashboardInit();
        $this->data['panelInit']              = $this->panelInit;
        $this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
        $this->data['users']                  = $this->panelInit->getAuthUser();
        if (!isset($this->data['users']->id)) {
            return \Redirect::to('/');
        }
        if ($this->data['users']->role != "admin") exit;

        if (!$this->panelInit->hasThePerm('classes')) {
            exit;
        }
    }

    public function listAll()
    {
        $lang     = $this->panelInit->languageUniversal;
        $toReturn = [];

        $classesIn = [];
        $classes   = \classes::where('classAcademicYear', $this->panelInit->selectAcYear)->get();
        foreach ($classes as $value) {
            $toReturn['classes'][$value->id] = $value->className;
            $classesIn[]                     = $value->id;
        }

        $toReturn['sections'] = [];
        if (count($classesIn) > 0) {
            $sections = \DB::table('sections')
                           ->select('sections.id as id',
                               'sections.sectionName as sectionName',
                               'sections.sectionTitle as sectionTitle',
                               'sections.classId as classId',
                               'sections.teacherId as teacherId',
                               'sections.building_id as building_id'
                           )
                           ->whereIn('sections.classId', $classesIn)
                           ->get();

            foreach ($sections as $key => $section) {
                $sections[$key]->teacherId = json_decode($sections[$key]->teacherId, true);
                $sections[$key]->building  = building::where('id', $section->building_id)->first();
                if (isset($toReturn['classes'][$section->classId])) {
                    $toReturn['sections'][$toReturn['classes'][$section->classId]][] = $section;
                }
            }
        }

        $toReturn['teachers'] = [];
        $teachers             = \User::where('role', 'teacher')->get();
        foreach ($teachers as $value) {
            $toReturn['teachers'][$value->id] = $value->fullName;
        }

        $toReturn['buildings'] = building::getNestedList($lang . '_name', 'id', '--');

        return $toReturn;
    }

    public function delete($id)
    {
        if ($postDelete = \sections::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delSection'], $this->panelInit->language['sectionDeleted']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delSection'], $this->panelInit->language['sectionNotExist']);
        }
    }

    public function create()
    {
        $sections               = new \sections();
        $sections->sectionName  = \Input::get('sectionName');
        $sections->sectionTitle = \Input::get('sectionTitle');
        $sections->classId      = \Input::get('classId');
        $sections->building_id  = \Input::get('building_id');
        $sections->teacherId    = json_encode(\Input::get('teacherId'));
        $sections->save();

        return $this->panelInit->apiOutput(true, $this->panelInit->language['addSection'], $this->panelInit->language['sectionAdded']);
    }

    function fetch($id)
    {
        $sections              = \sections::where('id', $id)->first()->toArray();
        $sections['teacherId'] = json_decode($sections['teacherId'], true);

        return $sections;
    }

    function edit($id)
    {
        $sections               = \sections::find($id);
        $sections->sectionName  = \Input::get('sectionName');
        $sections->sectionTitle = \Input::get('sectionTitle');
        $sections->classId      = \Input::get('classId');
        $sections->building_id  = \Input::get('building_id');
        $sections->teacherId    = json_encode(\Input::get('teacherId'));
        $sections->save();

        return $this->panelInit->apiOutput(true, $this->panelInit->language['editSection'], $this->panelInit->language['sectionUpdated']);
    }

}
