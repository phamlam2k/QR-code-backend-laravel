<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->prefix('qr-code')->group(function (){
    Route::resource('user', 'UserController')
        ->except('create', 'edit');
    Route::resource('student', 'StudentController');
    Route::resource('teacher', 'TeacherController');
    Route::resource('subject', 'SubjectController');
    Route::resource('table-register', 'TableRegisterController');
    Route::resource('attendance', 'AttendanceController');
    Route::resource('post', 'PostsController');
    Route::resource('comment', 'CommentController');
    Route::resource("list", "ListController");
});
