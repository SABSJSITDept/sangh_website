@extends('includes.layouts.super_admin')

@section('title', 'Member App Open Logs | Super Admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
        <div>
            <h2 class="fw-bold mb-1">Member App Open Logs</h2>
            <p class="text-muted mb-0">Track and monitor in real-time when registered members open the mobile application.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <!-- Auto Refresh Toggle -->
            <div class="glass-card px-3 py-2 d-flex align-items-center gap-2">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="autoRefreshSwitch">
                    <label class="form-check-label fw-medium text-dark small" for="autoRefreshSwitch">Auto Refresh (10s)</label>
                </div>
            </div>
            
            <!-- Date Filter -->
            <div class="glass-card px-3 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-calendar-event text-primary"></i>
                <input type="date" id="logDateFilter" class="border-0 bg-transparent fw-medium text-dark outline-none small" value="{{ date('Y-m-d') }}" style="outline: none;">
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="glass-card p-4 border-start border-4 border-primary h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Total App Opens</p>
                        <h2 class="fw-bold mb-0 text-primary" id="statTotalOpens">0</h2>
                        <span class="text-muted small">For selected date</span>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-phone-vibrate text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="glass-card p-4 border-start border-4 border-success h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Unique Active Members</p>
                        <h2 class="fw-bold mb-0 text-success" id="statUniqueMembers">0</h2>
                        <span class="text-muted small">For selected date</span>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-people-fill text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="glass-card p-4 border-start border-4 border-info h-100">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="w-100">
                        <p class="text-muted small text-uppercase fw-bold mb-2">Search / Filter Logs</p>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 border-opacity-10 rounded-start-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="logSearchInput" class="form-control border-start-0 border-opacity-10 rounded-end-3 py-2" placeholder="Search by member ID, name, or mobile...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="glass-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0 text-dark">Logs Table</h5>
            <button id="refreshLogsBtn" class="btn btn-sm btn-light rounded-pill px-3 py-1 fw-medium border-0 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-clockwise" id="refreshIcon"></i> Refresh Data
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0" id="logsTable">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 px-3 py-3 text-muted text-uppercase small fw-bold">#</th>
                        <th class="border-0 px-3 py-3 text-muted text-uppercase small fw-bold">Member ID</th>
                        <th class="border-0 px-3 py-3 text-muted text-uppercase small fw-bold">Member Name</th>
                        <th class="border-0 px-3 py-3 text-muted text-uppercase small fw-bold">Mobile</th>
                        <th class="border-0 px-3 py-3 text-muted text-uppercase small fw-bold">Timestamp</th>
                    </tr>
                </thead>
                <tbody id="logsTableBody" class="border-top-0">
                    <!-- Loading state by default -->
                    <tr id="tableLoadingRow">
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            <span>Loading logs...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        box-shadow: 0 4px 15px -3px rgba(0,0,0,0.05);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(245, 158, 11, 0.03) !important;
        transition: background-color 0.2s ease;
    }
    .table-light {
        --bs-table-bg: #f8fafc;
    }
    tbody tr {
        border-bottom: 1px solid #f1f5f9;
    }
    tbody tr:last-child {
        border-bottom: none;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.7;
    }
    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
    
    .spin {
        animation: spinner 0.8s linear infinite;
        display: inline-block;
    }
    @keyframes spinner {
        to {transform: rotate(360deg);}
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateFilter = document.getElementById('logDateFilter');
        const searchInput = document.getElementById('logSearchInput');
        const autoRefreshSwitch = document.getElementById('autoRefreshSwitch');
        const refreshBtn = document.getElementById('refreshLogsBtn');
        const refreshIcon = document.getElementById('refreshIcon');
        
        const logsTableBody = document.getElementById('logsTableBody');
        const loadingRow = document.getElementById('tableLoadingRow');
        
        const statTotalOpens = document.getElementById('statTotalOpens');
        const statUniqueMembers = document.getElementById('statUniqueMembers');
        
        let allLogs = [];
        let refreshInterval = null;

        // Fetch logs from API
        async function fetchLogs() {
            refreshIcon.classList.add('spin');
            refreshBtn.disabled = true;
            
            const selectedDate = dateFilter.value;
            
            try {
                const response = await fetch(`/api/member-app-opens/today?date=${selectedDate}`);
                const result = await response.json();
                
                if (result.success) {
                    allLogs = result.data || [];
                    statTotalOpens.textContent = result.total_opens || 0;
                    statUniqueMembers.textContent = result.unique_members_count || 0;
                    
                    renderLogs();
                } else {
                    showError(result.message || 'Failed to load logs.');
                }
            } catch (error) {
                console.error('Error fetching logs:', error);
                showError('Server connection error. Please try again.');
            } finally {
                refreshIcon.classList.remove('spin');
                refreshBtn.disabled = false;
            }
        }

        // Render logs in the table
        function renderLogs() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            // Filter logs locally based on search input
            const filteredLogs = allLogs.filter(log => {
                if (!searchTerm) return true;
                
                const memberId = String(log.member_id);
                const firstName = log.member?.first_name ? log.member.first_name.toLowerCase() : '';
                const lastName = log.member?.last_name ? log.member.last_name.toLowerCase() : '';
                const fullName = `${firstName} ${lastName}`;
                const mobile = log.member?.mobile ? String(log.member.mobile) : '';
                
                return memberId.includes(searchTerm) || 
                       fullName.includes(searchTerm) || 
                       mobile.includes(searchTerm);
            });

            if (filteredLogs.length === 0) {
                logsTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                            <span>No logs found.</span>
                        </td>
                    </tr>
                `;
                return;
            }

            logsTableBody.innerHTML = '';
            filteredLogs.forEach((log, index) => {
                const name = log.member ? `${log.member.first_name} ${log.member.last_name}` : '<span class="text-danger small fw-semibold">Unknown Member</span>';
                const mobile = log.member?.mobile ? log.member.mobile : '-';
                
                // Format timestamp
                const dateObj = new Date(log.created_at);
                const formattedTime = dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
                const formattedDate = dateObj.toLocaleDateString([], { day: '2-digit', month: 'short', year: 'numeric' });
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-3 py-3 text-muted fw-medium">${index + 1}</td>
                    <td class="px-3 py-3 fw-semibold text-dark">${log.member_id}</td>
                    <td class="px-3 py-3">${name}</td>
                    <td class="px-3 py-3 text-muted">${mobile}</td>
                    <td class="px-3 py-3"><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 py-2 px-3 rounded-pill fw-medium"><i class="bi bi-clock me-1"></i> ${formattedDate} ${formattedTime}</span></td>
                `;
                logsTableBody.appendChild(row);
            });
        }

        // Show error message in table
        function showError(message) {
            logsTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5 text-danger">
                        <i class="bi bi-exclamation-triangle-fill fs-3 d-block mb-2 text-danger text-opacity-50"></i>
                        <span>${message}</span>
                    </td>
                </tr>
            `;
        }

        // Event Listeners
        dateFilter.addEventListener('change', fetchLogs);
        searchInput.addEventListener('input', renderLogs);
        refreshBtn.addEventListener('click', fetchLogs);

        // Auto Refresh
        autoRefreshSwitch.addEventListener('change', function() {
            if (this.checked) {
                fetchLogs(); // refresh immediately
                refreshInterval = setInterval(fetchLogs, 10000);
            } else {
                clearInterval(refreshInterval);
                refreshInterval = null;
            }
        });

        // Initial Load
        fetchLogs();
    });
</script>
@endsection
