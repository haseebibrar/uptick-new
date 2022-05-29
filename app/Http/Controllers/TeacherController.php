<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FocusArea;
use App\Models\Teacher;
use App\Models\Event;
use App\Models\TeacherTimeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Redirect,Response;
use DB;

class TeacherController extends Controller
{
    public function index(){
        //dd('Test');
        $teacherID  = Auth::user()->id;
        $myTime     = $this->get_times();
        $sunData    = array();
        $monData    = array();
        $tueData    = array();
        $wedData    = array();
        $thuData    = array();
        $friData    = array();
        $satData    = array();
        $timetable  = TeacherTimeTable::where('teacher_id', '=', $teacherID)->get();
        if(!empty($timetable)){
            foreach($timetable as $myData){
                $time       = json_decode($myData['availabletime'], true);
                $myDay      = $myData['availableday'];
                $counter    = 0;
                // dd($myDay);
                foreach($time as $data){
                    if($myDay === "sun"){
                        $sunData[$counter][1] = $this->get_times($data[1]);
                        $sunData[$counter][2] = $this->get_times($data[2]);
                    }
                    if($myDay === "mon"){
                        $monData[$counter][1] = $this->get_times($data[1]);
                        $monData[$counter][2] = $this->get_times($data[2]);
                    }
                    if($myDay === "tue"){
                        $tueData[$counter][1] = $this->get_times($data[1]);
                        $tueData[$counter][2] = $this->get_times($data[2]);
                    }
                    if($myDay === "wed"){
                        $wedData[$counter][1] = $this->get_times($data[1]);
                        $wedData[$counter][2] = $this->get_times($data[2]);
                    }
                    if($myDay === "thu"){
                        $thuData[$counter][1] = $this->get_times($data[1]);
                        $thuData[$counter][2] = $this->get_times($data[2]);
                    }
                    if($myDay === "fri"){
                        $friData[$counter][1] = $this->get_times($data[1]);
                        $friData[$counter][2] = $this->get_times($data[2]);
                    }
                    if($myDay === "sat"){
                        $satData[$counter][1] = $this->get_times($data[1]);
                        $satData[$counter][2] = $this->get_times($data[2]);
                    }
                    //dd($data[1]);
                    $counter++;
                }
                // dd($tueData);
            }
        }
        
        return view('teacher.index', compact('teacherID', 'myTime', 'sunData', 'monData', 'tueData', 'wedData', 'thuData', 'friData', 'satData'));
    }

    public function get_times ($default = '', $interval = '+60 minutes') {
        $output = '';
        $current= strtotime('08:00');
        $end    = strtotime('20:00');
        while ($current <= $end) {
            $time = date('H:i', $current);
            $sel = ($time == $default) ? ' selected' : '';
            $output .= "<option value=\"{$time}\"{$sel}>" . date('h.i A', $current) .'</option>';
            $current = strtotime($interval, $current);
        }
        return $output;
    }

    public function updateTime(Request $request){
        //dd(Auth::user()->id);
        // dd($request->all());
        $teacherID  = Auth::user()->id;
        $mySunCheck = $this->saveData($request->suncheck[0], $teacherID, 'sun');
        $myMonCheck = $this->saveData($request->moncheck[0], $teacherID, 'mon');
        $myTueCheck = $this->saveData($request->tuecheck[0], $teacherID, 'tue');
        $myWedCheck = $this->saveData($request->wedcheck[0], $teacherID, 'wed');
        $myThuCheck = $this->saveData($request->thucheck[0], $teacherID, 'thu');
        $myFriCheck = $this->saveData($request->fricheck[0], $teacherID, 'fri');
        $mySatCheck = $this->saveData($request->satcheck[0], $teacherID, 'sat');
        return redirect()->route('teachers')->with('success','Availabilty Updated Successfully.'); 
    }

    public function saveData($myData, $teacherID, $myDay){
        if(isset($myData['name'])){
            $times = json_encode($myData['time']);
            $checkData = TeacherTimeTable::where('teacher_id', '=', $teacherID)->where('availableday', '=', $myDay)->first();
            if(!empty($checkData)){
                $checkData->availabletime = $times;
                $checkData->save();
            }else{
                TeacherTimeTable::create([
                    'teacher_id'    => $teacherID,
                    'availableday'  => $myData['name'],
                    'availabletime' => $times,
                ]);
            }
        }else{
            $checkData = TeacherTimeTable::where('teacher_id', '=', $teacherID)->where('availableday', '=', $myDay)->first();
            // dd($checkData->id);
            if(!empty($checkData))
                TeacherTimeTable::where('id',$checkData->id)->delete();
        }
    }

    public function openLessosns(){
        
    }

    public function calendarIndex(Request $request){
        // dd($request->start);
        if($request->start) 
        {
            //dd('testing');
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $data = Event::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)->get(['id','title','start', 'end']);
            //return $data;
            return response()->json($data);
        }
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
        //dd($request->all());
        $event = Event::where('id',$request->id)->delete();
        return Response::json($event);
    }
}