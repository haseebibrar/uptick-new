<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Teacher;
use App\Models\Event;
use App\Models\User;
use App\Models\Department;
use App\Models\FocusArea;
use App\Models\LessonSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Mail;
use App\Mail\NotifyMail;

class AdminController extends Controller
{
    public function index()
    {
        //dd();
        if(Auth::user()->is_super > 0)
            return view('admin');
        else{
            $myCompID       = Auth::user()->company_id;
            $students       = User::where('company_id', '=', $myCompID)->leftJoin('departments', 'users.dept_id', '=', 'departments.id')->select('users.*','departments.name as deptname')->ORDERBY('id', 'Asc')->get();
            $myCompany      = Company::where('id', '=', $myCompID)->first();
            $bankHours      = $myCompany->bank_hours;
            $remHours       = $myCompany->remaining_bank_hours;
            $totalStudents  = count($students);
            $totalConductW  = 0;
            $totalSchedtW   = 0;
            $totalConductM  = 0;
            $totalSchedtM   = 0;

            if($students->isNotEmpty()){
                $oldDate = date('Y-m-d', strtotime('-7 days'));
                $monDate = date('Y-m-d', strtotime('-30 days'));
                $curDate = date('Y-m-d H:i:s');
                foreach($students as $student){
                    $eventcondut   = Event::where('student_id', '=', $student->id)->where('status', '<>', 'canceled')->whereDate('start', '>=', $oldDate)->whereDate('start', '<=', $curDate)->get();
                    $totalConduct = count($eventcondut);

                    $eventshecd   = Event::where('student_id', '=', $student->id)->whereDate('start', '>=', $oldDate)->whereDate('start', '<=', $curDate)->get();
                    $totalSchedt  = count($eventshecd);

                    $eventcondutM   = Event::where('student_id', '=', $student->id)->where('status', '<>', 'canceled')->whereDate('start', '>=', $monDate)->whereDate('start', '<=', $curDate)->get();
                    $totalConductI = count($eventcondut);

                    $eventshecdM   = Event::where('student_id', '=', $student->id)->whereDate('start', '>=', $monDate)->whereDate('start', '<=', $curDate)->get();
                    $totalSchedtI  = count($eventshecd);

                    $totalConductW  = $totalConductW+$totalConduct;
                    $totalSchedtW   = $totalSchedtW+$totalSchedt;
                    $totalConductM  = $totalConductM+$totalConductI;
                    $totalSchedtM   = $totalSchedtM+$totalSchedtI;
                }
            }
            
            return view('admin.hr', compact('students', 'myCompID', 'totalStudents', 'bankHours', 'totalConductW', 'totalSchedtW', 'totalConductM', 'totalSchedtM', 'remHours'));
        }
    }

    public function divideHours(Request $request)
    {
        $myCompanyID    = $request->compID;
        $myCompany      = Company::where('id', '=', $myCompanyID)->first();
        $bankHours      = $myCompany->bank_hours;
        $totalStudents  = count($myCompany->students);
        if($myCompany->bank_hours > 0){
            if(!empty($myCompany->students)){
                foreach($myCompany->students as $students){
                    $myStudent    = User::where('id', '=', $students->id)->first();
                    $studentHr    = $myStudent->allocated_hour+6;
                    $remainHours  = $myStudent->remaining_hours+6;
                    $totalHours   = $myStudent->total_hours+6;
                    $myStudent->allocated_hour  = $studentHr;
                    $myStudent->remaining_hours = $remainHours;
                    $myStudent->total_hours     = $totalHours;
                    $myStudent->save();
                    $compRemHours   = $myCompany->remaining_bank_hours-6;
                    $compUsedHours  = $myCompany->used_hours+6;
                    $myCompany->remaining_bank_hours = $compRemHours;
                    $myCompany->used_hours           = $compUsedHours;
                    $myCompany->save();
                }
                return '<div style="font-size: 15px; text-align:center;">Hours allocated equally to students!</div>';
            }
        }else{
            return '<div style="font-size: 15px; text-align:center;">No hour left. Please contact your customer success person :)</div>';
        }
        return 'Please Add Students!';
        // dd($bankHours.' || '.$totalStudents.' || '.$hoursNum);
    }

    public function editProfile($id)
    {
        //dd(Auth::user());
        $users = Auth::user();
        return view('auth.editprofile', compact('users'));
    }

    public function updateProfile(Request $request)
    {
        if(Auth::guard('admin')->check()){
            $redirectUrl = '/admin';
            $profile  = Admin::findorFail($request->id);
        }elseif(Auth::guard('teacher')->check()){
            $redirectUrl = '/teacher';
            $profile  = Teacher::findorFail($request->id);
        }else{
            $redirectUrl = '/home';
            $profile  = User::findorFail($request->id);
        }
        
        if ($image = $request->file('image')) {
            $destinationPath = 'images/users/';
            if(!empty($teacher->image))
                unlink($destinationPath.$teacher->image);
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $request->image = "$profileImage";
        }
        $profile->name        = $request->name;
        $profile->email       = $request->email;
        if(isset($request->phone))
            $profile->phone       = $request->phone;
        if(isset($request->image))
            $profile->image   = $request->image;
        if(isset($request->zoom_link))
            $profile->zoom_link   = $request->zoom_link;
        if(isset($request->password))
            $profile->password   = Hash::make($request->password);
        $profile->save();
        return redirect($redirectUrl)->with('success','Profile Updated Successfully.');
    }

    ///////-------Teachers Section
    public function getTeachers()
    {
		$teachers  = Teacher::all();
        return view('admin.teacher', compact('teachers'));
    }

    public function addTeachers(Request $request)
    {
        if(count($request->all()) === 0){
            return view('admin.addteacher');
        }else{
            $request->validate([
                'name'      => 'required',
                'email'     => 'required|unique:teachers,email',
                'password'  => 'required_with:password_confirmation|same:password_confirmation',
                'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $input = $request->all();
            if ($image = $request->file('image')) {
                $destinationPath = 'images/users/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $input['image'] = "$profileImage";
            }
            //dd($input);
            Teacher::create([
                'name'      => $input['name'],
                'email'     => $input['email'],
                'password'  => Hash::make($input['password']),
                'image'     => $input['image'],
                'expertise' => $input['expertise'],
                'phone'     => $input['phone'],
                'zoom_link' => $input['zoom_link'],
            ]);
            return redirect()->route('admin.teachers')->with('success','Teacher Added Successfully.');
        }
    }
    
    public function editTeachers($myID)
    {
        $teachers  = Teacher::findorFail($myID);
        return view('admin.editteacher', compact('teachers'));
        
    }

    public function updateTeachers(Request $request)
    {
        //dd($request->all());
        $teacher  = Teacher::findorFail($request->id);
        if ($image = $request->file('image')) {
            $destinationPath = 'images/users/';
            if(!empty($teacher->image))
                unlink($destinationPath.$teacher->image);
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $request->image = "$profileImage";
        }
        $teacher->name        = $request->name;
        $teacher->email       = $request->email;
        if(isset($request->phone))
            $teacher->phone       = $request->phone;
        if(isset($request->expertise))
            $teacher->expertise   = $request->expertise;
        if(isset($request->image))
            $teacher->image   = $request->image;
        if(isset($request->zoom_link))
            $teacher->zoom_link   = $request->zoom_link;
        if(isset($request->password))
            $teacher->password   = Hash::make($request->password);
        $teacher->save();
        return redirect()->route('admin.teachers')->with('success','Record Updated Successfully.');
    }

    public function delTeachers($myID){
        //dd($myID);
        $rec = Teacher::find($myID);
        if(!empty($rec->image))
            unlink("images/users/".$rec->image); 
        //unlink("images/users/".$rec->image);
        Teacher::where("id", $rec->id)->delete();
        return redirect()->route('admin.teachers')->with('success','Record Deleted Successfully.');
    }
    ///////-------Teachers Section

    ///////-------Students Section
    public function getStudents($myID = '')
    {
        //dd($myID);
        if(Auth::user()->is_super > 0){
		    $students   = User::all();
            $myCompID   = 0;
        }else{
            $myCompID   = Auth::user()->company_id;
            $students   = User::where('company_id', '=', $myCompID)->leftJoin('departments', 'users.dept_id', '=', 'departments.id')->select('users.*','departments.name as deptname')->ORDERBY('id', 'DESC')->get();
            //$students   = User::where('company_id', '=', $myCompID)->get();
        }
        //dd($students);
        return view('admin.student', compact('students', 'myCompID'));
    }

    public function addStudents(Request $request)
    {
        if(count($request->all()) === 0){
            $myCompanies = '';
            if(Auth::user()->is_super > 0){
                $myCompID   = 0;
                $myCompanies= Company::all();
            }else
                $myCompID   = Auth::user()->company_id;
            $departments  = Department::all();
            return view('admin.addstudent', compact('myCompID', 'departments', 'myCompanies'));
        }else{
            $request->validate([
                'name'      => 'required',
                'email'     => 'required|unique:users,email',
                'password'  => 'required_with:password_confirmation|same:password_confirmation',
                'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $input = $request->all();
            if ($image = $request->file('image')) {
                $destinationPath = 'images/users/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $input['image'] = "$profileImage";
            }
            $myImage = '';
            $myCompID= $input['company_id'];
            if(isset($input['image']))
                $myImage = $input['image'];
            if(isset($input['company_id_admin']))
                $myCompID = $input['company_id_admin'];
            //dd($input);
            //$password = Hash::make($input['password']);
            User::create([
                'company_id'=> $myCompID,
                'name'      => $input['name'],
                'email'     => $input['email'],
                'password'  => Hash::make($input['password']),
                'image'     => $myImage,
                'phone'     => $input['phone'],
                'title'     => $input['title'],
                'dept_id'   => $input['dept_id'],
            ]);
            $emailData = [
                'first_name'=> $input['name'], 
                'email'     => $input['email'],
                'password'  => $input['password']
            ];
            Mail::to($input['email'])->send(new NotifyMail($emailData, 'signup'));
            if(Auth::user()->is_super > 0)
                return redirect()->route('admin.student')->with('success','Student Added Successfully.');
            else
                return redirect('/admin')->with('success','Student Added Successfully.');
        }
    }
    
    public function editStudents($myID)
    {
        $myCompanies = '';
        if(Auth::user()->is_super > 0){
            $myCompID   = 0;
            $myCompanies= Company::all();
        }else
            $myCompID   = Auth::user()->company_id;
        $students       = User::findorFail($myID);
        $departments    = Department::all();
        return view('admin.editstudent', compact('students', 'myCompID', 'departments', 'myCompanies'));
    }

    public function updateStudents(Request $request)
    {
        if(isset($request->company_id_admin))
            $compID = $request->company_id_admin;
        else
            $compID = $request->company_id;
        //dd($request->all());
        $student  = User::findorFail($request->id);
        $company = Company::findorFail($compID);
        if ($image = $request->file('image')) {
            $destinationPath = 'images/users/';
            if(!empty($student->image))
                unlink($destinationPath.$student->image);
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $request->image = "$profileImage";
        }
        $student->company_id  = $compID;
        $student->name        = $request->name;
        $student->email       = $request->email;
        if(isset($request->phone))
            $student->phone       = $request->phone;
        if(isset($request->title))
            $student->title   = $request->title;
        if(isset($request->dept_id))
            $student->dept_id   = $request->dept_id;
        if(isset($request->image))
            $student->image   = $request->image;
        if(isset($request->allocated_hour)){
            $oldtotalHours  = $student->total_hours;
            $oldremainHours = $student->remaining_hours;
            $oldHours       = $student->remaining_hours;
            $newHours       = $request->allocated_hour;
            $remainHoursComp= $company->remaining_bank_hours;
            if($newHours > $oldHours){
                $totalHours = ($newHours - $oldHours) + $oldtotalHours;
                $remainHours= ($newHours - $oldHours) + $oldremainHours;
                $remainHoursComp= $company->remaining_bank_hours-($newHours - $oldHours);
            }elseif($newHours < $oldHours){
                $totalHours     = $oldtotalHours-($oldHours - $newHours);
                $remainHours    = $oldremainHours-($oldHours - $newHours);
                $remainHoursComp= $company->remaining_bank_hours+($oldHours - $newHours);
            }
            // dd($totalHours);
            $student->total_hours           = $totalHours;
            $student->remaining_hours       = $remainHours;
            $student->allocated_hour        = $request->allocated_hour;
            $company->remaining_bank_hours  = $remainHoursComp;
            $company->save();
        }
        $student->save();
        if(Auth::user()->is_super > 0)
            return redirect()->route('admin.student')->with('success','Record Updated Successfully.');
        else
            return redirect('/admin')->with('success','Record Updated Successfully.');
        //return redirect()->route('admin.student')->with('success','Record Updated Successfully.');
    }

    public function delStudents($myID, $compid = ''){
        //dd($myID);
        $rec = User::find($myID);
        if(!empty($rec->image))
            unlink("images/users/".$rec->image); 
        User::where("id", $rec->id)->delete();
        if(Auth::user()->is_super > 0)
            return redirect()->route('admin.student')->with('success','Record Deleted Successfully.');
        else
            return redirect('/admin')->with('success','Record Updated Successfully.');
        //return redirect()->route('admin.student')->with('success','Record Deleted Successfully.');
    }
    ///////-------Students Section

    ///////-------Company Section
    public function getCompany()
    {
		$companies  = Company::all();
        return view('admin.company.company', compact('companies'));
    }

    public function addCompany(Request $request)
    {
        //dd($request->all());
        if(count($request->all()) === 0){
            return view('admin.company.addcompany');
        }else{
            $request->validate([
                'name'      => 'required',
            ]);
            $input = $request->all();
            Company::create([
                'name'                  => $input['name'],
                'phone'                 => $input['phone'],
                'total_bank_hours'      => $input['bank_hours'],
                'used_hours'            => 0,
                'bank_hours'            => $input['bank_hours'],
                'remaining_bank_hours'  => $input['bank_hours'],
            ]);
            return redirect()->route('admin.companies')->with('success','Company Added Successfully.');
        }
    }
    
    public function editCompany($myID)
    {
        $companies  = Company::findorFail($myID);
        return view('admin.company.editcompany', compact('companies'));
    }

    public function updateCompany(Request $request)
    {
        //dd($request->all());
        $company        = Company::findorFail($request->id);
        $company->name  = $request->name;
        if(isset($request->phone))
            $company->phone = $request->phone;
        if(isset($request->bank_hours)){
            $oldtotalHours  = $company->total_bank_hours;
            $oldremainHours = $company->remaining_bank_hours;
            $oldHours       = $company->remaining_bank_hours;
            $newHours       = $request->remaining_bank_hours;
            dd($newHours.' || '.$oldHours);
            if($newHours > $oldHours){
                $totalHours = ($newHours - $oldHours) + $oldtotalHours;
                $remainHours= ($newHours - $oldHours) + $oldremainHours;
            }elseif($newHours < $oldHours){
                $totalHours = $oldtotalHours-($oldHours - $newHours);
                $remainHours= $oldremainHours-($oldHours - $newHours);
            }else{
                $totalHours = $oldtotalHours+($oldHours - $newHours);
                $remainHours= $oldremainHours+($oldHours - $newHours);
            }
            // dd($totalHours);
            $company->total_bank_hours      = $totalHours;
            $company->remaining_bank_hours  = $remainHours;
            $company->bank_hours            = $request->bank_hours;
        }
        $company->save();
        return redirect()->route('admin.companies')->with('success','Record Updated Successfully.');
    }

    public function delCompany($myID){
        Company::where("id", $myID)->delete();
        return redirect()->route('admin.companies')->with('success','Record Deleted Successfully.');
    }
    ///////-------Company Section

    ///////-------Admin Users Section
    public function getUser()
    {
        $users = Admin::where('is_super', '=', 0)->leftJoin('companies', 'admins.company_id', '=', 'companies.id')->select('admins.*','companies.name as companyname')->ORDERBY('id', 'DESC')->get();
        return view('admin.adminusers.user', compact('users'));
    }

    public function addUser(Request $request)
    {
        if(count($request->all()) === 0){
            $companies  = Company::all();
            return view('admin.adminusers.adduser', compact('companies'));
        }else{
            $profileImage = '';
            $request->validate([
                'name'      => 'required',
                'email'     => 'required|unique:admins,email',
                'password'  => 'required_with:password_confirmation|same:password_confirmation',
                'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $input = $request->all();
            if ($image = $request->file('image')) {
                $destinationPath = 'images/users/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                // $input['image'] = $profileImage;
            }
            //dd($input);
            Admin::create([
                'company_id'=> $input['company_id'],
                'name'      => $input['name'],
                'email'     => $input['email'],
                'password'  => Hash::make($input['password']),
                'image'     => $profileImage,
                'phone'     => $input['phone'],
            ]);
            return redirect()->route('admin.users')->with('success','User Added Successfully.');
        }
    }
    
    public function editUser($myID)
    {
        //dd($myID);
        $users      = Admin::findorFail($myID);
        $companies  = Company::all();
        return view('admin.adminusers.edituser', compact('users', 'companies'));
    }

    public function updateUser(Request $request)
    {
        //dd($request->all());
        $user   = Admin::findorFail($request->id);
        if ($image = $request->file('image')) {
            $destinationPath = 'images/users/';
            if(!empty($user->image))
                unlink($destinationPath.$user->image);
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $request->image = "$profileImage";
        }
        $user->company_id  = $request->company_id;
        $user->name        = $request->name;
        $user->email       = $request->email;
        if(isset($request->phone))
            $user->phone       = $request->phone;
        if(isset($request->image))
            $user->image   = $request->image;
        if(isset($request->password))
            $user->password   = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.users')->with('success','Record Updated Successfully.');
    }

    public function delUser($myID){
        Admin::where("id", $myID)->delete();
        return redirect()->route('admin.users')->with('success','Record Deleted Successfully.');
    }
    ///////-------Admin User Section

    ///////-------Department Section
    public function getDepartment()
    {
		$departments  = Department::all();
        return view('admin.department.index', compact('departments'));
    }

    public function addDepartment(Request $request)
    {
        if(count($request->all()) === 0){
            return view('admin.department.adddepartment');
        }else{
            $request->validate([
                'name'      => 'required',
            ]);
            $input = $request->all();
            Department::create([
                'name'      => $input['name'],
            ]);
            return redirect()->route('admin.departments')->with('success','Department Added Successfully.');
        }
    }
    
    public function editDepartment($myID)
    {
        $departments  = Department::findorFail($myID);
        return view('admin.department.editdepartment', compact('departments'));
        
    }

    public function updateDepartment(Request $request)
    {
        //dd($request->all());
        $company        = Department::findorFail($request->id);
        $company->name  = $request->name;
        if(isset($request->phone))
            $company->phone = $request->phone;
        $company->save();
        return redirect()->route('admin.departments')->with('success','Record Updated Successfully.');
    }

    public function delDepartment($myID)
    {
        Department::where("id", $myID)->delete();
        return redirect()->route('admin.departments')->with('success','Record Deleted Successfully.');
    }
    ///////-------Department Section

    ///////-------Focus Area Section
    public function getFocusarea()
    {
		$focusareas  = FocusArea::all();
        return view('admin.focusarea.index', compact('focusareas'));
    }

    public function addFocusarea(Request $request)
    {
        if(count($request->all()) === 0){
            return view('admin.focusarea.addfocusarea');
        }else{
            $request->validate([
                'name'      => 'required',
            ]);
            $input = $request->all();
            FocusArea::create([
                'name'      => $input['name'],
            ]);
            return redirect()->route('admin.focusareas')->with('success','Focus Area Added Successfully.');
        }
    }
    
    public function editFocusarea($myID)
    {
        $focusareas  = FocusArea::findorFail($myID);
        return view('admin.focusarea.editfocusarea', compact('focusareas'));
    }

    public function updateFocusarea(Request $request)
    {
        //dd($request->all());
        $focusarea        = FocusArea::findorFail($request->id);
        $focusarea->name  = $request->name;
        $focusarea->save();
        return redirect()->route('admin.focusareas')->with('success','Record Updated Successfully.');
    }

    public function delFocusarea($myID)
    {
        FocusArea::where("id", $myID)->delete();
        return redirect()->route('admin.focusareas')->with('success','Record Deleted Successfully.');
    }
    ///////-------Focus Area Section

    ///////-------Lesson Subject Section
    public function getLessonsubject()
    {
		$lessonsubjects  = LessonSubject::all();
        return view('admin.lessonsubject.index', compact('lessonsubjects'));
    }

    public function addLessonsubject(Request $request)
    {
        if(count($request->all()) === 0){
            $focusareas  = FocusArea::all();
            return view('admin.lessonsubject.addlessonsubject', compact('focusareas'));
        }else{
            $request->validate([
                'name'      => 'required',
                'pdf_data'  => 'mimetypes:application/pdf',
            ]);
            $input = $request->all();
            if ($pdf_data = $request->file('pdf_data')) {
                $destinationPath = 'images/users/';
                $pdfFile = date('YmdHis') . "." . $pdf_data->getClientOriginalExtension();
                $pdf_data->move($destinationPath, $pdfFile);
                $input['pdf_data'] = "$pdfFile";
            }
            $myPDF = '';
            if(isset($input['pdf_data']))
                $myPDF = $input['pdf_data'];
            LessonSubject::create([
                'focusarea_id'=> $input['focusarea_id'],
                'name'        => $input['name'],
                'pdf_data'    => $myPDF,
            ]);
            return redirect()->route('admin.lessonsubjects')->with('success','Lesson Added Successfully.');
        }
    }
    
    public function editLessonsubject($myID)
    {
        $lessonsubjects = LessonSubject::findorFail($myID);
        $focusareas     = FocusArea::all();
        return view('admin.lessonsubject.editlessonsubject', compact('lessonsubjects', 'focusareas'));
    }

    public function updateLessonsubject(Request $request)
    {
        //dd($request->all());
        $lesson                = LessonSubject::findorFail($request->id);
        if ($image = $request->file('pdf_data')) {
            $destinationPath = 'images/users/';
            if(!empty($lesson->pdf_data))
                unlink($destinationPath.$student->pdf_data);
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $request->pdf_data = "$profileImage";
        }
        $lesson->name          = $request->name;
        $lesson->focusarea_id  = $request->focusarea_id;
        if(isset($request->pdf_data))
            $lesson->pdf_data   = $request->pdf_data;
        $lesson->save();
        return redirect()->route('admin.lessonsubjects')->with('success','Record Updated Successfully.');
    }

    public function delLessonsubject($myID){
        $rec = LessonSubject::find($myID);
        if(!empty($rec->pdf_data))
            unlink("images/users/".$rec->pdf_data); 
        LessonSubject::where("id", $myID)->delete();
        return redirect()->route('admin.lessonsubjects')->with('success','Record Deleted Successfully.');
    }
    ///////-------Lesson Subject Section
}