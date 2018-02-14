<?php


namespace App\Http\Controllers;


use App\Models\quarter;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class QuarterController extends Controller
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
        return quarter::where('semester_id', $id)->with('semester')->get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = quarter::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delQuarter'], $this->panelInit->language['quarterDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delQuarter'], $this->panelInit->language['quarterNotExist']);
        }
    }

    public function create()
    {
        $quarter              = new quarter();
        $quarter->name        = \Input::get('name');
        $quarter->semester_id = \Input::get('semester_id');
        $quarter->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addQuarter']
            , $this->panelInit->language['quarterAddSuc']
            , [
                "id"       => $quarter->id,
                'semester' => $quarter->semester_id,
                "name"     => \Input::get('name'),
            ]
        );
    }

    public function fetch($id)
    {
        $quarter = quarter::where('id', $id)->first()->toArray();

        return $quarter;
    }

    public function edit($id)
    {
        $quarter              = quarter::find($id);
        $quarter->name        = Input::get('name');
        $quarter->semester_id = \Input::get('semester_id');
        $quarter->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editQuarter'],
            $this->panelInit->language['quarterEditSuc'],
            [
                "id"       => $quarter->id,
                'semester' => $quarter->semester_id,
                "name"     => \Input::get('name'),
            ]
        );
    }

}