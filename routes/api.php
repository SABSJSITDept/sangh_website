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


// ------------------Login api ------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $role = $request->user()->role;

        switch ($role) {
            case 'super_admin':
                return response()->json(['message' => '🎯 Super Admin Dashboard']);
            case 'sahitya':
                return response()->json(['message' => '📚 Sahitya Dashboard']);
            case 'shree_sangh':
                return response()->json(['message' => '🏛️ Shree Sangh Dashboard']);
            case 'yuva_sangh':
                return response()->json(['message' => '🔥 Yuva Sangh Dashboard']);
            case 'mahila_samiti':
                return response()->json(['message' => '🌸 Mahila Samiti Dashboard']);
            default:
                return response()->json(['message' => '⛔ Unauthorized Role'], 403);
        }
    });

});
// ------------------Login api ------------------------------------

// ------------------Thoughts api ------------------------------------
Route::get('/latest-thought', [ThoughtApiController::class, 'latest']);
Route::prefix('thoughts')->group(function () {
    Route::get('/', [ThoughtApiController::class, 'index']);
    Route::post('/', [ThoughtApiController::class, 'store']);
    Route::get('/{id}', [ThoughtApiController::class, 'show']);
    Route::put('/{id}', [ThoughtApiController::class, 'update']);
    Route::delete('/{id}', [ThoughtApiController::class, 'destroy']);
});
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
    Route::post('/{id}', [PravartiSanyojakController::class, 'update']);
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
