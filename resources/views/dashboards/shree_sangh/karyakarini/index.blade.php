@extends('includes.layouts.shree_sangh')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4 text-center text-white py-3 px-4 rounded-4"
            style="font-family: 'Bebas Neue', sans-serif; font-size: 28px; background: linear-gradient(to right, #667eea, #764ba2); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            📋 कार्यकारिणी - Dashboard
        </h4>

        <div class="row g-4">

            {{-- 🔸 पूर्व अध्यक्ष Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #667eea, #764ba2); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-user-tie me-2"></i>पूर्व अध्यक्ष (Ex Presidents)</h5>
                            <p class="card-text"> पूर्व अध्यक्षों की जानकारी जोड़ें, अपडेट करें या हटाएं।</p>
                        </div>
                        <a href="{{ route('ex_president.index') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ View Details
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔹 महामंत्री Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #ff9966, #ff5e62); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-user-secret me-2"></i>PST MEMBERS</h5>
                            <p class="card-text"> PST MEMBERS की जानकारी जोड़ें अपडेट करें या हटाएं।</p>
                        </div>
                        <a href="{{ route('pst.view') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ View Details
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔹 उदाहरण के लिए एक और Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #56ccf2, #2f80ed); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-users me-2"></i>VP/SEC सदस्य</h5>
                            <p class="card-text">VP/SEC सदस्यों की जानकारी जोड़ें अपडेट करें या हटाएं।</p>
                        </div>
                        <a href="{{ route('vp_sec.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ View Members
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔸 VP/SEC PDF Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #f093fb, #f5576c); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-file-pdf me-2"></i>VP/SEC PDF</h5>
                            <p class="card-text">VP/SEC से संबंधित PDF फाइलें अपलोड करें और प्रबंधित करें।</p>
                        </div>
                        <a href="{{ route('vp_sec_pdf.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ Manage PDFs
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔸 IT CELL PDF Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #4facfe, #00f2fe); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-file-pdf me-2"></i>IT CELL PDF</h5>
                            <p class="card-text">IT CELL से संबंधित PDF फाइलें अपलोड करें और प्रबंधित करें।</p>
                        </div>
                        <a href="{{ route('it_cell_pdf.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ Manage PDFs
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔸 Pravarti Sanyojak PDF Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #fa709a, #fee140); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-file-pdf me-2"></i>प्रवृत्ति संयोजक PDF</h5>
                            <p class="card-text">प्रवृत्ति संयोजक से संबंधित PDF फाइलें अपलोड करें और प्रबंधित करें।</p>
                        </div>
                        <a href="{{ route('pravarti_sanyojak_pdf.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ Manage PDFs
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔸 Sthayi Sampati PDF Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #11998e, #38ef7d); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-file-pdf me-2"></i>स्थायी संपत्ति PDF</h5>
                            <p class="card-text">स्थायी संपत्ति से संबंधित PDF फाइलें अपलोड करें और प्रबंधित करें।</p>
                        </div>
                        <a href="{{ route('sthayi_sampati_pdf.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ Manage PDFs
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔸 Sanyojan Mandal PDF Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #8e2de2, #4a00e0); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-file-pdf me-2"></i>संयोजन मंडल PDF</h5>
                            <p class="card-text">संयोजन मंडल से संबंधित PDF फाइलें अपलोड करें और प्रबंधित करें।</p>
                        </div>
                        <a href="{{ route('sanyojan_mandal_pdf.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ Manage PDFs
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔸 Samta Jan Kalyan PDF Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #0ba360, #3cba92); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-file-pdf me-2"></i>समता जन कल्याण PDF</h5>
                            <p class="card-text">समता जन कल्याण से संबंधित PDF फाइलें अपलोड करें और प्रबंधित करें।</p>
                        </div>
                        <a href="{{ route('samta_jan_kalyan_pdf.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ Manage PDFs
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🔸 KSM Members PDF Card --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4"
                    style="background: linear-gradient(to right, #ee0979, #ff6a00); color: white;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><i class="fas fa-file-pdf me-2"></i>KSM MEMBERS PDF</h5>
                            <p class="card-text">KSM Members से संबंधित PDF फाइलें अपलोड करें और प्रबंधित करें।</p>
                        </div>
                        <a href="{{ route('ksm_members_pdf.manage') }}" class="btn btn-light fw-semibold mt-3 w-100">
                            ➡️ Manage PDFs
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection