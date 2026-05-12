@extends('includes.layouts.shree_sangh')

@section('title', 'Daily Panchang')
@section('page-title', '🕉️ दैनिक पंचांग')

@section('content')

<style>
    /* ───── Page Header ───── */
    .panchang-header {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 40%, #ffcd3c 100%);
        border-radius: 1.2rem;
        padding: 28px 32px;
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(255, 107, 53, 0.3);
        position: relative;
        overflow: hidden;
    }
    .panchang-header::before {
        content: '🕉️';
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 80px;
        opacity: 0.15;
        line-height: 1;
    }
    .panchang-header h2 { color:#fff; font-size:26px; font-weight:700; margin:0 0 6px 0; text-shadow:0 2px 8px rgba(0,0,0,.2); }
    .panchang-header p  { color:rgba(255,255,255,.9); margin:0; font-size:14px; }

    /* ───── Flash Alerts ───── */
    .flash-alert {
        border-radius: 1rem;
        padding: 14px 20px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
        animation: fadeSlideIn .4s ease;
        border: none;
    }
    .flash-alert.success { background: linear-gradient(135deg, rgba(79,172,254,.12), rgba(0,242,254,.08)); color: #0a6847; border-left: 4px solid #4facfe; }
    .flash-alert.error   { background: linear-gradient(135deg, rgba(245,87,108,.1), rgba(240,147,251,.08)); color: #7d1128; border-left: 4px solid #f5576c; }
    .flash-alert.warning { background: linear-gradient(135deg, rgba(255,107,53,.1), rgba(247,147,30,.08)); color: #7a3d00; border-left: 4px solid #f7931e; }
    .flash-alert .flash-icon { font-size: 22px; flex-shrink: 0; }
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(-12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ───── Stats Bar ───── */
    .stats-bar { display:flex; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .stat-pill {
        background:#fff; border-radius:50px; padding:10px 20px;
        display:flex; align-items:center; gap:10px;
        box-shadow:0 4px 16px rgba(0,0,0,.08);
        font-weight:600; font-size:14px; color:#1e3c72;
        border:1px solid rgba(102,126,234,.15);
        transition:transform .2s ease,box-shadow .2s ease;
    }
    .stat-pill:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(102,126,234,.18); }
    .stat-pill .stat-icon { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:15px; }
    .stat-pill .stat-icon.orange { background:linear-gradient(135deg,#ff6b35,#f7931e); color:#fff; }
    .stat-pill .stat-icon.purple { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
    .stat-pill .stat-icon.green  { background:linear-gradient(135deg,#4facfe,#00f2fe); color:#fff; }

    /* ───── Two-panel row ───── */
    .action-row { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px; }
    @media(max-width:768px){ .action-row { grid-template-columns:1fr; } }

    .panel-card {
        background:#fff; border-radius:1.1rem; padding:22px 24px;
        box-shadow:0 4px 20px rgba(0,0,0,.07);
    }
    .panel-card h6 {
        font-size:13px; font-weight:700; letter-spacing:.5px;
        text-transform:uppercase; margin-bottom:16px;
        display:flex; align-items:center; gap:8px;
    }
    .panel-card h6.fetch-heading { color:#ff6b35; }
    .panel-card h6.search-heading { color:#667eea; }

    .panel-card .form-control {
        border-radius:.6rem; border:2px solid #e8ecf4;
        font-size:14px; padding:9px 14px;
        transition:border-color .2s ease,box-shadow .2s ease;
    }
    .panel-card .form-control:focus {
        border-color:#667eea;
        box-shadow:0 0 0 3px rgba(102,126,234,.15);
        outline:none;
    }

    /* Fetch button */
    .btn-fetch {
        background:linear-gradient(135deg,#ff6b35,#f7931e);
        color:#fff; border:none; border-radius:.6rem;
        padding:9px 22px; font-weight:700; font-size:14px;
        cursor:pointer; transition:all .3s ease;
        display:inline-flex; align-items:center; gap:8px;
        white-space:nowrap;
    }
    .btn-fetch:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(255,107,53,.45); }
    .btn-fetch:disabled { opacity:.65; transform:none; cursor:not-allowed; }

    /* Search button */
    .btn-search {
        background:linear-gradient(135deg,#667eea,#764ba2);
        color:#fff; border:none; border-radius:.6rem;
        padding:9px 22px; font-weight:700; font-size:14px;
        cursor:pointer; transition:all .3s ease;
        display:inline-flex; align-items:center; gap:8px;
    }
    .btn-search:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(102,126,234,.4); }

    .btn-reset {
        background:linear-gradient(135deg,#f093fb,#f5576c);
        color:#fff; border:none; border-radius:.6rem;
        padding:9px 18px; font-weight:700; font-size:14px;
        cursor:pointer; text-decoration:none;
        display:inline-flex; align-items:center; gap:6px;
        transition:all .3s ease;
    }
    .btn-reset:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(245,87,108,.4); color:#fff; }

    /* Force refetch checkbox */
    .force-check-wrap {
        display:flex; align-items:center; gap:8px;
        margin-top:10px; font-size:13px; color:#7a8fab; font-weight:500;
    }
    .force-check-wrap input[type=checkbox] {
        width:16px; height:16px; accent-color:#ff6b35; cursor:pointer;
    }

    /* Spinner */
    .btn-fetch .spinner {
        display:none; width:16px; height:16px;
        border:2px solid rgba(255,255,255,.4);
        border-top-color:#fff;
        border-radius:50%;
        animation:spin .7s linear infinite;
    }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* ───── Table ───── */
    .panchang-table-wrap { background:#fff; border-radius:1.2rem; box-shadow:0 8px 32px rgba(0,0,0,.08); overflow:hidden; }
    .panchang-table-wrap table { margin:0; font-size:14px; }
    .panchang-table-wrap thead th {
        background:linear-gradient(135deg,#1e3c72,#2a5298);
        color:#fff; font-weight:600; padding:16px 18px;
        border:none; font-size:13px; letter-spacing:.4px; white-space:nowrap;
    }
    .panchang-table-wrap tbody tr { transition:background .2s ease; border-bottom:1px solid rgba(102,126,234,.08); }
    .panchang-table-wrap tbody tr:hover { background:linear-gradient(90deg,rgba(102,126,234,.06),rgba(118,75,162,.04)); }
    .panchang-table-wrap tbody td { padding:15px 18px; vertical-align:middle; border:none; color:#102a43; }

    /* ───── Badges ───── */
    .badge-tithi         { background:linear-gradient(135deg,#ff6b35,#f7931e); color:#fff; border-radius:50px; padding:5px 14px; font-weight:600; font-size:12.5px; }
    .badge-tithi-two     { background:linear-gradient(135deg,#f093fb,#f5576c); color:#fff; border-radius:50px; padding:5px 14px; font-weight:600; font-size:12.5px; }
    .badge-krishna       { background:linear-gradient(135deg,#1e3c72,#2a5298); color:#fff; border-radius:50px; padding:5px 14px; font-weight:600; font-size:12.5px; }
    .badge-shukla        { background:linear-gradient(135deg,#f093fb,#f5576c); color:#fff; border-radius:50px; padding:5px 14px; font-weight:600; font-size:12.5px; }
    .badge-samvat        { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; border-radius:50px; padding:5px 14px; font-weight:600; font-size:12.5px; }
    .badge-month         { background:linear-gradient(135deg,#4facfe,#00f2fe); color:#fff; border-radius:50px; padding:5px 14px; font-weight:600; font-size:12.5px; }

    /* ───── Date Cell ───── */
    .date-cell { display:flex; flex-direction:column; }
    .date-cell .date-main { font-weight:700; font-size:15px; color:#1e3c72; }
    .date-cell .date-day  { font-size:12px; color:#7a8fab; margin-top:2px; }

    /* ───── Sr No ───── */
    .sr-no { width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; }

    /* ───── Tithi Number ───── */
    .tithi-num { display:inline-flex; width:28px; height:28px; border-radius:50%; background:rgba(255,107,53,.12); color:#ff6b35; align-items:center; justify-content:center; font-weight:700; font-size:12px; margin-right:6px; }

    /* ───── Today highlight ───── */
    .row-today { background:linear-gradient(90deg,rgba(255,107,53,.06),rgba(247,147,30,.04)) !important; }
    .today-badge { display:inline-block; background:linear-gradient(135deg,#ff6b35,#f7931e); color:#fff; font-size:10px; font-weight:700; border-radius:4px; padding:2px 7px; margin-left:8px; letter-spacing:.5px; }

    /* ───── Delete Btn ───── */
    .btn-del { background:none; border:none; color:#f5576c; padding:4px 8px; border-radius:6px; cursor:pointer; transition:background .2s,transform .2s; font-size:16px; }
    .btn-del:hover { background:rgba(245,87,108,.1); transform:scale(1.15); }

    /* ───── Empty State ───── */
    .empty-state { text-align:center; padding:60px 20px; }
    .empty-state .emoji { font-size:64px; margin-bottom:16px; display:block; }
    .empty-state h5 { color:#1e3c72; font-weight:700; margin-bottom:8px; }
    .empty-state p  { color:#7a8fab; font-size:14px; }

    /* ───── Pagination ───── */
    .pagination-wrap { padding:18px 24px; border-top:1px solid rgba(102,126,234,.1); background:#fafbff; }
    .pagination .page-link { border:none; border-radius:8px !important; margin:0 3px; color:#667eea; font-weight:600; padding:8px 14px; transition:all .2s ease; }
    .pagination .page-item.active .page-link { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; box-shadow:0 4px 12px rgba(102,126,234,.4); }
    .pagination .page-link:hover:not(.active) { background:rgba(102,126,234,.12); color:#1e3c72; transform:translateY(-1px); }
</style>

<div class="container-fluid px-0">

    {{-- ───── Page Header ───── --}}
    <div class="panchang-header">
        <h2>🕉️ दैनिक पंचांग रिकॉर्ड</h2>
        <p>Bikaner (Rajasthan) — Lahiri Ayanamsha — Asia/Kolkata &nbsp;|&nbsp; Har roz raat 12 baje auto-fetch</p>
    </div>

    {{-- ───── Flash Alerts ───── --}}
    @if(session('success'))
        <div class="flash-alert success">
            <span class="flash-icon">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flash-alert error">
            <span class="flash-icon">❌</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="flash-alert warning">
            <span class="flash-icon">⚠️</span>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    {{-- ───── Stats Bar ───── --}}
    <div class="stats-bar">
        <div class="stat-pill">
            <div class="stat-icon orange">📅</div>
            <div>
                <div style="font-size:12px;color:#7a8fab;font-weight:500;">Total Records</div>
                <div>{{ $panchangs->total() }}</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="stat-icon purple">🌙</div>
            <div>
                <div style="font-size:12px;color:#7a8fab;font-weight:500;">Aaj Ki Date</div>
                <div>{{ \Carbon\Carbon::now('Asia/Kolkata')->format('d M Y') }}</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="stat-icon green">🔄</div>
            <div>
                <div style="font-size:12px;color:#7a8fab;font-weight:500;">Auto Fetch</div>
                <div>Har Roz Raat 12 Baje</div>
            </div>
        </div>
    </div>

    {{-- ───── Action Row: Fetch Panel + Search Panel ───── --}}
    <div class="action-row">

        {{-- 🔴 Fetch Panel (kisi bhi date ke liye) --}}
        <div class="panel-card">
            <h6 class="fetch-heading">
                <i class="bi bi-cloud-download-fill"></i>
                Kisi Bhi Date Ka Panchang Fetch Karo
            </h6>
            <form method="POST" action="{{ route('daily.panchang.fetch') }}" id="fetchForm">
                @csrf
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <input
                        type="date"
                        name="fetch_date"
                        id="fetch_date"
                        class="form-control @error('fetch_date') is-invalid @enderror"
                        value="{{ old('fetch_date', \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d')) }}"
                        max="{{ \Carbon\Carbon::now('Asia/Kolkata')->addYears(1)->format('Y-m-d') }}"
                        required
                        style="max-width:180px;"
                    >
                    <button type="submit" class="btn-fetch" id="fetchBtn">
                        <span class="spinner" id="fetchSpinner"></span>
                        <i class="bi bi-cloud-arrow-down" id="fetchIcon"></i>
                        Fetch Karo
                    </button>
                </div>
                @error('fetch_date')
                    <div style="color:#f5576c;font-size:13px;margin-top:8px;">{{ $message }}</div>
                @enderror
                {{-- Force Refetch checkbox --}}
                <div class="force-check-wrap">
                    <input type="checkbox" name="force_refetch" id="force_refetch" value="1">
                    <label for="force_refetch">
                        Force Refetch — agar is date ka data pehle se hai tab bhi dobara API call karo
                    </label>
                </div>
                <div style="margin-top:10px;font-size:12px;color:#7a8fab;">
                    <i class="bi bi-info-circle"></i>
                    Time hamesha 12:00 Noon (IST) se fetch hoga.
                </div>
            </form>
        </div>

        {{-- 🔵 Search Panel --}}
        <div class="panel-card">
            <h6 class="search-heading">
                <i class="bi bi-search"></i>
                Database Mein Date Se Dhundho
            </h6>
            <form method="GET" action="{{ url('/daily-panchang') }}">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <input
                        type="date"
                        name="search_date"
                        id="search_date"
                        class="form-control"
                        value="{{ request('search_date') }}"
                        style="max-width:180px;"
                    >
                    <button type="submit" class="btn-search">
                        <i class="bi bi-search"></i> Search
                    </button>
                    @if(request('search_date'))
                        <a href="{{ url('/daily-panchang') }}" class="btn-reset">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    @endif
                </div>
                <div style="margin-top:10px;font-size:12px;color:#7a8fab;">
                    <i class="bi bi-info-circle"></i>
                    Sirf database mein saved records mein search hoga.
                </div>
            </form>
        </div>

    </div>

    {{-- ───── Table ───── --}}
    <div class="panchang-table-wrap">
        @if($panchangs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>📅 Date</th>
                            <th>🌸 Lunar Month</th>
                            <th>🏛️ Vikram Samvat</th>
                            <th>🔢 Tithi No.</th>
                            <th>🌙 Tithi (Sunrise)</th>
                            <th>🌙 Tithi (12 Noon)</th>
                            <th>☀️ Paksha</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $today = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d'); @endphp
                        @foreach($panchangs as $p)
                            @php $isToday = $p->date->format('Y-m-d') === $today; @endphp
                            <tr class="{{ $isToday ? 'row-today' : '' }}">

                                {{-- # --}}
                                <td>
                                    <div class="sr-no">
                                        {{ ($panchangs->currentPage() - 1) * $panchangs->perPage() + $loop->iteration }}
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <div class="date-cell">
                                        <span class="date-main">
                                            {{ $p->date->format('d M Y') }}
                                            @if($isToday)
                                                <span class="today-badge">TODAY</span>
                                            @endif
                                        </span>
                                        <span class="date-day">{{ $p->date->format('l') }}</span>
                                    </div>
                                </td>

                                {{-- Lunar Month --}}
                                <td>
                                    @if($p->lunar_month_name)
                                        <span class="badge-month">{{ $p->lunar_month_name }}</span>
                                    @else <span class="text-muted">—</span> @endif
                                </td>

                                {{-- Vikram Samvat --}}
                                <td>
                                    @if($p->vikram_samvat)
                                        <span class="badge-samvat">{{ $p->vikram_samvat }}</span>
                                    @else <span class="text-muted">—</span> @endif
                                </td>

                                {{-- Tithi Number --}}
                                <td>
                                    @if($p->tithi_number)
                                        <span class="tithi-num">{{ $p->tithi_number }}</span>
                                    @else <span class="text-muted">—</span> @endif
                                </td>

                                {{-- Tithi (sunrise) --}}
                                <td>
                                    @if($p->tithi)
                                        <span class="badge-tithi">{{ $p->tithi }}</span>
                                    @else <span class="text-muted">—</span> @endif
                                </td>

                                {{-- Tithi Two (noon) --}}
                                <td>
                                    @if($p->tithi_two)
                                        <span class="badge-tithi-two">{{ $p->tithi_two }}</span>
                                    @else <span class="text-muted">—</span> @endif
                                </td>

                                {{-- Paksha --}}
                                <td>
                                    @if($p->paksha)
                                        @if(strtolower($p->paksha) === 'krishna')
                                            <span class="badge-krishna">🌑 {{ $p->paksha }}</span>
                                        @else
                                            <span class="badge-shukla">🌕 {{ $p->paksha }}</span>
                                        @endif
                                    @else <span class="text-muted">—</span> @endif
                                </td>

                                {{-- Delete --}}
                                <td>
                                    <form method="POST"
                                          action="{{ route('daily.panchang.delete', $p->id) }}"
                                          onsubmit="return confirmDelete('{{ $p->date->format('d M Y') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-del" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($panchangs->hasPages())
                <div class="pagination-wrap d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div style="font-size:13px;color:#7a8fab;font-weight:500;">
                        Showing <strong>{{ $panchangs->firstItem() }}</strong> – <strong>{{ $panchangs->lastItem() }}</strong>
                        of <strong>{{ $panchangs->total() }}</strong> records
                    </div>
                    {{ $panchangs->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="pagination-wrap text-center" style="font-size:13px;color:#7a8fab;">
                    Total {{ $panchangs->total() }} record(s)
                </div>
            @endif

        @else
            <div class="empty-state">
                <span class="emoji">🕉️</span>
                <h5>Koi Record Nahi Mila</h5>
                <p>
                    @if(request('search_date'))
                        <strong>{{ \Carbon\Carbon::parse(request('search_date'))->format('d M Y') }}</strong> ke liye koi panchang data nahi hai.<br>
                        Upar "Fetch Karo" button se is date ka data mangao.<br>
                        <a href="{{ url('/daily-panchang') }}" style="color:#667eea;">↩ Sab records dekho</a>
                    @else
                        Abhi tak koi panchang data nahi hai.<br>
                        Upar se koi bhi date select karke "Fetch Karo" dabao.
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>

<script>
// ── Loading spinner on fetch ──
document.getElementById('fetchForm').addEventListener('submit', function () {
    const btn     = document.getElementById('fetchBtn');
    const spinner = document.getElementById('fetchSpinner');
    const icon    = document.getElementById('fetchIcon');
    btn.disabled           = true;
    spinner.style.display  = 'block';
    icon.style.display     = 'none';
    btn.childNodes[btn.childNodes.length - 1].textContent = ' Fetch ho raha hai...';
});

// ── Delete confirm ──
function confirmDelete(dateStr) {
    return confirm(`⚠️ "${dateStr}" ka panchang record delete karna chahte hain?\n\nYeh action undo nahi ho sakta.`);
}

// ── Auto-hide flash alerts after 5s ──
setTimeout(() => {
    document.querySelectorAll('.flash-alert').forEach(el => {
        el.style.transition = 'opacity .5s ease, transform .5s ease';
        el.style.opacity    = '0';
        el.style.transform  = 'translateY(-8px)';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>

@endsection
