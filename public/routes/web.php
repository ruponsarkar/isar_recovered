<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/AllJournals', [PublicController::class, 'journals'])->name('journals');
Route::get('/journal/{journal}', [PublicController::class, 'journal'])->name('journal');
Route::get('/Articles/{Articles}', [PublicController::class, 'articles'])->name('articles');
foreach (['open-access-policy','CopyrightPolicyPage','author-instruction','processingFees','reviewProcess','aboutUs','RefundPolicy','PublicationFee','ReviewGuidlines','EditorGuidlines','EthicsPolicies','Representatives','GenerativeAIPolicy'] as $page) Route::get('/'.$page, fn () => app(PublicController::class)->policy($page))->name('policy.'.$page);
Route::get('/manuscript', [ApplicationController::class, 'form'])->defaults('type','manuscript')->name('manuscript');
Route::post('/manuscript', [ApplicationController::class, 'submit'])->defaults('type','manuscript')->name('manuscript.submit');
Route::get('/joinAuthor', [ApplicationController::class, 'form'])->defaults('type','author')->name('join.author');
Route::post('/joinAuthor', [ApplicationController::class, 'submit'])->defaults('type','author')->name('join.author.submit');
Route::get('/JoinReviewer', [ApplicationController::class, 'form'])->defaults('type','reviewer')->name('join.reviewer');
Route::post('/JoinReviewer', [ApplicationController::class, 'submit'])->defaults('type','reviewer')->name('join.reviewer.submit');
Route::get('/JoinEditor', [ApplicationController::class, 'form'])->defaults('type','editor')->name('join.editor');
Route::post('/JoinEditor', [ApplicationController::class, 'submit'])->defaults('type','editor')->name('join.editor.submit');
Route::get('/joinReviewer', [ApplicationController::class, 'form'])->defaults('type','reviewer');
Route::post('/joinReviewer', [ApplicationController::class, 'submit'])->defaults('type','reviewer');
Route::get('/joinEditor', [ApplicationController::class, 'form'])->defaults('type','editor');
Route::post('/joinEditor', [ApplicationController::class, 'submit'])->defaults('type','editor');
Route::get('/action', [PublicController::class, 'contact'])->name('contact');
Route::get('/Payment', [PaymentController::class, 'index'])->name('payment');
Route::post('/PaymentDetails', [PaymentController::class, 'details'])->name('payment.details');
Route::get('/PaymentDetails', [PaymentController::class, 'index']);
Route::post('/pay/order', [PaymentController::class, 'order'])->name('pay.order');
Route::post('/pay', [PaymentController::class, 'success'])->name('pay');
