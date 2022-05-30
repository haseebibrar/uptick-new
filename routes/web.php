<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;

Route::view('/', 'welcome');
Auth::routes();

Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm']);
Route::get('/login/teacher', [LoginController::class,'showTeacherLoginForm']);
Route::get('/register/admin', [RegisterController::class,'showAdminRegisterForm']);
Route::get('/register/teacher', [RegisterController::class,'showTeacherRegisterForm']);

Route::post('/login/admin', [LoginController::class,'adminLogin']);
Route::post('/login/teacher', [LoginController::class,'teacherLogin']);
Route::post('/register/admin', [RegisterController::class,'createAdmin']);
Route::post('/register/teacher', [RegisterController::class,'createTeacher']);

Route::group(['middleware' => 'auth'], function () {
    // Route::view('/home', 'home');
    Route::get('/home', [StudentController::class, 'index']);
    Route::get('/editprofile/{id}', [AdminController::class, 'editProfile']);
    Route::post('/updateprofile', [AdminController::class, 'updateProfile']);
    Route::get('/past-future-lesson', [StudentController::class, 'pastLessosns']);
    Route::get('/homework', [StudentController::class, 'studentHomework']);
    Route::post('/geteachers', [StudentController::class, 'getTeachers']);
    Route::post('/openbookpoup', [StudentController::class, 'getTeachersDetail']);
    //fullcalender
    Route::get('/fullcalendareventmaster', [StudentController::class, 'calendarIndex']);
    Route::post('/eventdelete', [StudentController::class, 'eventDelete']);
    Route::post('/fullcalendareventmaster/create', [StudentController::class, 'create']);
    Route::post('/fullcalendareventmaster/update', [StudentController::class, 'update']);
    Route::post('/fullcalendareventmaster/delete', [StudentController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:teacher'], function () {
    Route::get('/teacher', [TeacherController::class, 'index'])->name('teachers');;
    Route::get('/edittprofile/{id}', [AdminController::class, 'editProfile']);
    Route::post('/updatetprofile', [AdminController::class, 'updateProfile']);
    Route::post('/teacher/updatetime', [TeacherController::class, 'updateTime']);
    Route::get('/teacher/open-lesson', [TeacherController::class, 'openLessons']);
    Route::get('/teacher/lessons-materials', [TeacherController::class, 'lessonsMaterial'])->name('teacher.focusareas');
    Route::any('/teacher/lessons-material/add', [TeacherController::class, 'addLessonMaterial']);
    Route::post('/teacher/lessons-material/get-lessons', [TeacherController::class, 'getLessons']);
    Route::any('/teacher/lessons-material/delete/{id}', [TeacherController::class, 'delFocusarea']);
    Route::get('/teacher/lessons-material/edit/{id}', [TeacherController::class, 'editFocusarea']);
    Route::post('/teacher/lessons-material/update', [TeacherController::class, 'updateFocusarea']);

    Route::get('/fullcalendareventmaster', [StudentController::class, 'calendarIndex']);
    Route::post('/eventdelete', [StudentController::class, 'eventDelete']);
    Route::post('/fullcalendareventmaster/create', [StudentController::class, 'create']);
    Route::post('/fullcalendareventmaster/update', [StudentController::class, 'update']);
    Route::post('/fullcalendareventmaster/delete', [StudentController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/editaprofile/{id}', [AdminController::class, 'editProfile']);
    Route::post('/updateaprofile', [AdminController::class, 'updateProfile']);
    Route::get('/admin/teachers', [AdminController::class, 'getTeachers'])->name('admin.teachers');
    Route::any('/admin/teachers/add', [AdminController::class, 'addTeachers']);
    Route::any('/admin/teachers/delete/{id}', [AdminController::class, 'delTeachers']);
    Route::get('/admin/teachers/edit/{id}', [AdminController::class, 'editTeachers']);
    Route::post('/admin/teachers/update', [AdminController::class, 'updateTeachers']);
    Route::get('/admin/students', [AdminController::class, 'getStudents'])->name('admin.student');
    Route::any('/admin/students/add', [AdminController::class, 'addStudents']);
    Route::any('/admin/students/delete/{id}', [AdminController::class, 'delStudents']);
    Route::get('/admin/students/edit/{id}', [AdminController::class, 'editStudents']);
    Route::post('/admin/students/update', [AdminController::class, 'updateStudents']);
    Route::get('/admin/companies', [AdminController::class, 'getCompany'])->name('admin.companies');
    Route::any('/admin/companies/add', [AdminController::class, 'addCompany']);
    Route::any('/admin/companies/delete/{id}', [AdminController::class, 'delCompany']);
    Route::get('/admin/companies/edit/{id}', [AdminController::class, 'editCompany']);
    Route::post('/admin/companies/update', [AdminController::class, 'updateCompany']);
    Route::get('/admin/departments', [AdminController::class, 'getDepartment'])->name('admin.departments');
    Route::any('/admin/departments/add', [AdminController::class, 'addDepartment']);
    Route::any('/admin/departments/delete/{id}', [AdminController::class, 'delDepartment']);
    Route::get('/admin/departments/edit/{id}', [AdminController::class, 'editDepartment']);
    Route::post('/admin/departments/update', [AdminController::class, 'updateDepartment']);
    Route::get('/admin/users', [AdminController::class, 'getUser'])->name('admin.users');
    Route::any('/admin/users/add', [AdminController::class, 'addUser']);
    Route::any('/admin/users/delete/{id}', [AdminController::class, 'delUser']);
    Route::get('/admin/users/edit/{id}', [AdminController::class, 'editUser']);
    Route::post('/admin/users/update', [AdminController::class, 'updateUser']);
    Route::get('/admin/focusarea', [AdminController::class, 'getFocusarea'])->name('admin.focusareas');
    Route::any('/admin/focusarea/add', [AdminController::class, 'addFocusarea']);
    Route::any('/admin/focusarea/delete/{id}', [AdminController::class, 'delFocusarea']);
    Route::get('/admin/focusarea/edit/{id}', [AdminController::class, 'editFocusarea']);
    Route::post('/admin/focusarea/update', [AdminController::class, 'updateFocusarea']);
    Route::get('/admin/lessonsubject', [AdminController::class, 'getLessonsubject'])->name('admin.lessonsubjects');
    Route::any('/admin/lessonsubject/add', [AdminController::class, 'addLessonsubject']);
    Route::any('/admin/lessonsubject/delete/{id}', [AdminController::class, 'delLessonsubject']);
    Route::get('/admin/lessonsubject/edit/{id}', [AdminController::class, 'editLessonsubject']);
    Route::post('/admin/lessonsubject/update', [AdminController::class, 'updateLessonsubject']);
    Route::get('/admin/homework', [AdminController::class, 'getLessonsubject'])->name('admin.homeworks');
    Route::any('/admin/homework/add', [HomeworkController::class, 'addHomework'])->name('admin.addhomeworks');
    Route::any('/admin/homework/delete/{id}', [HomeworkController::class, 'delHomework']);
    Route::get('/admin/homework/edit/{id}', [HomeworkController::class, 'editHomework']);
    Route::post('/admin/homework/update', [HomeworkController::class, 'updateHomework']);
});

Route::get('logout', [LoginController::class,'logout']);

// Route::post('logout', [LoginController::class,'logout']);
// Route::post('admin-logout', [LoginController::class,'adminLogout'])->name('admin.logout');
// Route::post('teacher-logout', [LoginController::class,'teacherLogout'])->name('teacher.logout');