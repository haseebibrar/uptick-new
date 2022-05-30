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

class StudentController extends Controller
{
    public function index(){
        $teachers   = Teacher::all();
        $focusareas = FocusArea::all();
        return view('student.index', compact('teachers', 'focusareas'));
    }

    public function getTeachers(Request $request){
        $myData     = '';
        $teachers   = FocusAreaTeacher::where('focusarea_id', '=', $request->myFocusID)->get();
        if($teachers->isNotEmpty()){
            //dd($teachers);
            foreach($teachers as $teacher){
                //dd($teacher->teacher);
                $myImage    = '';
                if(!empty($teacher->teacher->image))
                    $myImage = asset('images/users/'.$teacher->teacher->image);
                
                $myData .= '<tr>
                                <td class="align-middle">'.($myImage === "" ? "" : '<img class="rounded-circle imgmr-1" style="max-width:50px; max-height:50px;" src="'.$myImage.'" alt="'.$teacher->teacher->name.'" title="'.$teacher->teacher->name.'" />').'</td>
                                <td class="align-middle">'.$teacher->teacher->name.'</td>
                                <td class="align-middle">'.$teacher->teacher->expertise.'</td>
                                <td class="text-nowrap align-middle"><a href="javascript:void(0)" data-focus="'.$request->myFocusID.'" data-id="'.$teacher->teacher->id.'" class="btn btnSchedule">Schedule</a></td>
                            </tr>';
            }
        }else{
            $myData = '<td colspan="3" class="text-center">No Teachers Available!</td>';
        }
        return $myData;
        //dd($teachers);
    }

    public function getTeachersDetail(Request $request){
        $myTeacherID    = $request->myTeacherID;
        $myFocusID      = $request->myFocusID;
        $teachers       = DB::table('focus_area_teachers')->where('focus_area_teachers.focusarea_id', '=', $myFocusID)->where('focus_area_teachers.teacher_id', '=', $myTeacherID)
                            ->join('focus_areas', 'focus_areas.id', '=', 'focus_area_teachers.focusarea_id')
                            ->join('lesson_subjects', 'lesson_subjects.id', '=', 'focus_area_teachers.lesson_id')
                            ->first(['focus_area_teachers.*', 'focus_areas.name as focusarea', 'lesson_subjects.name as lesson']);
        //where('focusarea_id', '=', $myFocusID)->where('teacher_id', '=', $myTeacherID)->first();
        $timetable      = TeacherTimeTable::where('teacher_id', '=', $myTeacherID)->get();
        //dd($teachers->teacher->timetable);
        dd($timetable);
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