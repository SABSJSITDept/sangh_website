<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shree_sangh\ThoughtApiController;
use App\Http\Controllers\Shree_sangh\ViharController;
use App\Http\Controllers\Shree_sangh\Karyakarini\ExPresidentController;
use App\Http\Controllers\Shree_sangh\Karyakarini\PstController;
use App\Http\Controllers\Shree_sangh\Karyakarini\VpSecController;
use App\Http\Controllers\Shree_sangh\Karyakarini\ItCellController;
use App\Http\Controllers\Pravarti\PravartiShreeSanghController;
use App\Http\Controllers\Shree_sangh\Karyakarini\PravartiSanyojakController;
use App\Http\Controllers\Aanchal\AanchalController;
use App\Http\Controllers\Shree_sangh\Karyakarini\KaryasamitiSadasyaController;
use App\Http\Controllers\Shree_sangh\Karyakarini\SthayiSampatiSanwardhanSamitiController;
use App\Http\Controllers\Shree_sangh\Karyakarini\SanyojanMandalAntrastriyaSadasyataController;
use App\Http\Controllers\Shree_sangh\Karyakarini\SamtaJanKalyanPranayashController;
use App\Http\Controllers\Shree_sangh\Karyakarini\PadhadhikariPrashashanKaryashalaController;
use App\Http\Controllers\Shree_sangh\News\NewsUpdateController;
use App\Http\Controllers\Shree_sangh\Shivir\ShivirUpdateController;
use App\Http\Controllers\Shree_sangh\AavedanPatra\AavedanPatraController;
use App\Http\Controllers\ShreeSangh\SanghPravartiya\DharmikPravartiyaController;
use App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp\SpBasicController;
use App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp\JspExamController;
use App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp\JspBigexamController;
use App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp\JspHindiBooksController;
use App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp\JspGujratiBooksController;
use App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp\JspOldPapersController;
use App\Http\Controllers\SanghSahitya\Shramnopasak\AddShramnopasakController;
use App\Http\Controllers\SanghSahitya\sahitya\AddSahityaController;
use App\Http\Controllers\SanghSahitya\ChaturmasSuchi\AddChaturmasSuchiController;
use App\Http\Controllers\PhotoGallery\AddPhotoController;


// ------------------Thoughts api ------------------------------------
Route::apiResource('thoughts', ThoughtApiController::class);
Route::get('/latest-thought', [ThoughtApiController::class, 'latestThought']);
// ------------------Thoughts api ------------------------------------

// ------------------Vihart api ------------------------------------
Route::get('/vihar/latest', [ViharController::class, 'latest']);
Route::prefix('vihar')->group(function () {
    Route::get('/', [ViharController::class, 'index']);
    Route::post('/', [ViharController::class, 'store']);
    Route::put('/{id}', [ViharController::class, 'update']);
    Route::delete('/{id}', [ViharController::class, 'destroy']);
    });
// ------------------Vihart api ------------------------------------

// ------------------Ex-President api ------------------------------------
Route::prefix('ex-president')->group(function () {
    Route::get('/', [ExPresidentController::class, 'all']);
    Route::post('/', [ExPresidentController::class, 'store']);
    Route::post('/{id}', [ExPresidentController::class, 'update']); // file upload requires POST
    Route::delete('/{id}', [ExPresidentController::class, 'destroy']);
}); 
// ------------------Ex-President api ------------------------------------


// ------------------PST api ------------------------------------
Route::prefix('pst')->group(function () {
    Route::get('/', [PstController::class, 'index']);
    Route::post('/', [PstController::class, 'store']);
    Route::get('/{id}', [PstController::class, 'show']);
    Route::put('/{id}', [PstController::class, 'update']);
    Route::delete('/{id}', [PstController::class, 'destroy']);
});
// ------------------PST api ------------------------------------


// ------------------vp/sec api ------------------------------------
Route::prefix('vp-sec')->group(function () {
    Route::get('/', [VpSecController::class, 'index']);
    Route::post('/', [VpSecController::class, 'store']);
 Route::put('/{id}', [VpSecController::class, 'update']); // ✅ REQUIRED
    Route::get('/{id}', [VpSecController::class, 'show']);
    Route::delete('/{id}', [VpSecController::class, 'destroy']);
});
// ------------------vp/sec api ------------------------------------


// ------------------it cell api ------------------------------------
Route::prefix('it-cell')->group(function () {
    Route::get('/', [ItCellController::class, 'index']);
    Route::post('/', [ItCellController::class, 'store']);
    Route::post('/{id}', [ItCellController::class, 'update']); // for FormData, use POST
    Route::delete('/{id}', [ItCellController::class, 'destroy']);
});
// ------------------it cell api ------------------------------------


// -----------------pravarti api ------------------------------------
Route::prefix('pravarti')->group(function () {
    Route::get('/', [PravartiShreeSanghController::class, 'index']);
    Route::post('/', [PravartiShreeSanghController::class, 'store']);
    Route::put('/{id}', [PravartiShreeSanghController::class, 'update']);
    Route::delete('/{id}', [PravartiShreeSanghController::class, 'destroy']);
});
// -----------------pravarti api ------------------------------------


// -----------------pravarti_sanyojak api ------------------------------------
Route::prefix('pravarti-sanyojak')->group(function () {
    Route::get('/', [PravartiSanyojakController::class, 'index']);
    Route::post('/', [PravartiSanyojakController::class, 'store']);
    Route::put('/{id}', [PravartiSanyojakController::class, 'update']); // ✅ Change this line
    Route::delete('/{id}', [PravartiSanyojakController::class, 'destroy']);
});
// -----------------pravarti_sanyojak api ------------------------------------


// -----------------aanchal api ------------------------------------
Route::prefix('aanchal')->group(function () {
    Route::get('/', [AanchalController::class, 'index']);
    Route::post('/', [AanchalController::class, 'store']);  // <- This one is required
    Route::get('{id}', [AanchalController::class, 'show']);
    Route::put('{id}', [AanchalController::class, 'update']);
    Route::delete('{id}', [AanchalController::class, 'destroy']);
});
// -----------------aanchal api ------------------------------------


// -----------------karyasamiti_sadasya api ------------------------------------
Route::apiResource('karyasamiti_sadasya', KaryasamitiSadasyaController::class);
// -----------------karyasamiti_sadasya api ------------------------------------



// -----------------Sthayi Sampati Sanwardhan Samiti api ------------------------------------
Route::apiResource('sthayi_sampati_sanwardhan_samiti', SthayiSampatiSanwardhanSamitiController::class);
// -----------------Sthayi Sampati Sanwardhan Samiti api ------------------------------------




// -----------------Sanyojan Mandal Antrastriya Sadasyata api -----------------------------------

Route::apiResource('sanyojan-mandal-antrastriya-sadasyata', SanyojanMandalAntrastriyaSadasyataController::class)
    ->parameters(['sanyojan-mandal-antrastriya-sadasyata' => 'sadasya']);

// -----------------Sanyojan Mandal Antrastriya Sadasyata api ------------------------------------


// -----------------Samta Jan Kalyan Pranayash api ------------------------------------
Route::apiResource('/samta-jan-kalyan-pranayash', SamtaJanKalyanPranayashController::class);
// -----------------Samta Jan Kalyan Pranayash api ------------------------------------


// -----------------Padhadhikari Prashashan Karyashala api ------------------------------------

Route::apiResource('padhadhikari-prashashan-karyashala', PadhadhikariPrashashanKaryashalaController::class)
     ->parameters([
         'padhadhikari-prashashan-karyashala' => 'record' // 👈 shorten the route parameter
     ]);
// -----------------Padhadhikari Prashashan Karyashala api ------------------------------------


// -----------------news api ------------------------------------
Route::apiResource('news', NewsUpdateController::class);
// -----------------news api ------------------------------------


// -----------------shivir api ------------------------------------
Route::apiResource('shivir', ShivirUpdateController::class);
// -----------------shivir api ------------------------------------

// -----------------Aavedan Patra api ------------------------------------
Route::apiResource('aavedan-patra', AavedanPatraController::class)->except(['show']);
Route::get('/aavedan-patra/{category}', [AavedanPatraController::class, 'getByCategory']);
Route::get('/aavedan-patra-online', [AavedanPatraController::class, 'onlyOnline']);
Route::get('/aavedan-patra-offline', [AavedanPatraController::class, 'onlyOffline']);
// -----------------Aavedan Patra api ------------------------------------

// -----------------Dharmik Pravartiya api ------------------------------------
Route::apiResource('dharmik-pravartiya', DharmikPravartiyaController::class);
// -----------------Dharmik Pravartiya api ------------------------------------

// -----------------JSP api ------------------------------------
Route::apiResource('jsp-basic', SpBasicController::class);
// -----------------JSP api ------------------------------------

// -----------------JSP Exam api ------------------------------------
Route::apiResource('jsp-exam', JspExamController::class);
// -----------------JSP Exam api ------------------------------------

// -----------------JSP Big Exam api ------------------------------------
Route::apiResource('jsp-bigexam', JspBigexamController::class);
// -----------------JSP Big Exam api ------------------------------------

// -----------------JSP Hindi Books api ------------------------------------
Route::apiResource('jsp-hindi-books', JspHindiBooksController::class);
// -----------------JSP Hindi Books api ------------------------------------

// -----------------JSP Gujarati Books api ------------------------------------
Route::apiResource('/jsp-gujrati-books', JspGujratiBooksController::class);
// -----------------JSP Gujarati Books api ------------------------------------

// -----------------JSP Old Papers api ------------------------------------]
Route::apiResource('jsp-old-papers', JspOldPapersController::class);
// -----------------JSP Old Papers api ------------------------------------

// -----------------LATEST Shramnopasak api ------------------------------------
Route::get('/shramnopasak/latest', [AddShramnopasakController::class, 'latest']);
// -----------------LATEST Shramnopasak api ------------------------------------

// -----------------Shramnopasak api ------------------------------------
Route::apiResource('shramnopasak', AddShramnopasakController::class);
// -----------------Shramnopasak api ------------------------------------


// -----------------Sahitya api ------------------------------------
Route::apiResource('sahitya', AddSahityaController::class)->except(['show']);
Route::get('/sahitya/category/{category}', [AddSahityaController::class, 'getByCategory']);
Route::get('/sahitya/homepage-books', [AddSahityaController::class, 'getHomepageBooks']);
Route::post('/sahitya/set-homepage/{id}', [AddSahityaController::class, 'setHomepageBook']);

// -----------------Sahitya api ------------------------------------


// -----------------Chaturmas Suchi api ------------------------------------
Route::apiResource('chaturmas-suchi', AddChaturmasSuchiController::class);
// -----------------Chaturmas Suchi api ------------------------------------


// -----------------Photo Gallery api ------------------------------------
Route::prefix('photo-gallery')->group(function () {
    Route::post('/store', [AddPhotoController::class, 'store']);
    Route::get('/fetch/{category}', [AddPhotoController::class, 'fetchByCategoryEvent']);
    Route::delete('/delete-single/{id}', [AddPhotoController::class, 'deleteSinglePhoto']);
    Route::post('/update/{id}', [AddPhotoController::class, 'updatePhoto']); 
    Route::post('/update-event/{eventName}', [AddPhotoController::class, 'updateEventName']);
    Route::delete('/delete-event/{category}/{eventName}', [AddPhotoController::class, 'deleteEvent']);
});
// -----------------Photo Gallery api ------------------------------------


// -----------------Home Slider api ------------------------------------
Route::apiResource('home_slider', App\Http\Controllers\HomeSlider\AddHomeSlider::class);
// -----------------Home Slider api ------------------------------------
