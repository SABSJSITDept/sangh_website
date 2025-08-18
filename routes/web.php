<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Shree_sangh\ThoughtApiController;
use App\Http\Controllers\Shree_sangh\Karyakarini\ExPresidentController;
use App\Models\Aanchal\Aanchal;
use App\Http\Controllers\API\AuthController;


 Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');
    Route::view('/change-password', 'change-password')->name('password.change');




// Login Page (Accessible to All)
Route::get('/', function () {
    return view('login');
})->name('login');

// Login Submit
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
        case 'sahitya_publication':
            return redirect()->route('dashboard.sahitya_publication');
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

// Logout
Route::get('/logout', function () {
    session()->forget('user');
    return redirect('/');
})->name('logout');


// ✅ All routes below require user to be logged in
Route::middleware(['web', 'checkSession'])->group(function () {

    // Super Admin Dashboard
    Route::middleware('matchRole:super_admin')->get('/dashboard/super_admin', function () {
        return view('dashboards.super_admin.index');
    })->name('dashboard.super_admin');

    // Sahitya Dashboard
    Route::middleware('matchRole:sahitya')->get('/dashboard/sahitya', function () {
        return view('dashboards.sahitya.index');
    })->name('dashboard.sahitya');

        // Sahitya publication Dashboard
    Route::middleware('matchRole:sahitya_publication')->get('/dashboard/sahitya_publication', function () {
        return view('dashboards.sahitya_publication.index');
    })->name('dashboard.sahitya_publication');

    // Yuva Sangh Dashboard
    Route::middleware('matchRole:yuva_sangh')->get('/dashboard/yuva_sangh', function () {
        return view('dashboards.yuva_sangh.index');
    })->name('dashboard.yuva_sangh');

    // Mahila Samiti Dashboard
    Route::middleware('matchRole:mahila_samiti')->get('/dashboard/mahila_samiti', function () {
        return view('dashboards.mahila_samiti.index');
    })->name('dashboard.mahila_samiti');

    // Shree Sangh Role Routes
    Route::middleware('matchRole:shree_sangh')->group(function () {
        Route::get('/dashboard/shree_sangh', function () {
            return view('dashboards.shree_sangh.index');
        })->name('dashboard.shree_sangh');

    });

    // Shared routes for any authenticated user

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


    Route::get('/aanchals', function () {
    return Aanchal::select('name')->orderBy('name')->get();
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

    Route::get('/news', function () {
        return view('dashboards.shree_sangh.news.news_update');
    });

    Route::get('/shivir', function () {
        return view('dashboards.shree_sangh.news.shivir_update');
    });

      Route::get('/aavedan_patra', function () {
        return view('dashboards.shree_sangh.aavedan_patra.aavedan_patra');
    });

    Route::get('/dharmik-pravartiya', function () {
    return view('dashboards.shree_sangh.sangh_pravartiya.dharmik_pravartiya');
})->name('dharmik_pravartiya');

 Route::get('/add-user-dashboard', function () {
    return view('dashboards.shree_sangh.jsp.jsp_index');
})->name('jsp.dashboard');

Route::get('/shree-sangh/sangh-pravartiya/jsp-basic', function () {
    return view('dashboards.shree_sangh.jsp.jsp_basic');
})->name('jsp.basic');


Route::get('/jsp-exam', function () {
    return view('dashboards.shree_sangh.jsp.jsp_exam');
})->name('jsp_exam.view');



Route::get('/jsp-bigexam', function () {
    return view('dashboards.shree_sangh.jsp.jsp_bigexam');
})->name('jsp-bigexam');


Route::get('/dashboard/jsp-hindi-books', function () {
    return view('dashboards.shree_sangh.jsp.jsp_hindi_books');
})->name('jsp-hindi-books.view');

Route::get('/jsp-gujrati-books', function () {
    return view('dashboards.shree_sangh.jsp.jsp_gujrati_books');
})->name('jsp-gujrati-books.view');

Route::get('/jsp-old-papers', function () {
    return view('dashboards.shree_sangh.jsp.jsp_old_papers');
})->name('jsp-old-papers.view');

Route::get('/shramnopasak', function () {
    return view('dashboards.sahitya.shramnopasak');
})->name('shramnopasak.view');

Route::get('/shramnopasak/all-view', function () {
    return view('dashboards.sahitya.shramnopasak_all');
});


Route::get('/dashboard/sahitya-publication', function () {
    return view('dashboards.sahitya_publication.sahitya_publication');
})->name('sahitya.publication');

Route::get('/daily-thoughts', function () {
    return view('dashboards.shree_sangh.daily_thoughts');
})->name('daily.thoughts');


Route::get('/chaturmas-suchi', function () {
    return view('dashboards.sahitya.chaturmas_suchi');
})->name('chaturmas_suchi.view');

Route::get('/pakhi', function () {
    return view('dashboards.sahitya.pakhi');
})->name('pakhi.view');

Route::get('/photo_gallery', function () {
    return view('dashboards.photo_gallery.add_photo');
})->name('photio_gallery.view');


Route::get('/sangh_photo_gallery', function () {
    return view('dashboards.photo_gallery.sangh');
})->name('sangh_photo_gallery.view');


Route::get('/home_slider', function () {
    return view('dashboards.home_slider.add_home_slider');
})->name('add_home_slider.view');


Route::get('/yuva_photo_gallery', function () {
    return view('dashboards.photo_gallery.yuva_sangh');
})->name('yuva_photo_gallery.view');

Route::get('/mahila_photo_gallery', function () {
    return view('dashboards.photo_gallery.mahila_samiti');
})->name('mahila_photo_gallery.view');


Route::get('/mahila_slider', function () {
    return view('dashboards.mahila_samiti.mahila_samiti_slider');
})->name('mahila_slider.view');

Route::get('/mahila_pst', function () {
    return view('dashboards.mahila_samiti.mahila_samiti_pst');
})->name('mahila_pst.view');

Route::get('/mahila_ex_president', function () {
    return view('dashboards.mahila_samiti.karyakarini.mahila_samiti_ex_prsident');
})->name('mahila_ex_president.view');
});
                     