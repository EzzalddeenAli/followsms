<?php


namespace App\Http\Controllers;


use App\Models\week;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class WeekController extends Controller
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
        return week::where('quarter_id', $id)->with('quarter')->get()->toArray();
    }

    public function delete($id)
    {
        if ($postDelete = week::where('id', $id)->first()) {
            $postDelete->delete();

            return $this->panelInit->apiOutput(true, $this->panelInit->language['delWeek'], $this->panelInit->language['weekDelSuc']);
        } else {
            return $this->panelInit->apiOutput(false, $this->panelInit->language['delWeek'], $this->panelInit->language['weekNotExist']);
        }
    }

    public function create()
    {
        $week              = new week();
        $week->name        = \Input::get('name');
        $week->quarter_id = \Input::get('quarter_id');
        $week->save();

        return $this->panelInit->apiOutput(true,
            $this->panelInit->language['addWeek']
            , $this->panelInit->language['weekAddSuc']
            , [
                "id"       => $week->id,
                'quarter' => $week->quarter_id,
                "name"     => \Input::get('name'),
            ]
        );
    }

    public function fetch($id)
    {
        $week = week::where('id', $id)->first()->toArray();

        return $week;
    }

    public function edit($id)
    {
        $week              = week::find($id);
        $week->name        = Input::get('name');
        $week->quarter_id = \Input::get('quarter_id');
        $week->save();

        return $this->panelInit->apiOutput(
            true,
            $this->panelInit->language['editWeek'],
            $this->panelInit->language['weekEditSuc'],
            [
                "id"       => $week->id,
                'quarter' => $week->quarter_id,
                "name"     => \Input::get('name'),
            ]
        );
    }

}