@extends('includes.layouts.shree_sangh')

@section('page-title', 'Shree Sangh Dashboard')

@section('content')
    <style>
        .dashboard-card {
            border: none;
            border-radius: 1.2rem;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: #ffffff;
            overflow: hidden;
            position: relative;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .dashboard-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.25);
        }

        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1.2rem;
            padding: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .menu-card {
            cursor: pointer;
            height: 100%;
        }

        .menu-card .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 180px;
        }

        .menu-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .menu-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3561;
            margin-bottom: 0.5rem;
        }

        .menu-desc {
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3561;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .vichar-text {
            font-size: 1.2rem;
            line-height: 1.8;
            color: white;
            font-style: italic;
            position: relative;
            z-index: 1;
        }

        .vihar-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 1.2rem;
            padding: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3);
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .badge-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.9rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-light border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 1rem;">
                    <h4 class="mb-0" style="color: #2d3561;">
                        <i class="bi bi-speedometer2"></i> नमस्ते! Welcome to Shree Sangh Dashboard
                    </h4>
                </div>
            </div>
        </div>

        <!-- Today's Information Row -->
        <div class="row mb-4">
            <!-- Aaj Ka Vichar -->
            <div class="col-md-6 mb-4">
                <h5 class="section-title"><i class="bi bi-lightbulb"></i> आज का विचार</h5>
                <div class="info-card">
                    <div id="vicharContent" class="vichar-text">
                        <i class="bi bi-quote"></i>
                        <span>Loading...</span>
                    </div>
                    <div class="mt-3">
                        <span class="badge-custom">
                            <i class="bi bi-calendar3"></i> {{ date('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Vihar Jankari -->
            <div class="col-md-6 mb-4">
                <h5 class="section-title"><i class="bi bi-geo-alt"></i> आज की विहार जानकारी</h5>
                <div class="vihar-info">
                    <div id="viharContent">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-map" style="font-size: 2rem; margin-right: 1rem;"></i>
                            <div>
                                <small class="opacity-75" id="viharDate">{{ date('d M Y') }}</small>
                            </div>
                        </div>
                        <p class="mb-0" id="viharDetails" style="font-size: 1rem; line-height: 1.6;">
                            Loading vihar information...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Menu Cards -->
        <h5 class="section-title"><i class="bi bi-grid-3x3-gap"></i> Dashboard Menu</h5>

        <div class="row g-4">
            <!-- General Updates -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card menu-card" data-bs-toggle="modal" data-bs-target="#generalUpdatesModal">
                    <div class="card-body">
                        <div class="menu-icon"><i class="bi bi-calendar-day"></i></div>
                        <div class="menu-title">General Updates</div>
                        <div class="menu-desc">Daily updates & news</div>
                    </div>
                </div>
            </div>

            <!-- Karyakarini -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card menu-card" data-bs-toggle="modal" data-bs-target="#karyakariniModal">
                    <div class="card-body">
                        <div class="menu-icon"><i class="bi bi-diagram-3"></i></div>
                        <div class="menu-title">कार्यकारिणी</div>
                        <div class="menu-desc">Committee management</div>
                    </div>
                </div>
            </div>

            <!-- Sangh Pravartiya -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card menu-card" data-bs-toggle="modal" data-bs-target="#sanghPravartiyaModal">
                    <div class="card-body">
                        <div class="menu-icon"><i class="bi bi-people"></i></div>
                        <div class="menu-title">संघ प्रवृत्तियाँ</div>
                        <div class="menu-desc">Activities & programs</div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card menu-card" data-bs-toggle="modal" data-bs-target="#photoGalleryModal">
                    <div class="card-body">
                        <div class="menu-icon"><i class="bi bi-images"></i></div>
                        <div class="menu-title">Photo Gallery</div>
                        <div class="menu-desc">Manage photos & sliders</div>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card menu-card"
                    onclick="window.location.href='{{ url('/send_notification-shree_sangh') }}'">
                    <div class="card-body">
                        <div class="menu-icon"><i class="bi bi-bell"></i></div>
                        <div class="menu-title">Notifications</div>
                        <div class="menu-desc">Send & view alerts</div>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card menu-card"
                    onclick="window.location.href='{{ url('/change-password_shree_sangh') }}'">
                    <div class="card-body">
                        <div class="menu-icon"><i class="bi bi-shield-lock"></i></div>
                        <div class="menu-title">Security</div>
                        <div class="menu-desc">Change password</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Modals -->

        <!-- General Updates Modal -->
        <div class="modal fade" id="generalUpdatesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 1rem; border: none;">
                    <div class="modal-header"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem 1rem 0 0;">
                        <h5 class="modal-title"><i class="bi bi-calendar-day"></i> General Updates</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="list-group list-group-flush">
                            <a href="{{ url('/daily-thoughts') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-lightbulb me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>आज का विचार</span>
                            </a>
                            <a href="{{ url('/dashboard/vihar-sewa') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-geo-alt me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>विहार जानकारी</span>
                            </a>
                            <a href="{{ url('/news') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-megaphone me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>NEWS</span>
                            </a>
                            <a href="{{ url('/shivir') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-calendar-event me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>शिविर</span>
                            </a>
                            <a href="{{ url('/aavedan_patra') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem;">
                                <i class="bi bi-file-earmark-text me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>आवेदन पत्र</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Karyakarini Modal -->
        <div class="modal fade" id="karyakariniModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 1rem; border: none;">
                    <div class="modal-header"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem 1rem 0 0;">
                        <h5 class="modal-title"><i class="bi bi-diagram-3"></i> कार्यकारिणी</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('karyakarini.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-house-door me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>HOME</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/shree-sangh/ex-president') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-person-check me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>पूर्व अध्यक्ष</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/shree-sangh/karyakarini/pst') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-person-video2 me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>PST</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/vp-sec') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-person-badge me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>VP/SEC सदस्य</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.it_cell') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-cpu me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>IT-CELL सदस्य</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/pravarti-sanyojak') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-diagram-3-fill me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>प्रवर्ती संयोजक</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/karyasamiti-sadasya') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-people-fill me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>कार्यसमिति सदस्य</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/samta_jan_kalyan_pranayash') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-activity me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                    <span>समता जन कल्याण</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sangh Pravartiya Modal -->
        <div class="modal fade" id="sanghPravartiyaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 1rem; border: none;">
                    <div class="modal-header"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem 1rem 0 0;">
                        <h5 class="modal-title"><i class="bi bi-people"></i> संघ प्रवृत्तियाँ</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('dharmik_pravartiya') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-person me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>धार्मिक प्रवर्तियाँ</span>
                            </a>
                            <a href="{{ route('jsp.dashboard') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem;">
                                <i class="bi bi-person-plus me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>JSP</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Gallery Modal -->
        <div class="modal fade" id="photoGalleryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 1rem; border: none;">
                    <div class="modal-header"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem 1rem 0 0;">
                        <h5 class="modal-title"><i class="bi bi-images"></i> Photo Gallery</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="list-group list-group-flush">
                            <a href="{{ url('/photo_gallery') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-cloud-upload-fill me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>Add Event Photos</span>
                            </a>
                            <a href="{{ url('/home_slider') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-collection-play-fill me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>Home Slider</span>
                            </a>
                            <a href="{{ url('/mobile_slider') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                style="border-radius: 0.5rem;">
                                <i class="bi bi-phone-fill me-3" style="font-size: 1.5rem; color: #667eea;"></i>
                                <span>Mobile Slider</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>

            // Fetch Today's Vichar
            fetch('/api/latest-thought')
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch');
                    return res.json();
                })
                .then(data => {
                    if (data && data.thought) {
                        document.getElementById('vicharContent').innerHTML = `
                                                <i class="bi bi-quote"></i>
                                                <span>${data.thought}</span>
                                            `;
                    } else {
                        document.getElementById('vicharContent').innerHTML = `
                                                <i class="bi bi-quote"></i>
                                                <span>आज का विचार उपलब्ध नहीं है</span>
                                            `;
                    }
                })
                .catch(err => {
                    console.error('Error fetching vichar:', err);
                    document.getElementById('vicharContent').innerHTML = `
                                            <i class="bi bi-quote"></i>
                                            <span>आज का विचार उपलब्ध नहीं है</span>
                                        `;
                });

            // Fetch Today's Vihar Info
            fetch('/api/vihar/latest')
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch');
                    return res.json();
                })
                .then(data => {
                    if (data && data.location) {
                        document.getElementById('viharDate').textContent = data.formatted_date || '{{ date("d M Y") }}';
                        document.getElementById('viharDetails').innerHTML = `
                                                <strong>आदि ठाणा:</strong> ${data.aadi_thana || 'N/A'}<br>
                                                <strong>स्थान:</strong> ${data.location || 'N/A'}
                                            `;
                    } else {
                        document.getElementById('viharLocation').textContent = 'विहार जानकारी उपलब्ध नहीं है';
                        document.getElementById('viharDetails').textContent = '';
                    }
                })
                .catch(err => {
                    console.error('Error fetching vihar:', err);
                    document.getElementById('viharLocation').textContent = 'विहार जानकारी उपलब्ध नहीं है';
                    document.getElementById('viharDetails').textContent = '';
                });
        </script>
@endsection