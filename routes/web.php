<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Shree_sangh\ThoughtApiController;
use App\Http\Controllers\Shree_sangh\Karyakarini\ExPresidentController;


Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Invalid credentials');
    }

    session(['user' => $user]);

    switch ($user->role) {
        case 'super_admin':
            return redirect()->route('dashboard.super_admin');
        case 'sahitya':
            return redirect()->route('dashboard.sahitya');
        case 'shree_sangh':
            return redirect()->route('dashboard.shree_sangh');
        case 'yuva_sangh':
            return redirect()->route('dashboard.yuva_sangh');
        case 'mahila_samiti':
            return redirect()->route('dashboard.mahila_samiti');
        default:
            return back()->with('error', 'Unknown role');
    }
});

Route::get('/logout', function () {
    session()->forget('user');
    return redirect('/');
})->name('logout');


Route::middleware(['web', 'checkSession', 'matchRole:super_admin'])->get('/dashboard/super_admin', function () {
    return view('dashboards.super_admin.index');
})->name('dashboard.super_admin');

Route::middleware(['web', 'checkSession', 'matchRole:sahitya'])->get('/dashboard/sahitya', function () {
    return view('dashboards.sahitya.index');
})->name('dashboard.sahitya');

Route::middleware(['web', 'checkSession', 'matchRole:shree_sangh'])->group(function () {

    // Shree Sangh Dashboard
    Route::get('/dashboard/shree_sangh', function () {
        return view('dashboards.shree_sangh.index');
    })->name('dashboard.shree_sangh');

    // Daily Thoughts Form Page
    Route::get('/dashboard/shree_sangh/daily-thoughts', [ThoughtApiController::class, 'create'])->name('daily-thoughts.create');

    

});
Route::middleware(['web', 'checkSession', 'matchRole:yuva_sangh'])->get('/dashboard/yuva_sangh', function () {
    return view('dashboards.yuva_sangh.index');
})->name('dashboard.yuva_sangh');

Route::middleware(['web', 'checkSession', 'matchRole:mahila_samiti'])->get('/dashboard/mahila_samiti', function () {
    return view('dashboards.mahila_samiti.index');
})->name('dashboard.mahila_samiti');

Route::get('/thoughts/list', [ThoughtApiController::class, 'index'])->name('thoughts.index');

Route::get('/dashboard/vihar-sewa', function () {
    return view('dashboards.shree_sangh.vihar_sewa');
})->name('vihar.sewa');

Route::get('/shree-sangh/ex-president', [ExPresidentController::class, 'index'])->name('ex_president.index');
Route::get('/shree-sangh/karyakarini', function () {
    return view('dashboards.shree_sangh.karyakarini.index');
})->name('karyakarini.index');

Route::get('/shree-sangh/karyakarini/pst', function () {
    return view('dashboards.shree_sangh.karyakarini.pst');
})->name('pst.view');

Route::get('/vp-sec', function () {
    return view('dashboards.shree_sangh.karyakarini.vp_sec');
})->name('vp_sec.manage');

Route::get('/admin/it-cell', function () {
    return view('dashboards.shree_sangh.karyakarini.it_cell');
})->name('admin.it_cell');

Route::get('/pravarti', function () {
    return view('pravarti.add_pravarti');
});

Route::get('/pravarti-sanyojak', function () {
    return view('dashboards.shree_sangh.karyakarini.pravarti_sanyojak');
});

Route::get('/aanchal', function () {
    return view('aanchal.add_aanchal');
});

Route::get('/karyasamiti-sadasya', function () {
    return view('dashboards.shree_sangh.karyakarini.karyasamiti_sadasya');
});

Route::get('/sthayi_sampati_sanwardhan_samiti', function () {
    return view('dashboards.shree_sangh.karyakarini.sthayi_sampati_sanwardhan_samiti');
});


Route::get('/sanyojan_mandal_antrastriya_sadasyata', function () {
    return view('dashboards.shree_sangh.karyakarini.sanyojan_mandal_antrastriya_sadasyata');
});


Route::get('/samta_jan_kalyan_pranayash', function () {
    return view('dashboards.shree_sangh.karyakarini.samta_jan_kalyan_pranayash');
});



Route::get('/padhadhikari_prashashan_karyashala', function () {
    return view('dashboards.shree_sangh.karyakarini.padhadhikari_prashashan_karyashala');
});