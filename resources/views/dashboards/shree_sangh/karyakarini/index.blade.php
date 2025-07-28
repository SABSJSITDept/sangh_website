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
            <div class="card h-100 border-0 shadow-lg rounded-4" style="background: linear-gradient(to right, #667eea, #764ba2); color: white;">
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
            <div class="card h-100 border-0 shadow-lg rounded-4" style="background: linear-gradient(to right, #ff9966, #ff5e62); color: white;">
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
    <div class="card h-100 border-0 shadow-lg rounded-4" style="background: linear-gradient(to right, #56ccf2, #2f80ed); color: white;">
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

    </div>
</div>
@endsection
