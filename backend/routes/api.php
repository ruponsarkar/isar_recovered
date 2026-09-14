<?php

use App\Http\Controllers\articleCntroller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\journalController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::post('postManuscript', [journalController::class, 'postManuscript']);
Route::post('postReviewerData', [journalController::class, 'postReviewerData']);
Route::post('postEditorData', [journalController::class, 'postEditorData']);
Route::post('postAuthorData', [journalController::class, 'postAuthorData']);
Route::get('getJournals', [journalController::class, 'getJournals']);
Route::get('journalDetails/{journal}', [journalController::class, 'journalDetails']);
Route::get('journalDetailsForUpdate/{journal}', [journalController::class, 'journalDetailsForUpdate']);
Route::post('postJournal', [journalController::class, 'postJournal']);
Route::post('postEditor', [journalController::class, 'postEditor']);
Route::get('getEditor/{id}', [journalController::class, 'getEditor']);
Route::get('getVolume/{id}', [journalController::class, 'getVolume']);
Route::post('addVolume', [journalController::class, 'addVolume']);
Route::get('getIssue/{id}', [journalController::class, 'getIssue']);
Route::post('saveIssue', [journalController::class, 'saveIssue']);
Route::post('updateIssue', [journalController::class, 'updateIssue']);
Route::get('getArticle/{id}', [journalController::class, 'getArticle']);
Route::post('postArticle', [journalController::class, 'postArticle']);
Route::post('deleteArticle', [journalController::class, 'deleteArticle']);
Route::get('getIndexingById/{id}', [journalController::class, 'getIndexingById']);
Route::get('getCeertificateById/{id}', [journalController::class, 'getCeertificateById']);
Route::post('postIndexing', [journalController::class, 'postIndexing']);
Route::post('postCertificate', [journalController::class, 'postCertificate']);
Route::get('getManuscript', [journalController::class, 'getManuscript']);
Route::get('getReviewerRequest', [journalController::class, 'getReviewerRequest']);
Route::get('getEditorRequest', [journalController::class, 'getEditorRequest']);
Route::get('getAuthorRequest', [journalController::class, 'getAuthorRequest']);
Route::get('countSection', [journalController::class, 'countSection']);
Route::get('visitor', [journalController::class, 'visitor']);
Route::post('updateArticle', [journalController::class, 'updateArticle']);
Route::post('saveImpactFactor', [journalController::class, 'saveImpactFactor']);
Route::get('getImpactFactor/{id}', [journalController::class, 'getImpactFactor']);
Route::post('deleteImpactFactor', [journalController::class, 'deleteImpactFactor']);
Route::get('downloadArticle/{slug}', [articleCntroller::class, 'download']);
Route::post('create-order', [PaymentController::class, 'createOrder']);
Route::post('payment-success', [PaymentController::class, 'paymentSuccess']);
