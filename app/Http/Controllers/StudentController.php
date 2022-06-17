<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FocusArea;
use App\Models\Teacher;
use App\Models\Event;
use App\Models\User;
use App\Models\HomeWork;
use App\Models\HomeWorkDetail;
use App\Models\HomeWorkDetailsStudent;
use App\Models\FocusAreaTeacher;
use App\Models\TeacherTimeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Redirect,Response;
use DB;
use DateTime;
use Mail;
use App\Mail\NotifyMail;
use Spatie\CalendarLinks\Link;

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

    public function pastLessosns()
    {
        $compCount  = 0;
        $futCount   = 0;
        $curDate    = date('Y-m-d H:i:s');
        $studentID  = Auth::user()->id;
        $compEvents = DB::table('events')->where('events.student_id', '=', $studentID)->where('events.status', '<>', 'canceled')->whereDate('events.start', '<', $curDate)
                            ->join('focus_areas', 'focus_areas.id', '=', 'events.focusarea_id')
                            ->join('teachers', 'teachers.id', '=', 'events.teacher_id')
                            ->leftJoin('home_works', 'home_works.focusarea_id', '=', 'events.focusarea_id')
                            ->leftJoin('focus_area_teachers', function($join){
                                $join->on('focus_area_teachers.focusarea_id', '=', 'events.focusarea_id');
                                $join->on('focus_area_teachers.teacher_id', '=', 'events.teacher_id');
                            })
                            ->leftJoin('lesson_subjects', 'lesson_subjects.focusarea_id', '=', 'events.focusarea_id')
                            ->get(['events.*', 'focus_areas.name as focusarea', 'teachers.name as teacher', 'teachers.image as teacherimage', 'focus_area_teachers.embeded_url as embeded_url', 'home_works.id as homeworkid', 'lesson_subjects.pdf_data']);
        $compCount  = count($compEvents);

        $futureEvents = DB::table('events')->where('events.student_id', '=', $studentID)->where('events.status', '<>', 'canceled')->whereDate('events.start', '>=', $curDate)
                            ->join('focus_areas', 'focus_areas.id', '=', 'events.focusarea_id')
                            ->join('teachers', 'teachers.id', '=', 'events.teacher_id')
                            ->leftJoin('lesson_subjects', 'lesson_subjects.focusarea_id', '=', 'events.focusarea_id')
                            ->get(['events.*', 'focus_areas.name as focusarea', 'teachers.name as teacher', 'teachers.zoom_link', 'teachers.expertise', 'teachers.image as teacherimage', 'lesson_subjects.pdf_data']);
        $futCount     = count($futureEvents);
        // dd($futureEvents);
        // $eventshecd   = Event::where('student_id', '=', $student->id)->whereDate('start', '>=', $oldDate)->whereDate('start', '<=', $curDate)->get();
        // $totalSchedt  = count($eventshecd);
        return view('student.pastfuture', compact('compEvents', 'futureEvents', 'compCount', 'futCount', 'studentID'));
    }

    public function studentHomework($myID)
    {
        $instruct   = '';
        $homework_id= $myID;
        $studentID  = Auth::user()->id;
        $homeWork   = HomeWork::where('id', '=', $myID)->first();
        $instruct   = $homeWork->instructions_text;
        $myHomeWork = DB::table('home_work_details')->where('home_work_details.homework_id', '=', $myID)
                            ->leftJoin('home_work_details_students', function($join){
                                $join->where('home_work_details_students.student_id', '=', Auth::user()->id);
                                $join->on('home_work_details_students.homework_id', '=', 'home_work_details.homework_id');
                                $join->on('home_work_details_students.question_id', '=', 'home_work_details.id');
                            })
                            ->orderBy('home_work_details.id')
                            ->get(['home_work_details.*', 'home_work_details_students.id as student_homeworkid', 'home_work_details_students.question_id', 'home_work_details_students.answer_name']);
        // dd($myHomeWork);
        return view('student.homework', compact('myHomeWork', 'instruct', 'homework_id', 'studentID'));
    }

    public function saveHomeWork(Request $request)
    {
        // dd($request->question);
        if(isset($request->question)){
            foreach ($request->question as $key => $value) {//homework_id
                // dd($value);
                $homework  = HomeWorkDetailsStudent::where('homework_id', '=', $request->homework_id)->where('student_id', '=', $request->student_id)->where('question_id', '=', $key)->first();
                if(!empty($homework)){
                    $homework->homework_id  = $request->homework_id;
                    $homework->student_id   = $request->student_id;
                    $homework->question_id  = $key;
                    $homework->answer_name  = $value;
                    $homework->save();
                }else{
                    //dd($request->key);
                    HomeWorkDetailsStudent::create([
                        'homework_id'   => $request->homework_id,
                        'student_id'    => $request->student_id,
                        'question_id'   => $key,
                        'answer_name'   => $value,
                    ]);
                }
            }
        }
        return back();
    }

    public function getCalEvents(Request $request)
    {
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
        // dd(Auth::user()->remaining_hours);
        if(Auth::user()->remaining_hours > 0){
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

    public function showCalEdit(Request $request)
    {
        // $eventData = Event::where('id', '=', $request->myEventID)->first();
        $data   = DB::table('events')->where('events.id', '=', $request->myEventID)
                            ->join('focus_areas', 'focus_areas.id', '=', 'events.focusarea_id')
                            ->join('teachers', 'teachers.id', '=', 'events.teacher_id')
                            ->first(['events.id', 'events.start', 'events.end', 'focus_areas.name as title', 'focus_areas.id as focusid', 'teachers.name as teachername', 'teachers.id as teacherid', 'teachers.zoom_link']);
        $strDate            = $data->start;
        $data->starttime    = date('Y-m-d H:i:s', strtotime($strDate));
        $data->endtime      = date('Y-m-d H:i:s', strtotime($data->end));
        $data->full         = date('M d Y', strtotime($strDate));
        $data->start        = date('l, F, d h:i', strtotime($strDate));
        $data->end          = date('h:i a', strtotime($data->end));
        // dd($data);
        return $data;
    }

    public function getCalEdit(Request $request)
    {
        $eventData      = Event::where('id', '=', $request->myEventID)->first();
        $myDay          = strtolower(date('D', strtotime($eventData->start)));
        $myTeacherID    = $eventData->teacher_id;
        $myFocusID      = $eventData->focusarea_id;
        $myStart        = date('H:i', strtotime($eventData->start));
        $myEnd          = date('H:i', strtotime($eventData->end));
        $startOptions   = '';
        $endOptions     = '';
        // return $myTeacherID;
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

    public function saveEvent(Request $request)
    {
        $studentID  = Auth::user()->id;
        $emailStude = Auth::user()->email;
        $teacherDt  = Teacher::where('id', '=', $request->teacher_id)->first();
        $student    = User::where('id', '=', $studentID)->first();
        $myHours    = intval($student->remaining_hours) - 1;
        $myUsedHour = intval($student->used_hours) + 1;
        $student->remaining_hours   = $myHours;
        $student->used_hours        = $myUsedHour;
        $student->save();
        $starttime  = new DateTime($request->event_date.' '.$request->starttime.':00');
        $endtime    = new DateTime($request->event_date.' '.$request->endtime.':00');
        // dd($datetime);
        // class_name   
        //Remove allocated hour of student
        // $from = DateTime::createFromFormat('Y-m-d H:i', $request->event_date.' '.$request->starttime);
        // $to = DateTime::createFromFormat('Y-m-d H:i', $request->event_date.' '.$request->endtime);
        $link = Link::create('Uptick Lesson', $starttime, $endtime)
                ->description('with '.$teacherDt->name);
        // $link->ics();
        //dd($link->ics());
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
        $emailData = [
            'first_name'=> $student->name, 
            'zoom_link' => $teacherDt->zoom_link,
            'teacher'   => $teacherDt->name,
            'icslink'   => $link->ics()
        ];
        Mail::to($emailStude)->send(new NotifyMail($emailData, 'eventbook'));
        // $emailData = [
        //     'first_name'=>'Haseeb Ibrar', 
        //     'email'=>'john@doe.com',
        //     'password'=>'temp'
        // ];
        // Mail::to('lahorewebdesign@gmail.com')->send(new NotifyMail($emailData, 'signup'));
        // if (Mail::failures()) {
        //     //return response()->Fail('Sorry! Please try again latter');
        // }else{
        //     //return response()->success('Great! Successfully send in your mail');
        // }
        return redirect('/home')->with('success','Booked Successfully.');
    }

    public function editEvent(Request $request)
    {
        // dd($request->all());
        $starttime  = new DateTime($request->edit_event_date.' '.$request->starttime.':00');
        $endtime    = new DateTime($request->edit_event_date.' '.$request->endtime.':00');
        $where      = array('id' => $request->eventeditid);
        $updateArr  = ['start' => $starttime, 'end' => $endtime];
        $event      = Event::where($where)->update($updateArr);
        return redirect('/home')->with('success','Updated Successfully.');
    }

    public function downloadLesson(Request $request)
    {
        // dd($request->all());
        $where      = array('id' => $request->myEventID);
        $updateArr  = ['file_downloaded' => 1];
        $event      = Event::where($where)->update($updateArr);
        return 1;
    }
 
    public function update(Request $request)
    {   
        $where      = array('id' => $request->id);
        $updateArr  = ['title' => $request->title,'start' => $request->start, 'end' => $request->end];
        $event      = Event::where($where)->update($updateArr);
 
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
            $myHours    = intval($student->remaining_hours) + 1;
            $myUsedHour = intval($student->used_hours) - 1;
            $student->remaining_hours   = $myHours;
            $student->used_hours        = $myUsedHour;
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
            if ($current >= $start_time && $current <= $end_time){
                $sel = ($time == $default) ? ' selected' : '';
                $output .= "<option value=\"{$time}\"{$sel}>" . date('h.i A', $current) .'</option>';
            }
            $current = strtotime($interval, $current);
        }
        return $output;
    }
}