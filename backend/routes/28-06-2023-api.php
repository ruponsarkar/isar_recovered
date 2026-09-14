<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\journalController;
use App\Http\Controllers\AuthController;

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



Route::post('login', [AuthController::class, 'login']);
Route::group(['middleware'=>'api'], function(){
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('postBlog', [BlogController::class, 'postBlog']);

    
});




Route::post('postManuscript', [journalController::class, 'postManuscript']);
Route::post('postReviewerData', [journalController::class, 'postReviewerData']);
Route::post('postEditorData', [journalController::class, 'postEditorData']);
Route::post('postAuthorData', [journalController::class, 'postAuthorData']);

Route::get('getJournals', [journalController::class, 'getJournals']);

Route::get('journalDetails/{journal}', [journalController::class, 'journalDetails']);
Route::get('journalDetailsForUpdate/{journal}', [journalController::class, 'journalDetailsForUpdate']);

Route::post('/postJournal', [journalController::class, 'postJournal']);
Route::post('/postEditor', [journalController::class, 'postEditor']);
Route::get('/getEditor/{id}', [journalController::class, 'getEditor']);
Route::get('/getVolume/{id}', [journalController::class, 'getVolume']);
Route::post('/addVolume', [journalController::class, 'addVolume']);

Route::get('/getIssue/{id}', [journalController::class, 'getIssue']);

Route::post('/saveIssue', [journalController::class, 'saveIssue']);

Route::get('/getArticle/{id}', [journalController::class, 'getArticle']);

Route::get('/getIndexingById/{id}', [journalController::class, 'getIndexingById']);
Route::post('/postIndexing', [journalController::class, 'postIndexing']);

Route::get('/getManuscript', [journalController::class, 'getManuscript']);
Route::get('/getReviewerRequest', [journalController::class, 'getReviewerRequest']);
Route::get('/getEditorRequest', [journalController::class, 'getEditorRequest']);
Route::get('/getAuthorRequest', [journalController::class, 'getAuthorRequest']);

Route::post('/postArticle', [journalController::class, 'postArticle']);

Route::get('/countSection', [journalController::class, 'countSection']);
Route::get('/getArticle/{id}', [journalController::class, 'getArticle']);

Route::get('/visitor', [journalController::class, 'visitor']);






