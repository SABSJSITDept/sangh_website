@extends('includes.layouts.spf')

@section('content')
<div class="container py-4">

    <h4 class="mb-4 text-center text-white py-3 px-4 rounded-4"
        style="font-family: 'Bebas Neue', sans-serif; font-size: 28px; background: linear-gradient(to right, #36D1DC, #5B86E5); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        📊 SPF Dashboard
    </h4>

    <div class="row row-cols-1 row-cols-md-3 g-4">

        <!-- Card 1: SPF Members -->
        <div class="col">
            <div class="card h-100 border-0 shadow-lg rounded-4">
                <div class="card-body text-center" style="background: linear-gradient(to right, #667eea, #764ba2); color: white; border-radius: 1rem 1rem 0 0;">
                    <img src="{{ asset('images/spf_members.jpg') }}" alt="Members" class="mb-3" style="width: 60px; height: auto; border-radius: 8px;">
                    <h5 class="card-title fs-5">👥 SPF Members</h5>
                    <p class="card-text">View, Edit Or Add SPF Members</p>
                </div>
                <div class="card-footer bg-white text-center rounded-bottom-4 py-3">
                    <a href="{{ url('/spf/members') }}" class="btn btn-outline-primary fw-semibold">👁️ View</a>
                </div>
            </div>
        </div>

        <!-- Card 2: SPF Activities -->
        <div class="col">
            <div class="card h-100 border-0 shadow-lg rounded-4">
                <div class="card-body text-center" style="background: linear-gradient(to right, #ff9966, #ff5e62); color: white; border-radius: 1rem 1rem 0 0;">
                    <img src="{{ asset('images/spf_activities.jpg') }}" alt="Activities" class="mb-3" style="width: 60px; height: auto; border-radius: 8px;">
                    <h5 class="card-title fs-5">📅 SPF Activities</h5>
                    <p class="card-text">Manage SPF Events & Activities</p>
                </div>
                <div class="card-footer bg-white text-center rounded-bottom-4 py-3">
                    <a href="{{ url('/spf/activities') }}" class="btn btn-outline-danger fw-semibold">👁️ View</a>
                </div>
            </div>
        </div>

        <!-- Card 3: SPF Gallery -->
        <div class="col">
            <div class="card h-100 border-0 shadow-lg rounded-4">
                <div class="card-body text-center" style="background: linear-gradient(to right, #56ccf2, #2f80ed); color: white; border-radius: 1rem 1rem 0 0;">
                    <img src="{{ asset('images/spf_gallery.jpg') }}" alt="Gallery" class="mb-3" style="width: 60px; height: auto; border-radius: 8px;">
                    <h5 class="card-title fs-5">🖼️ SPF Gallery</h5>
                    <p class="card-text">View, Edit Or Add Photos</p>
                </div>
                <div class="card-footer bg-white text-center rounded-bottom-4 py-3">
                    <a href="{{ url('/spf/gallery') }}" class="btn btn-outline-info fw-semibold">📂 Go</a>
                </div>
            </div>
        </div>

        <!-- Card 4: SPF News -->
        <div class="col">
            <div class="card h-100 border-0 shadow-lg rounded-4">
                <div class="card-body text-center" style="background: linear-gradient(to right, #f093fb, #f5576c); color: white; border-radius: 1rem 1rem 0 0;">
                    <img src="{{ asset('images/spf_news.jpg') }}" alt="News" class="mb-3" style="width: 60px; height: auto; border-radius: 8px;">
                    <h5 class="card-title fs-5">📰 SPF News</h5>
                    <p class="card-text">View, Edit Or Add News</p>
                </div>
                <div class="card-footer bg-white text-center rounded-bottom-4 py-3">
                    <a href="{{ url('/spf/news') }}" class="btn btn-outline-danger fw-semibold">👁️ View</a>
                </div>
            </div>
        </div>

        <!-- Card 5: SPF Announcements -->
        <div class="col">
            <div class="card h-100 border-0 shadow-lg rounded-4">
                <div class="card-body text-center" style="background: linear-gradient(to right, #4facfe, #00f2fe); color: white; border-radius: 1rem 1rem 0 0;">
                    <img src="{{ asset('images/spf_announcements.jpg') }}" alt="Announcements" class="mb-3" style="width: 60px; height: auto; border-radius: 8px;">
                    <h5 class="card-title fs-5">📢 Announcements</h5>
                    <p class="card-text">Manage SPF Announcements</p>
                </div>
                <div class="card-footer bg-white text-center rounded-bottom-4 py-3">
                    <a href="{{ url('/spf/announcements') }}" class="btn btn-outline-info fw-semibold">👁️ View</a>
                </div>
            </div>
        </div>

        <!-- Card 6: SPF Reports -->
        <div class="col">
            <div class="card h-100 border-0 shadow-lg rounded-4">
                <div class="card-body text-center" style="background: linear-gradient(to right, #fa709a, #fee140); color: white; border-radius: 1rem 1rem 0 0;">
                    <img src="{{ asset('images/spf_reports.jpg') }}" alt="Reports" class="mb-3" style="width: 60px; height: auto; border-radius: 8px;">
                    <h5 class="card-title fs-5">📊 Reports</h5>
                    <p class="card-text">View SPF Reports & Analytics</p>
                </div>
                <div class="card-footer bg-white text-center rounded-bottom-4 py-3">
                    <a href="{{ url('/spf/reports') }}" class="btn btn-outline-warning fw-semibold">👁️ View</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
