@extends('includes.layouts.mahila_Samiti')

@section('title', 'महिला समिति Dashboard')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap');

    * { box-sizing: border-box; }

    .ms-dashboard {
        font-family: 'Inter', sans-serif;
        background: #f0f2f8;
        min-height: 100vh;
        padding-bottom: 80px;
    }

    /* ===== HERO BANNER ===== */
    .ms-hero {
        background: linear-gradient(135deg, #c94b4b 0%, #ee0979 40%, #ff6a00 100%);
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(238,9,121,0.25);
    }
    .ms-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.07);
    }
    .ms-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; left: 30px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .ms-hero-inner {
        position: relative; z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .ms-hero-left h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 6px 0;
        text-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .ms-hero-left p {
        color: rgba(255,255,255,0.85);
        font-size: 0.95rem;
        margin: 0;
    }
    .ms-hero-badge {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 50px;
        padding: 8px 20px;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }
    .ms-hero-badge i { font-size: 1.1rem; }

    /* ===== STAT CARDS ===== */
    .ms-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 30px;
    }
    .ms-stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        cursor: default;
    }
    .ms-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.12);
    }
    .ms-stat-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .ms-stat-icon.pink    { background: linear-gradient(135deg,#ee0979,#ff6a00); color:#fff; }
    .ms-stat-icon.purple  { background: linear-gradient(135deg,#7c3aed,#a855f7); color:#fff; }
    .ms-stat-icon.teal    { background: linear-gradient(135deg,#0d9488,#14b8a6); color:#fff; }
    .ms-stat-icon.orange  { background: linear-gradient(135deg,#ea580c,#f97316); color:#fff; }
    .ms-stat-icon.blue    { background: linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; }
    .ms-stat-icon.green   { background: linear-gradient(135deg,#16a34a,#22c55e); color:#fff; }

    .ms-stat-info .label {
        font-size: 0.78rem;
        font-weight: 500;
        color: #8b92a9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .ms-stat-info .value {
        font-family: 'Poppins', sans-serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: #1a1f36;
        line-height: 1;
    }
    .ms-stat-info .sub {
        font-size: 0.75rem;
        color: #a0a8c0;
        margin-top: 3px;
    }

    /* ===== SECTION TITLE ===== */
    .ms-section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1f36;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ms-section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: linear-gradient(to right, rgba(238,9,121,0.15), transparent);
        border-radius: 2px;
    }

    /* ===== QUICK LINKS GRID ===== */
    .ms-quicklinks {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }
    .ms-ql-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px 16px;
        text-align: center;
        text-decoration: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: all 0.25s ease;
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    .ms-ql-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(238,9,121,0.15);
        border-color: rgba(238,9,121,0.3);
        text-decoration: none;
    }
    .ms-ql-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .ms-ql-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #3a3f58;
        line-height: 1.3;
        text-align: center;
    }

    /* gradient variants for quick links */
    .ql-g1  { background: linear-gradient(135deg,#ee0979,#ff6a00); color:#fff; }
    .ql-g2  { background: linear-gradient(135deg,#7c3aed,#a855f7); color:#fff; }
    .ql-g3  { background: linear-gradient(135deg,#0d9488,#14b8a6); color:#fff; }
    .ql-g4  { background: linear-gradient(135deg,#ea580c,#f97316); color:#fff; }
    .ql-g5  { background: linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; }
    .ql-g6  { background: linear-gradient(135deg,#16a34a,#22c55e); color:#fff; }
    .ql-g7  { background: linear-gradient(135deg,#db2777,#ec4899); color:#fff; }
    .ql-g8  { background: linear-gradient(135deg,#0369a1,#0ea5e9); color:#fff; }
    .ql-g9  { background: linear-gradient(135deg,#ca8a04,#eab308); color:#fff; }
    .ql-g10 { background: linear-gradient(135deg,#7c3aed,#6366f1); color:#fff; }
    .ql-g11 { background: linear-gradient(135deg,#be185d,#e879f9); color:#fff; }
    .ql-g12 { background: linear-gradient(135deg,#c94b4b,#ee0979); color:#fff; }

    /* ===== INFO BANNER ===== */
    .ms-info-banner {
        background: linear-gradient(135deg, #fdf2f8, #fce7f3);
        border: 1px solid #fbcfe8;
        border-left: 5px solid #ee0979;
        border-radius: 14px;
        padding: 20px 24px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 30px;
    }
    .ms-info-banner i.icon {
        font-size: 2rem;
        color: #ee0979;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .ms-info-banner h6 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #9d174d;
        margin-bottom: 4px;
        font-size: 0.95rem;
    }
    .ms-info-banner p {
        color: #6b2d5e;
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.6;
    }

    /* ===== TWO COL LAYOUT ===== */
    .ms-two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    @media (max-width: 768px) {
        .ms-two-col { grid-template-columns: 1fr; }
        .ms-hero { padding: 24px 20px; }
        .ms-hero-left h1 { font-size: 1.25rem; }
        .ms-stats { grid-template-columns: repeat(2, 1fr); }
        .ms-quicklinks { grid-template-columns: repeat(3, 1fr); }
    }

    /* ===== ACTIVITY CARD ===== */
    .ms-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    }
    .ms-card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1f36;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ms-card-title i { color: #ee0979; }

    .ms-module-list {
        list-style: none;
        padding: 0; margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .ms-module-list li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        background: #f8f9fc;
        text-decoration: none;
        color: #3a3f58;
        font-size: 0.87rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .ms-module-list li a:hover {
        background: linear-gradient(135deg,#ee0979,#ff6a00);
        color: #fff;
        transform: translateX(4px);
    }
    .ms-module-list li a i {
        width: 20px;
        text-align: center;
        font-size: 1rem;
    }

    /* ===== WELCOME TEXT ===== */
    .ms-welcome-text {
        text-align: center;
        padding: 10px 0 20px;
    }
    .ms-welcome-text h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        color: #6b7280;
        font-weight: 500;
    }
    .ms-welcome-text span { color: #ee0979; font-weight: 700; }
</style>

<div class="ms-dashboard">

    {{-- ===== HERO BANNER ===== --}}
    <div class="ms-hero">
        <div class="ms-hero-inner">
            <div class="ms-hero-left">
                <h1>🌸 महिला समिति Dashboard</h1>
                <p>श्री अखिल भारतवर्षीय साधुमार्गी जैन महिला समिति — Admin Panel</p>
            </div>
            <div class="ms-hero-badge">
                <i class="bi bi-calendar3"></i>
                <span id="heroDate"></span>
            </div>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="ms-stats" id="statsRow">
        <div class="ms-stat-card">
            <div class="ms-stat-icon pink"><i class="bi bi-people-fill"></i></div>
            <div class="ms-stat-info">
                <div class="label">पदाधिकारी (PST)</div>
                <div class="value" id="statPst">—</div>
                <div class="sub">कुल पद</div>
            </div>
        </div>
        <div class="ms-stat-card">
            <div class="ms-stat-icon purple"><i class="bi bi-person-check-fill"></i></div>
            <div class="ms-stat-info">
                <div class="label">पूर्व अध्यक्ष</div>
                <div class="value" id="statExPres">—</div>
                <div class="sub">रिकॉर्ड</div>
            </div>
        </div>
        <div class="ms-stat-card">
            <div class="ms-stat-icon teal"><i class="bi bi-megaphone-fill"></i></div>
            <div class="ms-stat-info">
                <div class="label">Events</div>
                <div class="value" id="statEvents">—</div>
                <div class="sub">कुल आयोजन</div>
            </div>
        </div>
        <div class="ms-stat-card">
            <div class="ms-stat-icon orange"><i class="bi bi-images"></i></div>
            <div class="ms-stat-info">
                <div class="label">Slider Photos</div>
                <div class="value" id="statSlider">—</div>
                <div class="sub">Slider Images</div>
            </div>
        </div>
        <div class="ms-stat-card">
            <div class="ms-stat-icon blue"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div class="ms-stat-info">
                <div class="label">आवेदन पत्र</div>
                <div class="value" id="statAavedan">—</div>
                <div class="sub">Documents</div>
            </div>
        </div>
        <div class="ms-stat-card">
            <div class="ms-stat-icon green"><i class="bi bi-diagram-3-fill"></i></div>
            <div class="ms-stat-info">
                <div class="label">VP/SEC सदस्य</div>
                <div class="value" id="statVpSec">—</div>
                <div class="sub">कार्यकारिणी</div>
            </div>
        </div>
    </div>

    {{-- ===== INFO BANNER ===== --}}
    <div class="ms-info-banner">
        <i class="bi bi-stars icon"></i>
        <div>
            <h6>महिला समिति Admin Panel में आपका स्वागत है 🌸</h6>
            <p>इस पैनल से आप पदाधिकारी, कार्यकारिणी सदस्य, Events, Photos, Slider और सभी महत्वपूर्ण जानकारियाँ आसानी से प्रबंधित कर सकते हैं। किसी भी module को नीचे दिए Quick Links से एक क्लिक में खोलें।</p>
        </div>
    </div>

    {{-- ===== QUICK LINKS ===== --}}
    <div class="ms-section-title">
        <i class="bi bi-grid-3x3-gap-fill" style="color:#ee0979;"></i>
        Quick Access — सभी Modules
    </div>

    <div class="ms-quicklinks">
        <a href="{{ url('/mahila_pst') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g1"><i class="bi bi-person-video2"></i></div>
            <span class="ms-ql-label">पदाधिकारी (PST)</span>
        </a>
        <a href="{{ url('/mahila_ex_president') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g2"><i class="bi bi-person-check"></i></div>
            <span class="ms-ql-label">पूर्व अध्यक्ष</span>
        </a>
        <a href="{{ url('/mahila_vp_sec') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g3"><i class="bi bi-person-badge"></i></div>
            <span class="ms-ql-label">VP/SEC सदस्य</span>
        </a>
        <a href="{{ url('/mahila_ksm_members') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g4"><i class="bi bi-people-fill"></i></div>
            <span class="ms-ql-label">कार्यसमिति सदस्य</span>
        </a>
        <a href="{{ url('/mahila_pravarti_sanyojika') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g5"><i class="bi bi-diagram-3-fill"></i></div>
            <span class="ms-ql-label">प्रवर्ती संयोजक</span>
        </a>
        <a href="{{ url('/mahila_events') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g6"><i class="bi bi-megaphone-fill"></i></div>
            <span class="ms-ql-label">Events</span>
        </a>
        <a href="{{ url('/mahila_aavedan_patra') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g7"><i class="bi bi-file-earmark-text"></i></div>
            <span class="ms-ql-label">आवेदन पत्र</span>
        </a>
        <a href="{{ url('/mahila_prativedan') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g8"><i class="bi bi-file-earmark-richtext"></i></div>
            <span class="ms-ql-label">प्रतिवेदन</span>
        </a>
        <a href="{{ url('/mahila_description') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g9"><i class="bi bi-card-text"></i></div>
            <span class="ms-ql-label">Description</span>
        </a>
        <a href="{{ url('/mahila_slider') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g10"><i class="bi bi-images"></i></div>
            <span class="ms-ql-label">Mahila Slider</span>
        </a>
        <a href="{{ url('/photo_gallery_mahila_samiti') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g11"><i class="bi bi-camera-fill"></i></div>
            <span class="ms-ql-label">Photo Gallery</span>
        </a>
        <a href="{{ url('/send_notification-mahila_Samiti') }}" class="ms-ql-card">
            <div class="ms-ql-icon ql-g12"><i class="bi bi-bell-fill"></i></div>
            <span class="ms-ql-label">Notifications</span>
        </a>
    </div>

    {{-- ===== TWO COL: Karyakarini + Settings ===== --}}
    <div class="ms-two-col">

        <div class="ms-card">
            <div class="ms-card-title">
                <i class="bi bi-diagram-3-fill"></i> कार्यकारिणी Modules
            </div>
            <ul class="ms-module-list">
                <li><a href="{{ url('/mahila_pst') }}"><i class="bi bi-person-video2"></i> पदाधिकारी (PST)</a></li>
                <li><a href="{{ url('/mahila_ex_president') }}"><i class="bi bi-person-check"></i> पूर्व अध्यक्ष</a></li>
                <li><a href="{{ url('/mahila_vp_sec') }}"><i class="bi bi-person-badge"></i> VP/SEC सदस्य</a></li>
                <li><a href="{{ url('/mahila_pravarti_sanyojika') }}"><i class="bi bi-diagram-3-fill"></i> प्रवर्ती संयोजक</a></li>
                <li><a href="{{ url('/mahila_ksm_members') }}"><i class="bi bi-people-fill"></i> कार्यसमिति सदस्य</a></li>
            </ul>
        </div>

        <div class="ms-card">
            <div class="ms-card-title">
                <i class="bi bi-calendar-event-fill"></i> General Updates
            </div>
            <ul class="ms-module-list">
                <li><a href="{{ url('/mahila_events') }}"><i class="bi bi-megaphone-fill"></i> Events</a></li>
                <li><a href="{{ url('/mahila_aavedan_patra') }}"><i class="bi bi-file-earmark-text"></i> आवेदन पत्र</a></li>
                <li><a href="{{ url('/mahila_prativedan') }}"><i class="bi bi-file-earmark-richtext"></i> प्रतिवेदन</a></li>
                <li><a href="{{ url('/mahila_description') }}"><i class="bi bi-card-text"></i> Description</a></li>
                <li><a href="{{ url('/send_notification-mahila_Samiti') }}"><i class="bi bi-bell-fill"></i> Notifications भेजें</a></li>
            </ul>
        </div>

    </div>

</div>

<script>
    // Date display
    const d = new Date();
    const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('heroDate').textContent = d.toLocaleDateString('hi-IN', opts);

    // Load stats from APIs
    async function loadStat(url, elementId, field = null) {
        try {
            const res = await fetch(url);
            const data = await res.json();
            const el = document.getElementById(elementId);
            if (Array.isArray(data)) {
                el.textContent = data.length;
            } else if (field && data[field] !== undefined) {
                el.textContent = data[field];
            } else {
                el.textContent = '—';
            }
        } catch {
            document.getElementById(elementId).textContent = '—';
        }
    }

    loadStat('/api/mahila-pst', 'statPst');
    loadStat('/api/mahila-ex-prsident', 'statExPres');
    loadStat('/api/mahila-events', 'statEvents');
    loadStat('/api/mahila-slider', 'statSlider');
    loadStat('/api/mahila-aavedan-patra', 'statAavedan');
    loadStat('/api/mahila_vp_sec', 'statVpSec');
</script>

@endsection
