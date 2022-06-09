<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FocusArea;
use App\Models\Teacher;
use App\Models\Event;
use App\Models\User;
use App\Models\FocusAreaTeacher;
use App\Models\TeacherTimeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Redirect,Response;
use DB;
use DateTime;

class StudentController extends Controller
{
    public function index(){
        $teachers   = Teacher::all();
        $focusareas = FocusArea::all();
        $studentID  = Auth::user()->id;
        return view('student.index', compact('teachers', 'focusareas', 'studentID'));
    }

    public function getTeachers(Request $request)
    {
        $myData     = '';
        $myDay      = strtolower($request->myDay);
        $myTime     = date('h:i a', strtotime($request->myTime));
        // $teachers   = FocusAreaTeacher::where('focusarea_id', '=', $request->myFocusID)->get();
        $teachers   = Teacher::all();
        // dd($request->all());
        // dd($myTime);
        // date('H:i', $current);
        if($teachers->isNotEmpty()){
            // dd($teacher);
            $foundData = 'no';
            foreach($teachers as $teacher){
                $foundDay = 'no';
                // dd($teacher->teacher->timetable);
                $timetable = $teacher->timetable;
                foreach($timetable as $myday){
                    if($myday['availableday'] === $myDay){
                        $time  = json_decode($myday['availabletime'], true);
                        foreach($time as $data){
                            $start_time = date('h:i a', strtotime($data[1]));
                            $end_time   = date('h:i a', strtotime($data[2]));
                            //dd($myTime.' || '.$start_time.' || '.$end_time);
                            if (strtotime($myTime) >= strtotime($start_time) && strtotime($myTime) < strtotime($end_time)){
                                //dd($myTime);
                                $foundDay = 'yes';
                                break;
                            }
                        }
                    }
                }
                //dd($foundDay);
                if($foundDay === "yes"){
                    $myImage    = '';
                    $foundData  = 'yes';
                    if(!empty($teacher->image))
                        $myImage = asset('images/users/'.$teacher->image);
                    
                    $myData .= '<tr>
                                <td class="align-middle">'.($myImage === "" ? "" : '<img class="rounded-circle imgmr-1" style="max-width:50px; max-height:50px;" src="'.$myImage.'" alt="'.$teacher->name.'" title="'.$teacher->name.'" />').'</td>
                                <td class="align-middle">'.$teacher->name.'</td>
                                <td class="text-nowrap align-middle"><a href="javascript:void(0)" data-name="'.$teacher->name.'" data-focus="'.$request->myFocusID.'" data-id="'.$teacher->id.'" class="btn btnSchedule">Schedule</a></td>
                            </tr>';
                }
            }
            // dd($foundData);
            if($foundData === "no")
                $myData = '<td colspan="3" class="text-center">No available teachers!</td>';
        }else{
            $myData = '<td colspan="3" class="text-center">No available teachers!</td>';
        }
        return $myData;
        //dd($teachers);
    }

    public function getTeachersDetail(Request $request)
    {
        // dd($request->all());
        $myDay          = strtolower($request->myDay);
        $myTeacherID    = $request->myTeacherID;
        $myFocusID      = $request->myFocusID;
        $myStart        = date('H:i', strtotime($request->myStart));
        $myEnd          = date('H:i', strtotime($request->myEnd));
        $startOptions   = '';
        $endOptions     = '';
        // $teachers       = DB::table('focus_area_teachers')->where('focus_area_teachers.focusarea_id', '=', $myFocusID)->where('focus_area_teachers.teacher_id', '=', $myTeacherID)
        //                     ->join('focus_areas', 'focus_areas.id', '=', 'focus_area_teachers.focusarea_id')
        //                     ->join('lesson_subjects', 'lesson_subjects.id', '=', 'focus_area_teachers.lesson_id')
        //                     ->first(['focus_area_teachers.*', 'focus_areas.name as focusarea', 'lesson_subjects.name as lesson']);
        //where('focusarea_id', '=', $myFocusID)->where('teacher_id', '=', $myTeacherID)->first();
        $timetable      = TeacherTimeTable::where('teacher_id', '=', $myTeacherID)->where('availableday', '=', $myDay)->first();
        if(!empty($timetable)){
            $time           = json_decode($timetable['availabletime'], true);
            $timeData       = array();
            $counter        = 0;
            foreach($time as $data){
                $timeData[$counter][1] = $this->getTimes($myStart, $data[1], $data[2]);
                $timeData[$counter][2] = $this->getTimes($myEnd, $data[1], $data[2]);
                $counter++;
            }

            foreach($timeData as $myTime){
                $startOptions .= $myTime[1];
                $endOptions .= $myTime[2];
            }
            $myData = '<div class="col-md-3"><select name="starttime" class="form-control">'.$startOptions.'</select></div><div class="col-md-1 text-center"> - </div><div class="col-md-3"><select name="endtime" class="form-control">'.$endOptions.'</select></div></div>';
        }else{
            $myData = '1';
        }
            // dd($myData);
        return $myData;
    }

    public function pastLessosns(){
        return view('student.pastfuture');
    }

    public function studentHomework(){
        return view('student.homework');
    }

    public function getCalEvents(Request $request){
        // dd($request->start);
        if($request->start) 
        {
            //dd('testing');
            $studentID  = Auth::user()->id;
            $start  = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end    = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $data   = DB::table('events')->where('events.student_id', '=', $studentID)->where('events.status', '<>', 'canceled')->whereDate('events.start', '>=', $start)->whereDate('events.end', '<=', $end)
                            ->join('focus_areas', 'focus_areas.id', '=', 'events.focusarea_id')
                            ->join('teachers', 'teachers.id', '=', 'events.teacher_id')
                            ->get(['events.id', 'events.start', 'events.end', 'events.class_name as className', 'focus_areas.name as title', 'teachers.name as description']);
            // $data   = Event::where('student_id', '=', $studentID)->whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)->get(['id','teacher_id','start', 'end']);
            //return $data;
            return response()->json($data);
        }
    }

    public function getClickData(Request $request)
    {
        if(!empty(Auth::user()->allocated_hour)){
            $strDate    = substr($request->start,4,20);
            $endDate    = substr($request->end,4,20);
            $myDateFull = substr($request->start,4,11);
            $myDay      = date('D', strtotime($strDate));
            $myDate     = date('Y-m-d', strtotime($strDate));
            $myStart    = date('Y-m-d H:i:s', strtotime($strDate));
            $myEnd      = date('Y-m-d H:i:s', strtotime($endDate));
            $datedata = $myDay.'--'.$myDate.'--'.$myStart.'--'.$myEnd.'--'.$myDateFull;
            return $datedata;
        }else{
            return '1';
        }
    }

    public function getCalEdit(Request $request){

    }

    public function saveEvent(Request $request)
    {
        $studentID  = Auth::user()->id;
        $student    = User::where('id', '=', $studentID)->first();
        $myHours    = intval($student->allocated_hour) - 1;
        $student->allocated_hour    = $myHours;
        $student->save();
        $starttime = new DateTime($request->event_date.' '.$request->starttime.':00');
        $endtime = new DateTime($request->event_date.' '.$request->endtime.':00');
        // dd($datetime);
        // class_name   
        //Remove allocated hour of student
        $insertArr = [ 'focusarea_id' => $request->focusarea_id,
                       'teacher_id' => $request->teacher_id,
                       'lesson_id' => $request->lesson_id,
                       'student_id' => $request->student_id,
                       'title' => '',
                       'start' => $starttime,
                       'end' => $endtime,
                       'class_name' => 'scheduledEvent'
                    ];
        $event = Event::insert($insertArr);   
        return redirect('/home')->with('success','Record Updated Successfully.');
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
        $bookData = Event::where('id', '=', $request->myEventID)->first();
        $myTime = strtotime($bookData->start);
        if($myTime > time() + 86400){
            $bookData->status = 'canceled';
            $bookData->save();
            $studentID  = Auth::user()->id;
            $student    = User::where('id', '=', $studentID)->first();
            $myHours    = intval($student->allocated_hour) + 1;
            $student->allocated_hour    = $myHours;
            $student->save();
        }else{
            if($request->myLessHour === '1'){
                $bookData->status = 'canceled';
                $bookData->save();
            }else
                return 'No';
        }
        return 'Done';
        //dd($request->all());
        // $event = Event::where('id',$request->id)->delete();
        // return Response::json($event);
    }

    public function getTimes ($default = '', $startTime, $endTime, $interval = '+60 minutes')
    {
        $output     = '';
        $current    = strtotime('07:00');
        $end        = strtotime('23:00');
        $start_time = strtotime($startTime);
        $end_time   = strtotime($endTime);
        while ($current <= $end) {
            $time   = date('H:i', $current);
            //$myTime = date('H:i', strtotime($current));
            //dd($current.' || '.$start_time.' || '.$end_time);
            if ($current >= $start_time && $current <= $end_time){
                $sel = ($time == $default) ? ' selected' : '';
                $output .= "<option value=\"{$time}\"{$sel}>" . date('h.i A', $current) .'</option>';
            }
            $current = strtotime($interval, $current);
        }
        return $output;
    }
}