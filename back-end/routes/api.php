<?php

use App\Http\Controllers\TakerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepressionTypeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\SuperUserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/send-verify-mail/takers/{email}', [TakerController::class, 'sendVerifyEmail']);
Route::middleware('auth:sanctum')->get('/send-verify-mail/admins/{email}', [AdminController::class, 'sendVerifyEmail']);
Route::middleware('auth:sanctum')->post('/send-result-email', [TakerController::class, 'sendResultEmail']);

//Takers
Route::get('takers', [TakerController::class, 'index']);
Route::post('takers', [TakerController::class, 'create']); // create takers
Route::get('takers/{id}', [TakerController::class, 'read']);
Route::put('takers/{id}/edit', [TakerController::class, 'update']);
Route::delete('takers/{id}/delete', [TakerController::class, 'delete']);

//Takers Login
Route::post('login-takers', [TakerController::class, 'login']);
Route::middleware('auth:sanctum')->get('taker', [TakerController::class, 'getTaker']);
Route::middleware('auth:sanctum')->post('logout-takers', [TakerController::class, 'logout']);

Route::post('login-admins', [AdminController::class, 'login']);
Route::middleware('auth:sanctum')->get('admin', [AdminController::class, 'getAdmin']);
Route::middleware('auth:sanctum')->post('logout-admins', [AdminController::class, 'logout']);

//Forgot password
Route::post('forgot-password', [TakerController::class, 'forgotPassword']);

//Admins
Route::get('admins', [AdminController::class, 'index']);
Route::post('admins', [AdminController::class, 'create']); 
Route::get('admins/{id}', [AdminController::class, 'read']);
Route::put('admins/{id}/edit', [AdminController::class, 'update']);
Route::delete('admins/{id}/delete', [AdminController::class, 'delete']);
Route::get('admins-questions', [AdminController::class, 'read_admin_questions']); // display questions made by admins
Route::get('admins-options', [AdminController::class, 'read_admin_options']); // display options made by admins

//SuperUser
Route::get('super-user', [SuperUserController::class, 'index']);

//Questions
Route::get('questions', [QuestionController::class, 'index']); 
Route::post('questions/{id}', [QuestionController::class, 'create']); 
Route::get('questions/{id}', [QuestionController::class, 'read']);
Route::put('questions/{q_id}/{a_id}/edit', [QuestionController::class, 'update']);
Route::delete('questions/{q_id}/{a_id}/delete', [QuestionController::class, 'delete']);
Route::get('questions-options', [QuestionController::class, 'read_question_options']); // display questions with options and scores

//Options
Route::get('options', [OptionController::class, 'index']); 
Route::post('options/{id}', [OptionController::class, 'create']); 
Route::get('options/{id}', [OptionController::class, 'read']);
Route::put('options/{o_id}/{a_id}/edit', [OptionController::class, 'update']);
Route::delete('options/{o_id}/{a_id}/delete', [OptionController::class, 'delete']);

//DepressionTypes
Route::get('depression-types', [DepressionTypeController::class, 'index']); 
Route::post('depression-types/{id}', [DepressionTypeController::class, 'create']); 
Route::get('depression-types/{id}', [DepressionTypeController::class, 'read']);
Route::put('depression-types/{d_id}/{a_id}/edit', [DepressionTypeController::class, 'update']);
Route::delete('depression-types/{d_id}/{a_id}/delete', [DepressionTypeController::class, 'delete']);

//Diagnosis
Route::get('diagnoses', [DiagnosisController::class, 'index']);
Route::post('diagnoses/{id}', [DiagnosisController::class, 'create']); // ara shean kani ang link para maka create
Route::get('recent-diagnosis', [DiagnosisController::class, 'read_recent']); // for email and result
Route::get('diagnoses/{id}', [DiagnosisController::class, 'read']); // for specific diagnosis
Route::get('taker-diagnoses/{id}', [DiagnosisController::class, 'read_taker_diagnoses']); // for specific takers
