<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FocusArea;
use App\Models\Teacher;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Redirect,Response;
use DB;

class StudentController extends Controller
{
    public function index(){
        $teachers   = Teacher::all();
        $focusareas = FocusArea::all();
        return view('student.index', compact('teachers', 'focusareas'));
    }

    public function pastLessosns(){
        return view('student.pastfuture');
    }

    public function studentHomework(){
        return view('student.homework');
    }

    public function calendarIndex(Request $request){
        // dd($request->start);
        if($request->start) 
        {
            //dd('testing');
            $start  = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end    = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $data   = Event::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)->get(['id','title','start', 'end']);
            //return $data;
            return response()->json($data);
        }
        //$teachers   = '';
        //$focusareas = '';
        // $teachers   = Teacher::all();
        // $focusareas = FocusArea::all();
        // return view('student.index', compact('teachers', 'focusareas'));
    }

    public function create(Request $request)
    {
        dd($request->all());
        $insertArr = [ 'title' => $request->title,
                       'start' => $request->start,
                       'end' => $request->end
                    ];
        $event = Event::insert($insertArr);   
        return Response::json($event);
    }
 
    public function update(Request $request)
    {   
        $where = array('id' => $request->id);
        $updateArr = ['title' => $request->title,'start' => $request->start, 'end' => $request->end];
        $event  = Event::where($where)->update($updateArr);
 
        return Response::json($event);
    }
 
    public function eventDelete(Request $request)
    {
        // dd($request->all());
        $event = Event::where('id',$request->id)->delete();
        return Response::json($event);
    }
}