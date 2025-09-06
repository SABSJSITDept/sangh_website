@extends('includes.layouts.super_admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">🏛️ Welcome to Super Admin Dashboard</h1>

    <div class="dashboard-grid">
        <a href="{{ route('dashboard.sahitya') }}" class="dashboard-card">
            📚 Shramnopasak Dashboard
        </a>
        <a href="{{ route('dashboard.sahitya_publication') }}" class="dashboard-card">
            📖 Sahitya Publication
        </a>
        <a href="{{ route('dashboard.yuva_sangh') }}" class="dashboard-card">
            👥 Yuva Sangh
        </a>
        <a href="{{ route('dashboard.mahila_samiti') }}" class="dashboard-card">
            👩 Mahila Samiti
        </a>
        <a href="{{ route('dashboard.shree_sangh') }}" class="dashboard-card">
            🏵️ Shree Sangh
        </a>
    </div>
</div>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .dashboard-card {
        background: linear-gradient(135deg, #ff6a00, #ee0979);
        color: #fff;
        padding: 40px 20px;
        border-radius: 12px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        user-select: none;
        text-decoration: none; /* link underline हटाने के लिए */
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        color: #fff; /* hover पर भी text white रहे */
    }
</style>
@endsection
