<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Shree_sangh\ThoughtApiController;
use App\Http\Controllers\Shree_sangh\Karyakarini\ExPresidentController;
use App\Models\Aanchal\Aanchal;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Updated: Use Laravel session-based auth for web routes (auth middleware will work).
|
*/

// Notification route (example)
Route::post('/send-notification', [NotificationController::class, 'sendNotification']);

// change-password view + submit दोनों को auth के अंदर रखें
Route::middleware('auth')->group(function () {
    Route::view('/change-password_shree_sangh', 'change_password_dashboards.change-password')
        ->name('change-password.shree_sangh');
    Route::view('/change-password_mahila_samiti', 'change_password_dashboards.change_password_mahila')
        ->name('change-password.mahila_samiti');
    Route::view('/change-password_yuva_sangh', 'change_password_dashboards.change_password_yuva')
        ->name('change-password.yuva_sangh');
    Route::view('/change-password_shramnopasak', 'change_password_dashboards.change_password_shramnopasak')
        ->name('change-password.shramnopasak');
    Route::view('/change-password_sahitya', 'change_password_dashboards.change_password_sahitya')
        ->name('change-password.sahitya');
    Route::view('/change-password_super_admin', 'change_password_dashboards.change_password_super_admin')
        ->name('change-password.super_admin');
    Route::view('/change-password_spf', 'change_password_dashboards.change_password_spf')
        ->name('change-password.spf');

    Route::post('/change-password', [AuthController::class, 'updatePassword'])
        ->name('password.update');
});

// Login Page (Accessible to All)
Route::get('/', function () {
    return view('login');
})->name('login');

// Register Page (Accessible to All)
Route::get('/register', function () {
    return view('register');
})->name('register');

// Register Submit
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:super_admin,shree_sangh,yuva_sangh,spf,mahila_samiti,sahitya,sahitya_publication'
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        Log::info('USER CREATED: id=' . $user->id . ', email=' . $user->email . ', role=' . $user->role);

        return back()->with('success', 'User created successfully! You can now login.');
    } catch (\Exception $e) {
        Log::error('USER CREATION FAILED: ' . $e->getMessage());
        return back()->with('error', 'Failed to create user. Please try again.')->withInput();
    }
})->name('register.submit');

// Login Submit (web session-based login)
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Invalid credentials');
    }

    // log in using Laravel guard
    auth()->login($user);

    // optional: maintain legacy session('user') if other code expects it
    session(['user' => auth()->user()]);

    // regenerate token to avoid fixation
    $request->session()->regenerate();

    // debug logs (remove later)
    Log::info('LOGIN: user id=' . auth()->id());
    Log::info('SESSION KEYS: ' . json_encode(array_keys(session()->all())));

    // redirect as before
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
        case 'spf':
            return redirect()->route('dashboard.spf');
        default:
            auth()->logout();
            return back()->with('error', 'Unknown role');
    }
})->name('login.submit');


// Logout (web session)
Route::get('/logout', function (Request $request) {
    auth()->logout();

    // Invalidate and regenerate token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');


// ✅ All routes below require user to be logged in (your custom checkSession middleware + web)
Route::middleware(['web', 'checkSession'])->group(function () {

    // Super Admin Dashboard (already only for super_admin)
    Route::middleware('matchRole:super_admin')->get('/dashboard/super_admin', function () {
        return view('dashboards.super_admin.index');
    })->name('dashboard.super_admin');

    // Sahitya Dashboard
    Route::middleware('matchRole:sahitya,super_admin')->get('/dashboard/sahitya', function () {
        return view('dashboards.sahitya.index');
    })->name('dashboard.sahitya');

    // Sahitya publication Dashboard
    Route::middleware('matchRole:sahitya_publication,super_admin')->get('/dashboard/sahitya_publication', function () {
        return view('dashboards.sahitya_publication.index');
    })->name('dashboard.sahitya_publication');

    // Yuva Sangh Dashboard
    Route::middleware('matchRole:yuva_sangh,super_admin')->get('/dashboard/yuva_sangh', function () {
        return view('dashboards.yuva_sangh.index');
    })->name('dashboard.yuva_sangh');

    // Mahila Samiti Dashboard
    Route::middleware('matchRole:mahila_samiti,super_admin')->get('/dashboard/mahila_samiti', function () {
        return view('dashboards.mahila_samiti.index');
    })->name('dashboard.mahila_samiti');

    Route::middleware('matchRole:spf,super_admin')->get('/dashboard/spf', function () {
        return view('dashboards.spf.index');
    })->name('dashboard.spf');

    // Shree Sangh Dashboard    
    Route::middleware('matchRole:shree_sangh,super_admin')->get('/dashboard/shree_sangh', function () {
        return view('dashboards.shree_sangh.index');
    })->name('dashboard.shree_sangh');
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

    Route::get('/jsp_results', function () {
        return view('shree_sangh.jsp.JspResult');
    })->name('jsp.result');

    Route::get('/jsp_bulk_results', function () {
        return view('shree_sangh.jsp.JspResultBulkUpload');
    })->name('jsp.bulk_results');

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

    Route::get('/mahila_vp_sec', function () {
        return view('dashboards.mahila_samiti.karyakarini.mahila_samiti_vp_sec');
    })->name('mahila_vp_sec.view');

    Route::get('/mahila_ksm_members', function () {
        return view('dashboards.mahila_samiti.karyakarini.mahila_samiti_ksm_members');
    })->name('mahila_ksm_members.view');


    Route::get('/mahila_pravarti_sanyojika', function () {
        return view('dashboards.mahila_samiti.karyakarini.mahila_samiti_pravarti_sanyojika');
    })->name('mahila_pravarti_sanyojika.view');

    Route::get('/mahila_events', function () {
        return view('dashboards.mahila_samiti.events.mahila_samiti_events');
    })->name('mahila_events.view');


    Route::get('/mahila_aavedan_patra', function () {
        return view('dashboards.mahila_samiti.downloads.mahila_samiti_aavedan_patra');
    })->name('mahila_aavedan_patra.view');

    Route::get('/mahila_prativedan', function () {
        return view('dashboards.mahila_samiti.downloads.mahila_samiti_prativedan');
    })->name('mahila_prativedan.view');


    Route::get('/mobile_slider', function () {
        return view('dashboards.shree_sangh.mobile_slider.mobile_slider');
    })->name('mobile_slider.view');

    Route::get('/mahila_samiti_photo_gallery', function () {
        return view('dashboards.photo_gallery.mahila_samiti');
    })->name('mahila_samiti_photo_gallery.view');

    Route::get('/photo_gallery_mahila_samiti', function () {
        return view('dashboards.photo_gallery.add_photo_mahila');
    })->name('photo_gallery_mahila_samiti.view');

    Route::get('/mahila_mobile_slider', function () {
        return view('dashboards.mahila_samiti.mobile_slider.mobile_slider');
    })->name('mahila_mobile_slider.view');

    Route::get('/mahila_home_slider', function () {
        return view('dashboards.mahila_samiti.mobile_slider.home_slider');
    })->name('mahila_home_slider.view');


    Route::get('/yuva_home_slider', function () {
        return view('dashboards.yuva_sangh.yuva_slider.yuva_slider');
    })->name('yuva_home_slider.view');

    Route::get('/yuva_news', function () {
        return view('dashboards.yuva_sangh.news_and_events.news_and_events');
    })->name('yuva_news_slider.view');

    Route::get('/yuva_pst', function () {
        return view('dashboards.yuva_sangh.karyakarini.yuva_pst');
    })->name('yuva_pst.view');

    Route::get('/yuva_ex_president', function () {
        return view('dashboards.yuva_sangh.karyakarini.yuva_ex_president');
    })->name('yuva_ex_president.view');

    Route::get('/yuva_vp_sec', function () {
        return view('dashboards.yuva_sangh.karyakarini.yuva_vp_sec');
    })->name('/yuva_vp_sec.view');

    Route::get('/yuva_mobile_slider', function () {
        return view('dashboards.yuva_sangh.mobile_slider.yuva_sangh_mobile_slider');
    })->name('yuva_mobile_slider.view');

    Route::get('/yuva_main_home_slider', function () {
        return view('dashboards.yuva_sangh.mobile_slider.shree_sangh_yuva_slider');
    })->name('yuva_main_home_slider.view');


    Route::get('/photo_gallery_yuva_sangh', function () {
        return view('dashboards.photo_gallery.add_photo_yuva');
    })->name('photo_gallery_yuva_sangh.view');

    Route::get('/yuva_sangh_photo_gallery', function () {
        return view('dashboards.photo_gallery.yuva_sangh');
    })->name('yuva_sangh_photo_gallery.view');


    Route::get('/yuva_sangh_pravartiya', function () {
        return view('dashboards.yuva_sangh.pravartiya.yuva_pravartiya');
    })->name('yuva_sangh_pravartiya.view');

    Route::get('/send_notification-form', function () {
        return view('notifications.send_notifications.send');
    })->name('notification_send.view');

    Route::get('/view_notifications_all', function () {
        return view('notifications.super_admin_notifications');
    })->name('notification_view.all_view');

    Route::get('/view_notifications_shree_sangh', function () {
        return view('notifications.view_notifications');
    })->name('notification_view.shree_sangh_view');

    Route::get('/view_notifications_mahila_samiti', function () {
        return view('notifications.mahila_samiti_notifications');
    })->name('notification_view.mahila_samiti_view');

    Route::get('/view_notifications_yuva_sangh', function () {
        return view('notifications.yuva_sangh_notifications');
    })->name('notification_view.yuva_sangh_view');

    Route::get('/send_notification-shree_sangh', function () {
        return view('notifications.send_notifications.shree_sangh_notifications');
    })->name('notification_send.shree_sangh');

    Route::get('/send_notification-mahila_Samiti', function () {
        return view('notifications.send_notifications.mahila_samiti_notifications');
    })->name('notification_send.mahila_samiti');

    Route::get('/send_notification-yuva_sangh', function () {
        return view('notifications.send_notifications.yuva_sangh_notifications');
    })->name('notification_send.yuva_sangh');

    Route::get('/mobile_app_version', function () {
        return view('app_version.app_version');
    })->name('mobile_app_version');
});

Route::get('/yuva_content', function () {
    return view('dashboards.yuva_sangh.general_details.update_content');
})->name('yuva_content.view');

// SPF Dashboard Home Screen
Route::get('/dashboard/spf/home', function () {
    return view('dashboards.spf.home_screen');
})->name('dashboard.spf.home');

Route::get('/dashboard/spf/committee', function () {
    return view('dashboards.spf.spf_committee_screen');
})->name('dashboard.spf.committee');

Route::get('/dashboard/spf/slider', function () {
    return view('dashboards.spf.slider');
})->name('dashboard.spf.slider');

Route::get('/dashboard/spf/events', function () {
    return view('dashboards.spf.events');
})->name('dashboard.spf.events');


Route::get('/spf_photo_gallery', function () {
    return view('dashboards.photo_gallery.add_photo_spf');
})->name('photo_gallery_spf.view');

Route::get('/spf_photo_gallery_view', function () {
    return view('dashboards.photo_gallery.spf_photos');
})->name('spf_photo_gallery.view');

Route::get('/dashboard/spf/projects', function () {
    return view('dashboards.spf.projects');
})->name('dashboard.spf.projects');

Route::get('/dashboard/spf/safarnama', function () {
    return view('dashboards.spf.safarnama');
})->name('dashboard.spf.safarnama');

Route::get('/dashboard/spf/downloads', function () {
    return view('dashboards.spf.spfdownloads');
})->name('dashboard.spf.downloads');