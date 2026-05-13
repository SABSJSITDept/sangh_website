@extends('includes.layouts.yuva_sangh')

@section('title', 'Yuva Sangh Notifications History')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Yuva Notifications</h2>
                <p class="text-muted small mb-0">Review the history of notifications sent to the community.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-bell-history text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 1.25rem;">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-600 small text-uppercase text-muted">Filter by Year</label>
                    <select id="filterYear" class="form-select rounded-3 border-light bg-light shadow-none">
                        <option value="">All Years</option>
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-600 small text-uppercase text-muted">Filter by Month</label>
                    <select id="filterMonth" class="form-select rounded-3 border-light bg-light shadow-none">
                        <option value="">All Months</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}">{{ date("F", mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="btnFetchYearMonth" class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm">
                        <i class="bi bi-funnel me-2"></i> Apply Filters
                    </button>
                </div>
                <div class="col-md-3">
                    <button id="btnReset" class="btn btn-light w-100 py-2 rounded-3 fw-bold border" type="button">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="fw-bold outfit-font mb-0">Notification Log</h5>
                <span id="logCount" class="badge bg-light text-dark border rounded-pill px-3 py-2">Loading...</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 border-0" style="width:60px">#</th>
                            <th class="py-3 border-0">Subject</th>
                            <th class="py-3 border-0" style="max-width: 400px;">Message Content</th>
                            <th class="py-3 border-0 text-center">Media</th>
                            <th class="pe-4 py-3 border-0 text-end">Sent Date</th>
                        </tr>
                    </thead>
                    <tbody id="notificationTable">
                        <tr><td colspan="5" class="text-center py-5"><div class="spinner-border text-primary spinner-border-sm"></div></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("notificationTable");
    const yearSelect = document.getElementById("filterYear");
    const monthSelect = document.getElementById("filterMonth");
    const logCount = document.getElementById("logCount");

    function renderTable(data) {
        tableBody.innerHTML = "";
        logCount.textContent = `${data.length} Records`;
        
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted">No notifications found for the selected period.</td></tr>`;
        } else {
            data.forEach((n, i) => {
                const date = new Date(n.created_at);
                const formattedDate = date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                
                tableBody.innerHTML += `
                    <tr class="transition-all">
                        <td class="ps-4 small text-muted fw-bold">${i+1}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-indigo-subtle text-indigo rounded-pill small border-indigo">${n.group}</span>
                                <h6 class="mb-0 fw-bold outfit-font text-dark">${n.title}</h6>
                            </div>
                        </td>
                        <td class="py-3">
                            <p class="mb-0 small text-muted text-wrap" style="max-width: 450px;">${n.body}</p>
                        </td>
                        <td class="py-3 text-center">
                            ${n.image 
                                ? `<img src="${n.image}" class="rounded-3 border shadow-sm" style="height:45px; width:45px; object-fit:cover;"/>` 
                                : '<span class="text-light-emphasis small"><i class="bi bi-image-fill"></i> —</span>'}
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="small fw-bold text-dark">${formattedDate}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">${formattedTime}</div>
                        </td>
                    </tr>`;
            });
        }
    }

    function loadLast30Days() {
        fetch(`/api/notifications/filter?group=Yuva Sangh`)
            .then(res => res.json())
            .then(data => {
                const last30 = data.filter(n => new Date(n.created_at) >= new Date(Date.now() - 30*24*60*60*1000));
                renderTable(last30);
            });
    }

    loadLast30Days();

    document.getElementById("btnFetchYearMonth").addEventListener("click", () => {
        const year = yearSelect.value;
        const month = monthSelect.value;
        const btn = document.getElementById("btnFetchYearMonth");
        const originalHtml = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        fetch(`/api/notifications/filter?group=Yuva Sangh&year=${year}&month=${month}`)
            .then(res => res.json())
            .then(data => {
                renderTable(data);
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
    });

    document.getElementById("btnReset").addEventListener("click", () => {
        yearSelect.value = "";
        monthSelect.value = "";
        loadLast30Days();
    });
});
</script>

<style>
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .text-indigo { color: #6366f1; }
    .bg-indigo-subtle { background-color: #e0e7ff; }
    .border-indigo { border: 1px solid #c7d2fe !important; }
</style>
@endsection