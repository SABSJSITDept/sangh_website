@extends('includes.layouts.yuva_sangh')

@section('title', 'Daily Panchang')
@section('page-title', '🕉️ दैनिक पंचांग')

@section('content')

@php
    $tithis = [
        'Pratipada' => 'प्रतिपदा',
        'Dwitiya' => 'द्वितीया',
        'Tritiya' => 'तृतीया',
        'Chaturthi' => 'चतुर्थी',
        'Panchami' => 'पंचमी',
        'Shashti' => 'षष्ठी',
        'Saptami' => 'सप्तमी',
        'Ashtami' => 'अष्टमी',
        'Navami' => 'नवमी',
        'Dashami' => 'दशमी',
        'Ekadashi' => 'एकादशी',
        'Dwadashi' => 'द्वादशी',
        'Trayodashi' => 'त्रयोदशी',
        'Chaturdashi' => 'चतुर्दशी',
        'Purnima' => 'पूर्णिमा',
        'Amavasya' => 'अमावस्या',
    ];

    $months = [
        'Chaitra' => 'चैत्र',
        'Vaisakha' => 'वैशाख',
        'Jyeshtha' => 'ज्येष्ठ',
        'Ashadha' => 'आषाढ़',
        'Shravana' => 'श्रावण',
        'Bhadrapada' => 'भाद्रपद',
        'Ashvina' => 'आश्विन',
        'Kartika' => 'कार्तिक',
        'Margashirsha' => 'मार्गशीर्ष',
        'Pausha' => 'पौष',
        'Magha' => 'माघ',
        'Phalguna' => 'फाल्गुन',
    ];
@endphp

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

    /* ───── Action Row ───── */
    .action-row { display:grid; grid-template-columns: 1.5fr 1fr; gap:20px; margin-bottom:24px; }
    @media(max-width:991px){ .action-row { grid-template-columns:1fr; } }

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

    .btn-custom {
        border: none; border-radius:.6rem;
        padding: 9px 20px; font-weight:700; font-size:14px;
        transition:all .3s ease; display:inline-flex; align-items:center; gap:8px;
    }
    .btn-fetch { background:linear-gradient(135deg,#ff6b35,#f7931e); color:#fff; }
    .btn-fetch:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(255,107,53,.45); }
    
    .btn-add-manual { background:linear-gradient(135deg,#4facfe,#00f2fe); color:#fff; margin-left: 10px; }
    .btn-add-manual:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(79,172,254,.45); }

    .btn-search { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
    .btn-search:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(102,126,234,.4); }

    /* Table Badges */
    .badge-p { border-radius: 50px; padding: 4px 12px; font-weight: 600; font-size: 12px; color: #fff; }
    .bg-orange { background: linear-gradient(135deg, #ff6b35, #f7931e); }
    .bg-purple { background: linear-gradient(135deg, #667eea, #764ba2); }
    .bg-pink   { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .bg-blue   { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .bg-dark-blue { background: linear-gradient(135deg, #1e3c72, #2a5298); }
    .bg-green { background: linear-gradient(135deg, #28a745, #218838); }

    /* Actions */
    .btn-action { background: none; border: none; padding: 5px; cursor: pointer; transition: transform 0.2s; }
    .btn-edit { color: #667eea; }
    .btn-action:hover { transform: scale(1.2); }

    .tithi-num {
        display: inline-flex; width: 26px; height: 26px; border-radius: 50%;
        background: rgba(255,107,53,0.1); color: #ff6b35;
        align-items: center; justify-content: center; font-weight: 700; font-size: 11px;
    }

    /* Modal Styling */
    .modal-content { border-radius: 1.2rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
    .modal-header { background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; border-top-left-radius: 1.2rem; border-top-right-radius: 1.2rem; }
    .modal-title { font-weight: 700; }
    .btn-close { filter: invert(1); }
</style>

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="panchang-header">
        <h2>🕉️ दैनिक पंचांग (युवा संघ)</h2>
        <p>Bikaner (Rajasthan) — Lahiri Ayanamsha &nbsp;|&nbsp; Auto-fetch @ 12:00 PM IST</p>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="flash-alert success">
            <span class="flash-icon">✅</span><span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flash-alert error">
            <span class="flash-icon">❌</span><span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Action Row --}}
    <div class="action-row">
        {{-- Fetch & Add Panel --}}
        <div class="panel-card">
            <h6 class="fetch-heading"><i class="bi bi-cloud-download-fill"></i> Data Management</h6>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="POST" action="{{ route('daily.panchang.fetch') }}" class="d-flex align-items-center gap-2" id="fetchForm">
                    @csrf
                    <input type="date" name="fetch_date" class="form-control" value="{{ date('Y-m-d') }}" style="width:160px;">
                    <button type="submit" class="btn-custom btn-fetch" id="fetchBtn">
                        <i class="bi bi-cloud-arrow-down" id="fetchIcon"></i> Fetch
                    </button>
                </form>
                <button class="btn-custom btn-add-manual" data-bs-toggle="modal" data-bs-target="#addPanchangModal">
                    <i class="bi bi-plus-lg"></i> Add Manually
                </button>
            </div>
        </div>

        {{-- Search Panel --}}
        <div class="panel-card">
            <h6 class="search-heading"><i class="bi bi-search"></i> Search Record</h6>
            <form method="GET" action="{{ url('/daily-panchang') }}" class="d-flex align-items-center gap-2">
                <input type="date" name="search_date" class="form-control" value="{{ request('search_date') }}" style="width:160px;">
                <button type="submit" class="btn-custom btn-search">Search</button>
                @if(request('search_date'))
                    <a href="{{ url('/daily-panchang') }}" class="btn-reset btn btn-sm btn-outline-danger" style="border-radius:.6rem;">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm" style="border-radius: 1.2rem; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Month / Samvat</th>
                        <th>Tithi (Sunrise)</th>
                        <th>Tithi (12 Noon)</th>
                        <th>Paksha / Pakhi</th>
                        <th>Event</th>
                        <th class="text-center pe-4">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($panchangs as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-primary">{{ $p->date->format('d M Y') }}</div>
                            <small class="text-muted">{{ $p->date->format('l') }}</small>
                        </td>
                        <td>
                            <span class="badge-p bg-blue">{{ $months[$p->lunar_month_name] ?? $p->lunar_month_name }}</span><br>
                            <small class="text-muted">{{ $p->vikram_samvat }}</small>
                        </td>
                        <td>
                            <span class="tithi-num me-1">{{ $p->tithi_number }}</span>
                            <span class="badge-p bg-orange">{{ $tithis[$p->tithi] ?? $p->tithi }}</span>
                        </td>
                        <td><span class="badge-p bg-pink">{{ $tithis[$p->tithi_two] ?? $p->tithi_two }}</span></td>
                        <td>
                            @if(strtolower($p->paksha) == 'krishna')
                                <span class="badge-p bg-dark-blue">🌑 {{ $p->paksha }}</span>
                            @else
                                <span class="badge-p bg-pink">🌕 {{ $p->paksha }}</span>
                            @endif
                            @if($p->is_pakhi)
                                <span class="badge-p bg-green ms-1">Pakhi</span>
                            @endif
                        </td>
                        <td>
                            <div style="max-width: 150px; font-size: 0.85rem;" class="text-truncate" title="{{ $p->today_event }}">
                                {{ $p->today_event ?? '—' }}
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <button class="btn-action btn-edit" onclick='openEditModal({!! $p->toJson() !!})' title="Edit">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-light text-center">
            {{ $panchangs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addPanchangModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('daily.panchang.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Manual Panchang Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Date</label>
                        <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lunar Month</label>
                        <select name="lunar_month_name" class="form-select">
                            <option value="">Select Month</option>
                            @foreach($months as $key => $val)
                                <option value="{{ $key }}">{{ $val }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Vikram Samvat</label>
                        <input type="text" name="vikram_samvat" class="form-control" placeholder="e.g. 2083">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tithi Number</label>
                        <input type="number" name="tithi_number" class="form-control" placeholder="e.g. 15">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tithi (Sunrise)</label>
                        <select name="tithi" class="form-select">
                            <option value="">Select Tithi</option>
                            @foreach($tithis as $key => $val)
                                <option value="{{ $key }}">{{ $val }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tithi (12 Noon)</label>
                        <select name="tithi_two" class="form-select">
                            <option value="">Select Tithi</option>
                            @foreach($tithis as $key => $val)
                                <option value="{{ $key }}">{{ $val }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Paksha</label>
                        <select name="paksha" class="form-select">
                            <option value="Shukla">Shukla</option>
                            <option value="Krishna">Krishna</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Is Pakhi?</label>
                        <select name="is_pakhi" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Today's Event / Special Info</label>
                        <textarea name="today_event" class="form-control" rows="2" placeholder="e.g. Mahavir Jayanti"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Save Record</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editPanchangModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <h5 class="modal-title">Edit Panchang Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Date</label>
                        <input type="date" name="date" id="edit_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lunar Month</label>
                        <select name="lunar_month_name" id="edit_month" class="form-select">
                            <option value="">Select Month</option>
                            @foreach($months as $key => $val)
                                <option value="{{ $key }}">{{ $val }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Vikram Samvat</label>
                        <input type="text" name="vikram_samvat" id="edit_samvat" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tithi Number</label>
                        <input type="number" name="tithi_number" id="edit_tithi_num" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tithi (Sunrise)</label>
                        <select name="tithi" id="edit_tithi" class="form-select">
                            <option value="">Select Tithi</option>
                            @foreach($tithis as $key => $val)
                                <option value="{{ $key }}">{{ $val }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tithi (12 Noon)</label>
                        <select name="tithi_two" id="edit_tithi_two" class="form-select">
                            <option value="">Select Tithi</option>
                            @foreach($tithis as $key => $val)
                                <option value="{{ $key }}">{{ $val }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Paksha</label>
                        <select name="paksha" id="edit_paksha" class="form-select">
                            <option value="Shukla">Shukla</option>
                            <option value="Krishna">Krishna</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Is Pakhi?</label>
                        <select name="is_pakhi" id="edit_is_pakhi" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Today's Event / Special Info</label>
                        <textarea name="today_event" id="edit_today_event" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Update Record</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(data) {
        if (!data) return;
        
        const form = document.getElementById('editForm');
        form.action = "{{ url('/daily-panchang/update') }}/" + data.id;
        
        document.getElementById('edit_date').value = data.date ? data.date.split('T')[0] : '';
        document.getElementById('edit_month').value = data.lunar_month_name || '';
        document.getElementById('edit_samvat').value = data.vikram_samvat || '';
        document.getElementById('edit_tithi_num').value = data.tithi_number || '';
        document.getElementById('edit_tithi').value = data.tithi || '';
        document.getElementById('edit_tithi_two').value = data.tithi_two || '';
        document.getElementById('edit_paksha').value = data.paksha || 'Shukla';
        document.getElementById('edit_is_pakhi').value = data.is_pakhi ? 1 : 0;
        document.getElementById('edit_today_event').value = data.today_event || '';
        
        const editModal = new bootstrap.Modal(document.getElementById('editPanchangModal'));
        editModal.show();
    }

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.flash-alert').forEach(el => el.remove());
    }, 5000);
</script>

@endsection
