<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReadArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages']);
Route::get('/sitemap-{type}-{page}.xml', [SitemapController::class, 'records'])->where('type', 'journals|issues|articles|journal-pages')->whereNumber('page');
Route::get('/robots.txt', [SitemapController::class, 'robots']);

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/AllJournals', [PublicController::class, 'journals'])->name('journals');
Route::get('/journal/{journal}', [PublicController::class, 'journal'])->name('journal');
Route::get('/journal/{journal}/pages/{page}', [PublicController::class, 'journalPage'])->name('journal.page');
Route::get('/Articles/{Articles}', [PublicController::class, 'articles'])->name('articles');
Route::get('/viewArticle/{slug}', [ReadArticleController::class, 'show'])->name('article.show');
Route::get('/viewArticle/{slug}/fulltext.pdf', [ReadArticleController::class, 'fulltext'])->name('article.fulltext');
Route::get('/download/{id}', [ReadArticleController::class, 'download'])->whereNumber('id')->name('article.download');
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
Route::get('/PaymentDetails', [PaymentController::class, 'showDetails'])->name('payment.details.show');
Route::post('/pay/razorpay/order', [PaymentController::class, 'razorpayOrder'])->name('pay.razorpay.order');
Route::post('/pay/razorpay/success', [PaymentController::class, 'razorpaySuccess'])->name('pay.razorpay.success');
Route::post('/pay/razorpay/abandon', [PaymentController::class, 'razorpayAbandon'])->name('pay.razorpay.abandon');
Route::post('/pay/paypal/order', [PaymentController::class, 'paypalOrder'])->name('pay.paypal.order');
Route::post('/pay/paypal/capture', [PaymentController::class, 'paypalCapture'])->name('pay.paypal.capture');
Route::get('/pay/result/{reference}', [PaymentController::class, 'result'])->name('payment.result');
Route::post('/pay/order', [PaymentController::class, 'razorpayOrder'])->name('pay.order');
Route::post('/pay', [PaymentController::class, 'razorpaySuccess'])->name('pay');
