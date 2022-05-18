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
    //fullcalender
    Route::get('/fullcalendareventmaster', [StudentController::class, 'calendarIndex']);
    Route::post('/fullcalendareventmaster/create', [StudentController::class, 'create']);
    Route::post('/fullcalendareventmaster/update', [StudentController::class, 'update']);
    Route::post('/fullcalendareventmaster/delete', [StudentController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:teacher'], function () {
    Route::view('/teacher', 'teacher');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', [AdminController::class, 'index']);
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

    Route::get('/admin/users', [AdminController::class, 'getUser'])->name('admin.users');
    Route::any('/admin/users/add', [AdminController::class, 'addUser']);
    Route::any('/admin/users/delete/{id}', [AdminController::class, 'delUser']);
    Route::get('/admin/users/edit/{id}', [AdminController::class, 'editUser']);
    Route::post('/admin/users/update', [AdminController::class, 'updateUser']);
});

Route::get('logout', [LoginController::class,'logout']);

// Route::post('logout', [LoginController::class,'logout']);
// Route::post('admin-logout', [LoginController::class,'adminLogout'])->name('admin.logout');
// Route::post('teacher-logout', [LoginController::class,'teacherLogout'])->name('teacher.logout');