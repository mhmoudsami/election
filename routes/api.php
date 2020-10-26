<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\ProxyController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('api')->get('proxy/{nid}', [ProxyController::class , 'index']);

Route::middleware('api')->post('/login', [AuthController::class , 'login']);
Route::get('candidates/search', [CandidateController::class , 'search'])->middleware('auth:api');
Route::apiResource('candidates', CandidateController::class)->middleware('auth:api');
Route::get('supervisors/list', [SupervisorController::class , 'list'])->middleware('auth:api');
Route::apiResource('supervisors', SupervisorController::class)->middleware('auth:api');
