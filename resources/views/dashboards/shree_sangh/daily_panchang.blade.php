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
        margin-bottom: 28px;
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

    .panchang-header h2 {
        color: #fff;
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 6px 0;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .panchang-header p {
        color: rgba(255,255,255,0.9);
        margin: 0;
        font-size: 14px;
    }

    /* ───── Stats Bar ───── */
    .stats-bar {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .stat-pill {
        background: #fff;
        border-radius: 50px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        font-weight: 600;
        font-size: 14px;
        color: #1e3c72;
        border: 1px solid rgba(102,126,234,0.15);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(102,126,234,0.18);
    }

    .stat-pill .stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .stat-pill .stat-icon.orange { background: linear-gradient(135deg, #ff6b35, #f7931e); color: #fff; }
    .stat-pill .stat-icon.purple { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
    .stat-pill .stat-icon.green  { background: linear-gradient(135deg, #4facfe, #00f2fe); color: #fff; }

    /* ───── Search Card ───── */
    .search-card {
        background: #fff;
        border-radius: 1rem;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .search-card label {
        font-weight: 600;
        color: #1e3c72;
        font-size: 14px;
        margin: 0;
        white-space: nowrap;
    }

    .search-card .form-control {
        border-radius: 0.6rem;
        border: 2px solid #e8ecf4;
        font-size: 14px;
        padding: 9px 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        max-width: 200px;
    }

    .search-card .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        outline: none;
    }

    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        border-radius: 0.6rem;
        padding: 9px 22px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102,126,234,0.4);
    }

    .btn-reset {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #fff;
        border: none;
        border-radius: 0.6rem;
        padding: 9px 18px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245,87,108,0.4);
        color: #fff;
    }

    /* ───── Table ───── */
    .panchang-table-wrap {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .panchang-table-wrap table {
        margin: 0;
        font-size: 14px;
    }

    .panchang-table-wrap thead th {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #fff;
        font-weight: 600;
        padding: 16px 18px;
        border: none;
        font-size: 13px;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }

    .panchang-table-wrap thead th:first-child { border-radius: 0; }

    .panchang-table-wrap tbody tr {
        transition: background 0.2s ease, transform 0.15s ease;
        border-bottom: 1px solid rgba(102,126,234,0.08);
    }

    .panchang-table-wrap tbody tr:hover {
        background: linear-gradient(90deg, rgba(102,126,234,0.06), rgba(118,75,162,0.04));
    }

    .panchang-table-wrap tbody td {
        padding: 15px 18px;
        vertical-align: middle;
        border: none;
        color: #102a43;
    }

    /* ───── Badges ───── */
    .badge-tithi {
        background: linear-gradient(135deg, #ff6b35, #f7931e);
        color: #fff;
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 600;
        font-size: 12.5px;
    }

    .badge-paksha-krishna {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 600;
        font-size: 12.5px;
    }

    .badge-paksha-shukla {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: #fff;
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 600;
        font-size: 12.5px;
    }

    .badge-samvat {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 600;
        font-size: 12.5px;
    }

    .badge-month {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: #fff;
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 600;
        font-size: 12.5px;
    }

    /* ───── Date Cell ───── */
    .date-cell {
        display: flex;
        flex-direction: column;
    }

    .date-cell .date-main {
        font-weight: 700;
        font-size: 15px;
        color: #1e3c72;
    }

    .date-cell .date-day {
        font-size: 12px;
        color: #7a8fab;
        margin-top: 2px;
    }

    /* ───── Sr No ───── */
    .sr-no {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
    }

    /* ───── Empty State ───── */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state .emoji {
        font-size: 64px;
        margin-bottom: 16px;
        display: block;
    }

    .empty-state h5 {
        color: #1e3c72;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #7a8fab;
        font-size: 14px;
    }

    /* ───── Pagination ───── */
    .pagination-wrap {
        padding: 18px 24px;
        border-top: 1px solid rgba(102,126,234,0.1);
        background: #fafbff;
    }

    .pagination .page-link {
        border: none;
        border-radius: 8px !important;
        margin: 0 3px;
        color: #667eea;
        font-weight: 600;
        padding: 8px 14px;
        transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        box-shadow: 0 4px 12px rgba(102,126,234,0.4);
    }

    .pagination .page-link:hover:not(.active) {
        background: rgba(102,126,234,0.12);
        color: #1e3c72;
        transform: translateY(-1px);
    }

    /* ───── Today highlight ───── */
    .row-today {
        background: linear-gradient(90deg, rgba(255,107,53,0.06), rgba(247,147,30,0.04)) !important;
    }

    .today-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff6b35, #f7931e);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        border-radius: 4px;
        padding: 2px 7px;
        margin-left: 8px;
        letter-spacing: 0.5px;
    }

    /* ───── Tithi Number ───── */
    .tithi-num {
        display: inline-flex;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255,107,53,0.12);
        color: #ff6b35;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        margin-right: 6px;
    }
</style>

<div class="container-fluid px-0">

    {{-- ───── Page Header ───── --}}
    <div class="panchang-header">
        <h2>🕉️ दैनिक पंचांग रिकॉर्ड</h2>
        <p>Bikaner (Rajasthan) — Lahiri Ayanamsha — Asia/Kolkata — Har roz auto-fetch hota hai</p>
    </div>

    {{-- ───── Stats Bar ───── --}}
    <div class="stats-bar">
        <div class="stat-pill">
            <div class="stat-icon orange">📅</div>
            <div>
                <div style="font-size:12px; color:#7a8fab; font-weight:500;">Total Records</div>
                <div>{{ $panchangs->total() }}</div>
            </div>
        </div>

        <div class="stat-pill">
            <div class="stat-icon purple">🌙</div>
            <div>
                <div style="font-size:12px; color:#7a8fab; font-weight:500;">Aaj Ki Date</div>
                <div>{{ \Carbon\Carbon::now('Asia/Kolkata')->format('d M Y') }}</div>
            </div>
        </div>

        <div class="stat-pill">
            <div class="stat-icon green">🔄</div>
            <div>
                <div style="font-size:12px; color:#7a8fab; font-weight:500;">Auto Fetch</div>
                <div>Har Roz Raat 12 Baje</div>
            </div>
        </div>
    </div>

    {{-- ───── Search / Filter ───── --}}
    <form method="GET" action="{{ url('/daily-panchang') }}">
        <div class="search-card">
            <label for="search_date">
                <i class="bi bi-search me-1"></i> Date se dhundho:
            </label>
            <input
                type="date"
                id="search_date"
                name="search_date"
                class="form-control"
                value="{{ request('search_date') }}"
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
    </form>

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
                        </tr>
                    </thead>
                    <tbody>
                        @php $today = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d'); @endphp
                        @foreach($panchangs as $index => $p)
                            @php $isToday = $p->date->format('Y-m-d') === $today; @endphp
                            <tr class="{{ $isToday ? 'row-today' : '' }}">
                                {{-- Sr No --}}
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
                                        <span class="date-day">
                                            {{ $p->date->format('l') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Lunar Month --}}
                                <td>
                                    @if($p->lunar_month_name)
                                        <span class="badge-month">{{ $p->lunar_month_name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Vikram Samvat --}}
                                <td>
                                    @if($p->vikram_samvat)
                                        <span class="badge-samvat">{{ $p->vikram_samvat }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Tithi Number --}}
                                <td>
                                    @if($p->tithi_number)
                                        <span class="tithi-num">{{ $p->tithi_number }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Tithi (from tithi.name) --}}
                                <td>
                                    @if($p->tithi)
                                        <span class="badge-tithi">{{ $p->tithi }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Tithi Two (from request_time_panchang.tithi.name) --}}
                                <td>
                                    @if($p->tithi_two)
                                        <span class="badge-tithi" style="background: linear-gradient(135deg, #f093fb, #f5576c);">{{ $p->tithi_two }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Paksha --}}
                                <td>
                                    @if($p->paksha)
                                        @if(strtolower($p->paksha) === 'krishna')
                                            <span class="badge-paksha-krishna">🌑 {{ $p->paksha }}</span>
                                        @else
                                            <span class="badge-paksha-shukla">🌕 {{ $p->paksha }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ───── Pagination ───── --}}
            @if($panchangs->hasPages())
                <div class="pagination-wrap d-flex justify-content-between align-items-center">
                    <div style="font-size:13px; color:#7a8fab; font-weight:500;">
                        Showing <strong>{{ $panchangs->firstItem() }}</strong> – <strong>{{ $panchangs->lastItem() }}</strong>
                        of <strong>{{ $panchangs->total() }}</strong> records
                    </div>
                    {{ $panchangs->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="pagination-wrap text-center" style="font-size:13px; color:#7a8fab;">
                    Total {{ $panchangs->total() }} record(s)
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="empty-state">
                <span class="emoji">🕉️</span>
                <h5>Koi Record Nahi Mila</h5>
                <p>
                    @if(request('search_date'))
                        <strong>{{ \Carbon\Carbon::parse(request('search_date'))->format('d M Y') }}</strong> ke liye koi panchang data nahi hai.
                        <br><a href="{{ url('/daily-panchang') }}" style="color:#667eea;">Sab records dekho</a>
                    @else
                        Abhi tak koi panchang data fetch nahi hua.<br>
                        Server pe <code>php artisan panchang:fetch</code> command chalao.
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>

@endsection
