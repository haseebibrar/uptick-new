<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class AdminController extends Controller
{
    public function index(){
        //dd();
        if(Auth::user()->is_super > 0)
            return view('admin');
        else{
            $myCompID   = Auth::user()->company_id;
            $students   = User::where('company_id', '=', $myCompID)->get();
            return view('admin.hr', compact('students'));
        }
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
                'email'     => 'required',
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
        if(isset($request->password))
            $teacher->password   = Hash::make($request->password);
        $teacher->save();
        return redirect()->route('admin.teachers')->with('success','Record Updated Successfully.');
    }

    public function delTeachers($myID){
        //dd($myID);
        $rec = Teacher::find($myID);
        unlink("images/users/".$rec->image);
        Teacher::where("id", $rec->id)->delete();
        return redirect()->route('admin.teachers')->with('success','Record Deleted Successfully.');
    }
    ///////-------Teachers Section

    ///////-------Students Section
    public function getStudents($myID = '')
    {
        if(Auth::user()->is_super > 0){
		    $students   = User::all();
            $myCompID   = 0;
        }else{
            $myCompID   = Auth::user()->company_id;
            $students   = User::where('company_id', '=', $myCompID)->get();
        }
        return view('admin.student', compact('students', 'myCompID'));
    }

    public function addStudents(Request $request)
    {
        if(count($request->all()) === 0){
            if(Auth::user()->is_super > 0)
                $myCompID   = 0;
            else
                $myCompID   = Auth::user()->company_id;
            return view('admin.addstudent', compact('myCompID'));
        }else{
            $request->validate([
                'name'      => 'required',
                'email'     => 'required',
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
            if(isset($input['image']))
                $myImage = $input['image'];
            //dd($input);
            User::create([
                'company_id'=> $input['company_id'],
                'name'      => $input['name'],
                'email'     => $input['email'],
                'password'  => Hash::make($input['password']),
                'image'     => $myImage,
                'phone'     => $input['phone'],
            ]);
            if(Auth::user()->is_super > 0)
                return redirect()->route('admin.student')->with('success','Student Added Successfully.');
            else
                return redirect('/admin')->with('success','Student Added Successfully.');
        }
    }
    
    public function editStudents($myID)
    {
        if(Auth::user()->is_super > 0)
            $myCompID   = 0;
        else
            $myCompID   = Auth::user()->company_id;
        $students  = User::findorFail($myID);
        return view('admin.editstudent', compact('students', 'myCompID'));
    }

    public function updateStudents(Request $request)
    {
        //dd($request->all());
        $student  = User::findorFail($request->id);
        if ($image = $request->file('image')) {
            $destinationPath = 'images/users/';
            if(!empty($student->image))
                unlink($destinationPath.$student->image);
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $request->image = "$profileImage";
        }
        $student->company_id  = $request->company_id;
        $student->name        = $request->name;
        $student->email       = $request->email;
        if(isset($request->phone))
            $student->phone       = $request->phone;
        if(isset($request->expertise))
            $student->expertise   = $request->expertise;
        if(isset($request->image))
            $student->image   = $request->image;
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
        if(count($request->all()) === 0){
            return view('admin.company.addcompany');
        }else{
            $request->validate([
                'name'      => 'required',
            ]);
            $input = $request->all();
            Company::create([
                'name'      => $input['name'],
                'phone'     => $input['phone'],
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
            $request->validate([
                'name'      => 'required',
                'email'     => 'required',
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
            Admin::create([
                'company_id'=> $input['company_id'],
                'name'      => $input['name'],
                'email'     => $input['email'],
                'password'  => Hash::make($input['password']),
                'image'     => $input['image'],
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
    ///////-------Company Section
}