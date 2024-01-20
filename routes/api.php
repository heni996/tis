<?php

use App\Http\Controllers\BackOffice\GuestBookController;
use App\Http\Controllers\BackOffice\HotelController;
use App\Http\Controllers\BackOffice\QuestionController;
use App\Http\Controllers\BackOffice\ResponseController;
use App\Http\Controllers\BackOffice\TouristController;
use App\Http\Controllers\BackOffice\UserController;
use App\Http\Controllers\FrontOffice\GuestBookController as FrontOfficeGuestBookController;
use App\Http\Controllers\FrontOffice\HotelController as FrontOfficeHotelController;
use App\Http\Controllers\FrontOffice\QuestionController as FrontOfficeQuestionController;
use App\Http\Controllers\FrontOffice\ResponseController as FrontOfficeResponseController;
use App\Http\Controllers\FrontOffice\TouristController as FrontOfficeTouristController;
use App\Http\Controllers\FrontOffice\UserController as FrontOfficeUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::group(['namespace' => 'BackOffice', 'prefix' => 'backoffice'], function ($router) {
        Route::get('/guestbooks', [GuestBookController::class, 'index']);
        Route::get('/guestbooks/{guestbook}', [GuestBookController::class, 'show']);
        Route::post('/guestbooks', [GuestBookController::class, 'store']);
        Route::put('/guestbooks/{guestbook}', [GuestBookController::class, 'update']);
        Route::delete('/guestbooks/{guestbook}', [GuestBookController::class, 'destroy']);
        Route::get('/hotels', [HotelController::class, 'index']);
        Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
        Route::post('/hotels', [HotelController::class, 'store']);
        Route::put('/hotels/{hotel}', [HotelController::class, 'update']);
        Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy']);
        Route::get('/questions', [QuestionController::class, 'index']);
        Route::get('/questions/{question}', [QuestionController::class, 'show']);
        Route::post('/questions', [QuestionController::class, 'store']);
        Route::put('/questions/{question}', [QuestionController::class, 'update']);
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);
        Route::get('/responses', [ResponseController::class, 'index']);
        Route::get('/responses/{response}', [ResponseController::class, 'show']);
        Route::post('/responses', [ResponseController::class, 'store']);
        Route::put('/responses/{response}', [ResponseController::class, 'update']);
        Route::delete('/responses/{response}', [ResponseController::class, 'destroy']);
        Route::get('/tourists', [TouristController::class, 'index']);
        Route::get('/tourists/{tourist}', [TouristController::class, 'show']);
        Route::post('/tourists', [TouristController::class, 'store']);
        Route::put('/tourists/{tourist}', [TouristController::class, 'update']);
        Route::delete('/tourists/{tourist}', [TouristController::class, 'destroy']);
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
    Route::group(['namespace' => 'FrontOffice', 'prefix' => 'frontoffice'], function ($router) {
        Route::get('/guestbooks', [FrontOfficeGuestBookController::class, 'index']);
        Route::get('/guestbooks/{guestbook}', [FrontOfficeGuestBookController::class, 'show']);
        Route::post('/guestbooks', [FrontOfficeGuestBookController::class, 'store']);
        Route::put('/guestbooks/{guestbook}', [FrontOfficeGuestBookController::class, 'update']);
        Route::delete('/guestbooks/{guestbook}', [FrontOfficeGuestBookController::class, 'destroy']);
        Route::get('/hotels', [FrontOfficeHotelController::class, 'index']);
        Route::get('/hotels/{hotel}', [FrontOfficeHotelController::class, 'show']);
        Route::post('/hotels', [FrontOfficeHotelController::class, 'store']);
        Route::put('/hotels/{hotel}', [FrontOfficeHotelController::class, 'update']);
        Route::delete('/hotels/{hotel}', [FrontOfficeHotelController::class, 'destroy']);
        Route::get('/questions', [FrontOfficeQuestionController::class, 'index']);
        Route::get('/questions/{question}', [FrontOfficeQuestionController::class, 'show']);
        Route::post('/questions', [FrontOfficeQuestionController::class, 'store']);
        Route::put('/questions/{question}', [FrontOfficeQuestionController::class, 'update']);
        Route::delete('/questions/{question}', [FrontOfficeQuestionController::class, 'destroy']);
        Route::get('/responses', [FrontOfficeResponseController::class, 'index']);
        Route::get('/responses/{response}', [FrontOfficeResponseController::class, 'show']);
        Route::post('/responses', [FrontOfficeResponseController::class, 'store']);
        Route::put('/responses/{response}', [FrontOfficeResponseController::class, 'update']);
        Route::delete('/responses/{response}', [FrontOfficeResponseController::class, 'destroy']);
        Route::get('/tourists', [FrontOfficeTouristController::class, 'index']);
        Route::get('/tourists/{tourist}', [FrontOfficeTouristController::class, 'show']);
        Route::post('/tourists', [FrontOfficeTouristController::class, 'store']);
        Route::put('/tourists/{tourist}', [FrontOfficeTouristController::class, 'update']);
        Route::delete('/tourists/{tourist}', [FrontOfficeTouristController::class, 'destroy']);
        Route::get('/users', [FrontOfficeUserController::class, 'index']);
        Route::get('/users/{user}', [FrontOfficeUserController::class, 'show']);
        Route::post('/users', [FrontOfficeUserController::class, 'store']);
        Route::put('/users/{user}', [FrontOfficeUserController::class, 'update']);
        Route::delete('/users/{user}', [FrontOfficeUserController::class, 'destroy']);
    });
});

