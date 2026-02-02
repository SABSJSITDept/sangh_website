@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jsPDF for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <!-- SheetJS for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <style>
        .swal2-container {
            z-index: 9999 !important;
        }

        .form-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin: -1rem -1rem 1rem -1rem;
        }

        .required-field::after {
            content: " *";
            color: #ff4757;
            font-weight: bold;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: all 0.3s;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: scale(1.05);
            color: white;
        }

        .response-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .response-yes {
            background-color: #55efc4;
            color: #00b894;
        }

        .response-no {
            background-color: #fab1a0;
            color: #d63031;
        }

        .response-maybe {
            background-color: #ffeaa7;
            color: #fdcb6e;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .loading-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="container-fluid mt-4 px-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold">
                    <i class="fas fa-user-plus"></i> SPF Event Registration Management
                </h2>
                <p class="text-muted">Manage event registrations with ease</p>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="row mb-4" id="formContainer" style="display: none;">
            <div class="col-12">
                <form id="registrationForm" class="card p-4 shadow">
                    <div class="form-section">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Registration Form
                        </h5>
                    </div>

                    <input type="hidden" id="registration_id">

                    <div class="row g-3">
                        <!-- Registration Details -->
                        <div class="col-12">
                            <h6 class="text-primary fw-bold border-bottom pb-2">
                                <i class="fas fa-calendar-check"></i> Registration Details
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required-field">MID</label>
                            <input type="text" class="form-control" id="member_id" name="member_id"
                                placeholder="Enter member ID" required>
                            <small class="text-muted" id="memberInfo"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required-field">Select Event</label>
                            <select class="form-select" id="event_id" name="event_id" required>
                                <option value="">-- Choose Event --</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required-field">Response</label>
                            <select class="form-select" id="response" name="response" required>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                <option value="maybe">Maybe</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-gradient px-4" id="submitBtn">
                            <i class="fas fa-save"></i> Save Registration
                        </button>
                        <button type="button" class="btn btn-secondary px-4" id="cancelBtn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-list"></i> Event Registrations
                                </h5>
                                <div id="currentEventBadge" class="mt-1">
                                    <span class="badge bg-success"><i class="fas fa-star"></i> Latest Event:</span>
                                    <span id="currentEventName" class="text-dark fw-bold"></span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-gradient" id="addNewBtn">
                                    <i class="fas fa-plus"></i> Add New Registration
                                </button>
                                <button class="btn btn-outline-primary" id="viewOtherEventsBtn">
                                    <i class="fas fa-calendar-alt"></i> View Other Events
                                </button>
                            </div>
                        </div>
                        <div id="eventSelectContainer" class="mb-3" style="display:none;">
                            <div class="card bg-light">
                                <div class="card-body py-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold"><i class="fas fa-calendar-check"></i> Select
                                                Different Event</label>
                                            <select class="form-select" id="selectEventDropdown">
                                                <option value="">-- Show Latest Event (Default) --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-sm btn-outline-success" id="showLatestEventBtn">
                                                <i class="fas fa-undo"></i> Back to Latest Event
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Bar -->
                        <div class="card mb-3" id="filterCard">
                            <div class="card-header bg-light py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-filter text-primary"></i> Filters
                                    </h6>
                                    <button class="btn btn-sm btn-outline-secondary" id="clearFiltersBtn">
                                        <i class="fas fa-times"></i> Clear All
                                    </button>
                                </div>
                            </div>
                            <div class="card-body py-3">
                                <div class="row g-2">
                                    <!-- Member ID Filter -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold mb-1">MID</label>
                                        <input type="text" class="form-control form-control-sm" id="filterMemberId"
                                            placeholder="Search MID...">
                                    </div>

                                    <!-- Name Filter -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold mb-1">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="filterName"
                                            placeholder="Search name...">
                                    </div>

                                    <!-- Anchal Filter -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold mb-1">Anchal</label>
                                        <select class="form-select form-select-sm" id="filterAnchal">
                                            <option value="">All Anchals</option>
                                        </select>
                                    </div>

                                    <!-- City Filter -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold mb-1">City</label>
                                        <input type="text" class="form-control form-control-sm" id="filterCity"
                                            placeholder="Search city...">
                                    </div>

                                    <!-- Education Filter -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold mb-1">Education</label>
                                        <input type="text" class="form-control form-control-sm" id="filterEducation"
                                            placeholder="Search education...">
                                    </div>

                                    <!-- Occupation Filter -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold mb-1">Occupation</label>
                                        <input type="text" class="form-control form-control-sm" id="filterOccupation"
                                            placeholder="Search occupation...">
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <!-- Response Filter -->
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold mb-1">Response</label>
                                        <select class="form-select form-select-sm" id="filterResponse">
                                            <option value="">All Responses</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                            <option value="maybe">Maybe</option>
                                        </select>
                                    </div>

                                    <!-- Apply Filter Button -->
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button class="btn btn-primary btn-sm w-100" id="applyFiltersBtn">
                                            <i class="fas fa-search"></i> Apply Filters
                                        </button>
                                    </div>

                                    <!-- Clear Filter Button -->
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button class="btn btn-outline-secondary btn-sm w-100" id="clearFiltersBtn2">
                                            <i class="fas fa-times"></i> Clear All
                                        </button>
                                    </div>
                                </div>

                                <!-- Total Results Count -->
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Showing <span id="filteredCount"
                                            class="fw-bold">0</span> registrations
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div id="registrationsContainer">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiUrl = "/api/spf-event-reg";
        const eventsApiUrl = "/api/spf-event-reg-events";
        const memberApiUrl = "https://mrmapi.sadhumargi.in/api/member";
        const aanchalApiUrl = "/api/aanchal";
        let allRegistrations = [];
        let allEvents = [];
        let latestEventId = null;
        let memberCache = {}; // Cache member details to avoid repeated API calls
        let aanchalMap = {}; // Map anchal_id to anchal name

        // Helper function to get latest event title
        function getLatestEventTitle() {
            if (allEvents.length > 0) {
                return allEvents[allEvents.length - 1].title;
            }
            return 'Unknown Event';
        }

        // Toast Alert Function
        function showToast(message, type = "success") {
            const iconMap = {
                success: "success",
                error: "error",
                warning: "warning",
                info: "info"
            };

            Swal.fire({
                icon: iconMap[type] || "success",
                text: message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
                background: type === "success" ? "#d4edda" : type === "error" ? "#f8d7da" : "#fff3cd",
                color: type === "success" ? "#155724" : type === "error" ? "#721c24" : "#856404"
            });
        }

        // Fetch Aanchals and build map
        async function fetchAanchals() {
            try {
                const res = await fetch(aanchalApiUrl);
                const result = await res.json();
                let aanchals = [];

                if (result && Array.isArray(result)) {
                    aanchals = result;
                } else if (result.data && Array.isArray(result.data)) {
                    aanchals = result.data;
                }

                // Build map and populate dropdown
                const filterAnchalEl = document.getElementById("filterAnchal");
                aanchals.forEach(aanchal => {
                    aanchalMap[aanchal.id] = aanchal.name;

                    // Add to dropdown
                    if (filterAnchalEl) {
                        const option = document.createElement("option");
                        option.value = aanchal.name;
                        option.textContent = aanchal.name;
                        filterAnchalEl.appendChild(option);
                    }
                });
            } catch (error) {
                console.error("Error fetching aanchals:", error);
            }
        }

        // Get Anchal Name from ID
        function getAnchalName(anchalId) {
            if (!anchalId) return 'N/A';
            return aanchalMap[anchalId] || `Anchal ${anchalId}`;
        }

        // Fetch Member Details from External API
        async function fetchMemberDetails(memberId) {
            if (!memberId) return null;

            // Check cache first
            if (memberCache[memberId]) {
                return memberCache[memberId];
            }

            try {
                const res = await fetch(`${memberApiUrl}/${memberId}`);
                if (res.ok) {
                    const data = await res.json();
                    memberCache[memberId] = data; // Cache the result
                    return data;
                }
                return null;
            } catch (error) {
                console.error("Error fetching member details:", error);
                return null;
            }
        }

        // Fetch Events for Dropdown
        async function fetchEvents() {
            try {
                const res = await fetch(eventsApiUrl);
                const result = await res.json();
                if (result.success && result.data.length > 0) {
                    // Sort events by ID (ascending) to ensure last item is the latest added
                    allEvents = result.data.sort((a, b) => a.id - b.id);

                    // Set latest event id (most recently added event - last in array)
                    const latestEvent = allEvents[allEvents.length - 1];
                    latestEventId = latestEvent.id;

                    // Update current event name display
                    const currentEventNameEl = document.getElementById("currentEventName");
                    if (currentEventNameEl) {
                        currentEventNameEl.textContent = latestEvent.title;
                    }

                    // For form dropdown - show all events
                    const selectElement = document.getElementById("event_id");
                    selectElement.innerHTML = '<option value="">-- Choose Event --</option>';
                    result.data.forEach(event => {
                        const option = document.createElement("option");
                        option.value = event.id;
                        option.textContent = event.title;
                        selectElement.appendChild(option);
                    });

                    // For event select dropdown (for viewing other events) - exclude latest event
                    const selectEventDropdown = document.getElementById("selectEventDropdown");
                    const viewOtherEventsBtn = document.getElementById("viewOtherEventsBtn");

                    // Show all events except the latest one in reverse order (newest first)
                    const otherEvents = result.data.slice(0, -1).reverse();

                    if (selectEventDropdown) {
                        selectEventDropdown.innerHTML = '<option value="">-- Show Latest Event (Default) --</option>';
                        otherEvents.forEach(event => {
                            const option = document.createElement("option");
                            option.value = event.id;
                            option.textContent = event.title;
                            selectEventDropdown.appendChild(option);
                        });
                    }

                    // Hide "View Other Events" button if there are no other events
                    if (viewOtherEventsBtn) {
                        if (otherEvents.length === 0) {
                            viewOtherEventsBtn.style.display = "none";
                        } else {
                            viewOtherEventsBtn.style.display = "inline-block";
                        }
                    }
                } else {
                    showToast("No events found. Please create events first.", "warning");
                    // Hide the current event badge and "View Other Events" button if no events
                    const currentEventBadge = document.getElementById("currentEventBadge");
                    const viewOtherEventsBtn = document.getElementById("viewOtherEventsBtn");
                    if (currentEventBadge) currentEventBadge.style.display = "none";
                    if (viewOtherEventsBtn) viewOtherEventsBtn.style.display = "none";
                }
            } catch (error) {
                console.error("Error fetching events:", error);
                showToast("Failed to load events", "error");
            }
        }

        // Show/Hide Form Functions
        function showForm() {
            document.getElementById("formContainer").style.display = "block";
            document.getElementById("addNewBtn").style.display = "none";
            const eventSelectContainer = document.getElementById("eventSelectContainer");
            if (eventSelectContainer) eventSelectContainer.style.display = "none";
            const registrationsContainer = document.getElementById("registrationsContainer");
            if (registrationsContainer) registrationsContainer.style.display = "none";
        }

        function hideForm() {
            document.getElementById("formContainer").style.display = "none";
            document.getElementById("addNewBtn").style.display = "block";
            const registrationsContainer = document.getElementById("registrationsContainer");
            if (registrationsContainer) registrationsContainer.style.display = "block";
            resetForm();
        }

        // Fetch Registrations
        async function fetchRegistrations() {
            try {
                const res = await fetch(apiUrl);
                const result = await res.json();
                if (result.success) {
                    if (result.data && result.data.length > 0) {
                        allRegistrations = result.data;
                        // By default show only latest event registrations
                        if (latestEventId) {
                            const latestRegs = allRegistrations.filter(r => r.event && r.event.id == latestEventId);
                            displayRegistrations(latestRegs);
                        } else {
                            displayRegistrations(allRegistrations);
                        }
                    } else {
                        allRegistrations = [];
                        document.getElementById("registrationsContainer").innerHTML =
                            '<div class="text-center text-muted py-5"><i class="fas fa-inbox fa-3x mb-3 d-block"></i><h5>No registrations found</h5></div>';
                    }
                } else {
                    console.error("API returned error:", result.message);
                    document.getElementById("registrationsContainer").innerHTML =
                        '<div class="text-center text-danger py-4">Failed to load data: ' + (result.message || 'Unknown error') + '</div>';
                }
            } catch (error) {
                console.error("Error fetching registrations:", error);
                showToast("Failed to load registrations", "error");
                document.getElementById("registrationsContainer").innerHTML =
                    '<div class="text-center text-danger py-4">Error loading data</div>';
            }
        }

        // Display Registrations Grouped by Events
        async function displayRegistrations(registrations) {
            const container = document.getElementById("registrationsContainer");

            if (!container) {
                console.error("registrationsContainer element not found");
                return;
            }

            if (!registrations || registrations.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-inbox fa-3x mb-3 d-block"></i><h5>No registrations found</h5></div>';
                updateFilteredCount(0);
                return;
            }

            // Group registrations by events
            const groupedByEvent = {};
            registrations.forEach(reg => {
                const eventId = reg.event ? reg.event.id : 'unknown';
                const eventTitle = reg.event ? reg.event.title : 'Unknown Event';

                if (!groupedByEvent[eventId]) {
                    groupedByEvent[eventId] = {
                        title: eventTitle,
                        registrations: []
                    };
                }
                groupedByEvent[eventId].registrations.push(reg);
            });

            let html = '';
            const eventIds = Object.keys(groupedByEvent).reverse();

            for (const eventId of eventIds) {
                const eventData = groupedByEvent[eventId];
                const eventRegs = eventData.registrations;

                html += `
                                                                                                                    <div class="card mb-4" id="event-card-${eventId}">
                                                                                                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                                                                                            <h6 class="mb-0">
                                                                                                                                <i class="fas fa-calendar"></i> ${eventData.title}
                                                                                                                            </h6>
                                                                                                                            <div class="d-flex align-items-center gap-2">
                                                                                                                                <span class="badge bg-light text-dark">${eventRegs.length} Registrations</span>
                                                                                                                                <button class="btn btn-sm btn-danger" onclick="downloadPDF('${eventId}', '${eventData.title}')" title="Download PDF">
                                                                                                                                    <i class="fas fa-file-pdf"></i>
                                                                                                                                </button>
                                                                                                                                <button class="btn btn-sm btn-success" onclick="downloadExcel('${eventId}', '${eventData.title}')" title="Download Excel">
                                                                                                                                    <i class="fas fa-file-excel"></i>
                                                                                                                                </button>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="card-body p-0">
                                                                                                                            <div class="table-responsive">
                                                                                                                                <table class="table table-hover table-striped mb-0">
                                                                                                                                    <thead class="table-light">
                                                                                                                                        <tr>
                                                                                                                                            <th>#</th>
                                                                                                                                            <th>MID</th>
                                                                                                                                            <th>Name</th>
                                                                                                                                            <th>Mobile</th>
                                                                                                                                            <th>Anchal</th>
                                                                                                                                            <th>City</th>
                                                                                                                                            <th>Education</th>
                                                                                                                                            <th>Occupation</th>
                                                                                                                                            <th>Event</th>
                                                                                                                                            <th>Response</th>
                                                                                                                                            <th>Actions</th>
                                                                                                                                        </tr>
                                                                                                                                    </thead>
                                                                                                                                    <tbody id="tbody-${eventId}">
                                                                                                                `;

                eventRegs.forEach((reg, index) => {
                    const responseClass = reg.response === "yes" ? "response-yes" :
                        reg.response === "no" ? "response-no" : "response-maybe";

                    html += `
                                                                                                                        <tr id="row-${reg.id}">
                                                                                                                            <td>${index + 1}</td>
                                                                                                                            <td><strong>${reg.member_id || "N/A"}</strong></td>
                                                                                                                            <td id="name-${reg.id}"><span class="loading-spinner"></span></td>
                                                                                                                            <td id="mobile-${reg.id}"><span class="loading-spinner"></span></td>
                                                                                                                            <td id="anchal-${reg.id}"><span class="loading-spinner"></span></td>
                                                                                                                            <td id="city-${reg.id}"><span class="loading-spinner"></span></td>
                                                                                                                            <td id="education-${reg.id}"><span class="loading-spinner"></span></td>
                                                                                                                            <td id="occupation-${reg.id}"><span class="loading-spinner"></span></td>
                                                                                                                            <td>${reg.event ? reg.event.title : "N/A"}</td>
                                                                                                                            <td><span class="response-badge ${responseClass}">${reg.response}</span></td>
                                                                                                                            <td>
                                                                                                                                <button class="btn btn-sm btn-info me-1" onclick="viewRegistration(${reg.id})" title="View Details">
                                                                                                                                    <i class="fas fa-eye"></i>
                                                                                                                                </button>
                                                                                                                                <button class="btn btn-sm btn-warning me-1" onclick="editRegistration(${reg.id})" title="Edit">
                                                                                                                                    <i class="fas fa-edit"></i>
                                                                                                                                </button>
                                                                                                                                <button class="btn btn-sm btn-danger" onclick="deleteRegistration(${reg.id})" title="Delete">
                                                                                                                                    <i class="fas fa-trash"></i>
                                                                                                                                </button>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    `;
                });

                html += `
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                `;
            }

            container.innerHTML = html;
            updateFilteredCount(registrations.length);

            // Now fetch member details for each registration
            for (const reg of registrations) {
                if (reg.member_id) {
                    fetchMemberDetails(reg.member_id).then(member => {
                        if (member) {
                            const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim() || 'N/A';
                            const anchalName = getAnchalName(member.anchal_id);
                            document.getElementById(`name-${reg.id}`).textContent = fullName;
                            document.getElementById(`mobile-${reg.id}`).textContent = member.mobile || 'N/A';
                            document.getElementById(`anchal-${reg.id}`).textContent = anchalName;
                            document.getElementById(`city-${reg.id}`).textContent = member.city || 'N/A';
                            document.getElementById(`education-${reg.id}`).textContent = member.education || 'N/A';
                            document.getElementById(`occupation-${reg.id}`).textContent = member.occupation || 'N/A';
                        } else {
                            document.getElementById(`name-${reg.id}`).textContent = 'N/A';
                            document.getElementById(`mobile-${reg.id}`).textContent = 'N/A';
                            document.getElementById(`anchal-${reg.id}`).textContent = 'N/A';
                            document.getElementById(`city-${reg.id}`).textContent = 'N/A';
                            document.getElementById(`education-${reg.id}`).textContent = 'N/A';
                            document.getElementById(`occupation-${reg.id}`).textContent = 'N/A';
                        }
                    });
                } else {
                    document.getElementById(`name-${reg.id}`).textContent = 'N/A';
                    document.getElementById(`mobile-${reg.id}`).textContent = 'N/A';
                    document.getElementById(`anchal-${reg.id}`).textContent = 'N/A';
                    document.getElementById(`city-${reg.id}`).textContent = 'N/A';
                    document.getElementById(`education-${reg.id}`).textContent = 'N/A';
                    document.getElementById(`occupation-${reg.id}`).textContent = 'N/A';
                }
            }
        }

        // Update filtered count display
        function updateFilteredCount(count) {
            const filteredCountEl = document.getElementById("filteredCount");
            if (filteredCountEl) {
                filteredCountEl.textContent = count;
            }
        }

        // Search and Filter Functionality
        async function applyFilters() {
            const filterMemberId = document.getElementById("filterMemberId").value.toLowerCase().trim();
            const filterName = document.getElementById("filterName").value.toLowerCase().trim();
            const filterAnchal = document.getElementById("filterAnchal").value.toLowerCase().trim();
            const filterCity = document.getElementById("filterCity").value.toLowerCase().trim();
            const filterEducation = document.getElementById("filterEducation").value.toLowerCase().trim();
            const filterOccupation = document.getElementById("filterOccupation").value.toLowerCase().trim();
            const filterResponse = document.getElementById("filterResponse").value;
            const selectEventDropdown = document.getElementById("selectEventDropdown");
            const selectedEventId = selectEventDropdown ? selectEventDropdown.value : null;

            let baseData = allRegistrations;
            if (selectedEventId) {
                baseData = allRegistrations.filter(r => r.event && r.event.id == selectedEventId);
            } else if (latestEventId) {
                baseData = allRegistrations.filter(r => r.event && r.event.id == latestEventId);
            }

            // If any member-related filter is applied, we need to check member cache
            const hasMemberFilter = filterName || filterAnchal || filterCity || filterEducation || filterOccupation;

            let filteredData = [];

            for (const reg of baseData) {
                // Basic filters
                const matchesMemberId = !filterMemberId || (reg.member_id && reg.member_id.toLowerCase().includes(filterMemberId));
                const matchesResponse = !filterResponse || reg.response === filterResponse;

                if (!matchesMemberId || !matchesResponse) {
                    continue;
                }

                // If no member filters, add to result
                if (!hasMemberFilter) {
                    filteredData.push(reg);
                    continue;
                }

                // Check member data from cache
                if (reg.member_id && memberCache[reg.member_id]) {
                    const member = memberCache[reg.member_id];
                    const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim().toLowerCase();
                    const anchalName = getAnchalName(member.anchal_id).toLowerCase();
                    const city = (member.city || '').toLowerCase();
                    const education = (member.education || '').toLowerCase();
                    const occupation = (member.occupation || '').toLowerCase();

                    const matchesName = !filterName || fullName.includes(filterName);
                    const matchesAnchal = !filterAnchal || anchalName.includes(filterAnchal);
                    const matchesCity = !filterCity || city.includes(filterCity);
                    const matchesEducation = !filterEducation || education.includes(filterEducation);
                    const matchesOccupation = !filterOccupation || occupation.includes(filterOccupation);

                    if (matchesName && matchesAnchal && matchesCity && matchesEducation && matchesOccupation) {
                        filteredData.push(reg);
                    }
                } else if (reg.member_id) {
                    // Fetch member data if not in cache
                    const member = await fetchMemberDetails(reg.member_id);
                    if (member) {
                        const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim().toLowerCase();
                        const anchalName = getAnchalName(member.anchal_id).toLowerCase();
                        const city = (member.city || '').toLowerCase();
                        const education = (member.education || '').toLowerCase();
                        const occupation = (member.occupation || '').toLowerCase();

                        const matchesName = !filterName || fullName.includes(filterName);
                        const matchesAnchal = !filterAnchal || anchalName.includes(filterAnchal);
                        const matchesCity = !filterCity || city.includes(filterCity);
                        const matchesEducation = !filterEducation || education.includes(filterEducation);
                        const matchesOccupation = !filterOccupation || occupation.includes(filterOccupation);

                        if (matchesName && matchesAnchal && matchesCity && matchesEducation && matchesOccupation) {
                            filteredData.push(reg);
                        }
                    }
                }
            }

            displayRegistrations(filteredData);
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById("filterMemberId").value = "";
            document.getElementById("filterName").value = "";
            document.getElementById("filterAnchal").value = "";
            document.getElementById("filterCity").value = "";
            document.getElementById("filterEducation").value = "";
            document.getElementById("filterOccupation").value = "";
            document.getElementById("filterResponse").value = "";

            // Show all data for selected event
            const selectEventDropdown = document.getElementById("selectEventDropdown");
            const selectedEventId = selectEventDropdown ? selectEventDropdown.value : null;

            let baseData = allRegistrations;
            if (selectedEventId) {
                baseData = allRegistrations.filter(r => r.event && r.event.id == selectedEventId);
            } else if (latestEventId) {
                baseData = allRegistrations.filter(r => r.event && r.event.id == latestEventId);
            }

            displayRegistrations(baseData);
            showToast("Filters cleared", "info");
        }

        // Validate Member ID on input
        document.getElementById("member_id").addEventListener("blur", async function () {
            const memberId = this.value.trim();
            const memberInfo = document.getElementById("memberInfo");

            if (memberId) {
                memberInfo.innerHTML = '<span class="loading-spinner"></span> Verifying...';
                const member = await fetchMemberDetails(memberId);
                if (member) {
                    const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim();
                    const anchalName = getAnchalName(member.anchal_id);
                    memberInfo.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> ${fullName} - ${member.mobile || 'N/A'} (${anchalName})</span>`;
                } else {
                    memberInfo.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Member not found</span>';
                }
            } else {
                memberInfo.innerHTML = '';
            }
        });

        // Submit Form
        document.getElementById("registrationForm").addEventListener("submit", async (e) => {
            e.preventDefault();

            const id = document.getElementById("registration_id").value;
            const formData = {
                member_id: document.getElementById("member_id").value,
                event_id: document.getElementById("event_id").value,
                response: document.getElementById("response").value,
            };

            const url = id ? `${apiUrl}/${id}` : apiUrl;
            const method = id ? "PUT" : "POST";

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await res.json();

                if (res.ok && result.success) {
                    showToast(result.message, "success");
                    fetchRegistrations();
                    hideForm();
                } else {
                    if (result.errors) {
                        let errorMessages = "";
                        Object.values(result.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                errorMessages += error + "<br>";
                            });
                        });

                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            html: errorMessages,
                            confirmButtonColor: "#667eea"
                        });
                    } else {
                        showToast(result.message || "Failed to save registration", "error");
                    }
                }
            } catch (error) {
                console.error("Error:", error);
                showToast("An error occurred while saving", "error");
            }
        });

        // View Registration Details
        async function viewRegistration(id) {
            try {
                const res = await fetch(`${apiUrl}/${id}`);
                const result = await res.json();

                if (result.success) {
                    const reg = result.data;
                    const eventName = reg.event ? reg.event.title : "N/A";

                    Swal.fire({
                        title: `<strong>Registration Details</strong>`,
                        html: `
                                                                                                                            <div class="text-start">
                                                                                                                                <table class="table table-bordered" id="viewDetailsTable">
                                                                                                                                    <tr><th>ID</th><td>${reg.id}</td></tr>
                                                                                                                                    <tr><th>Member ID</th><td>${reg.member_id || "-"}</td></tr>
                                                                                                                                    <tr id="memberNameRow"><th>Name</th><td><span class="loading-spinner"></span></td></tr>
                                                                                                                                    <tr id="memberMobileRow"><th>Mobile</th><td><span class="loading-spinner"></span></td></tr>
                                                                                                                                    <tr id="memberAnchalRow"><th>Anchal</th><td><span class="loading-spinner"></span></td></tr>
                                                                                                                                    <tr id="memberCityRow"><th>City</th><td><span class="loading-spinner"></span></td></tr>
                                                                                                                                    <tr id="memberEducationRow"><th>Education</th><td><span class="loading-spinner"></span></td></tr>
                                                                                                                                    <tr id="memberOccupationRow"><th>Occupation</th><td><span class="loading-spinner"></span></td></tr>
                                                                                                                                    <tr><th>Event</th><td>${eventName}</td></tr>
                                                                                                                                    <tr><th>Response</th><td><span class="badge bg-${reg.response === 'yes' ? 'success' : reg.response === 'no' ? 'danger' : 'warning'}">${reg.response}</span></td></tr>
                                                                                                                                    <tr><th>Created At</th><td>${new Date(reg.created_at).toLocaleString()}</td></tr>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                        `,
                        width: '600px',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#667eea',
                        didOpen: async () => {
                            if (reg.member_id) {
                                const member = await fetchMemberDetails(reg.member_id);
                                if (member) {
                                    const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim() || 'N/A';
                                    const anchalName = getAnchalName(member.anchal_id);
                                    document.querySelector('#memberNameRow td').textContent = fullName;
                                    document.querySelector('#memberMobileRow td').textContent = member.mobile || 'N/A';
                                    document.querySelector('#memberAnchalRow td').textContent = anchalName;
                                    document.querySelector('#memberCityRow td').textContent = member.city || 'N/A';
                                    document.querySelector('#memberEducationRow td').textContent = member.education || 'N/A';
                                    document.querySelector('#memberOccupationRow td').textContent = member.occupation || 'N/A';
                                } else {
                                    document.querySelector('#memberNameRow td').textContent = 'N/A';
                                    document.querySelector('#memberMobileRow td').textContent = 'N/A';
                                    document.querySelector('#memberAnchalRow td').textContent = 'N/A';
                                    document.querySelector('#memberCityRow td').textContent = 'N/A';
                                    document.querySelector('#memberEducationRow td').textContent = 'N/A';
                                    document.querySelector('#memberOccupationRow td').textContent = 'N/A';
                                }
                            } else {
                                document.querySelector('#memberNameRow td').textContent = 'N/A';
                                document.querySelector('#memberMobileRow td').textContent = 'N/A';
                                document.querySelector('#memberAnchalRow td').textContent = 'N/A';
                                document.querySelector('#memberCityRow td').textContent = 'N/A';
                                document.querySelector('#memberEducationRow td').textContent = 'N/A';
                                document.querySelector('#memberOccupationRow td').textContent = 'N/A';
                            }
                        }
                    });
                }
            } catch (error) {
                console.error("Error:", error);
                showToast("Failed to load details", "error");
            }
        }

        // Edit Registration
        async function editRegistration(id) {
            try {
                const res = await fetch(`${apiUrl}/${id}`);
                const result = await res.json();

                if (result.success) {
                    const reg = result.data;

                    document.getElementById("registration_id").value = reg.id;
                    document.getElementById("member_id").value = reg.member_id || "";
                    document.getElementById("event_id").value = reg.event_id;
                    document.getElementById("response").value = reg.response;

                    // Make fields read-only in edit mode (only response can be edited)
                    document.getElementById("member_id").setAttribute("readonly", true);
                    document.getElementById("member_id").classList.add("bg-light");
                    document.getElementById("event_id").setAttribute("disabled", true);
                    document.getElementById("event_id").classList.add("bg-light");

                    // Fetch and show member info
                    if (reg.member_id) {
                        const member = await fetchMemberDetails(reg.member_id);
                        if (member) {
                            const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim();
                            const anchalName = getAnchalName(member.anchal_id);
                            document.getElementById("memberInfo").innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> ${fullName} - ${member.mobile || 'N/A'} (${anchalName})</span>`;
                        }
                    }

                    document.getElementById("submitBtn").innerHTML = '<i class="fas fa-sync"></i> Update Response';

                    showForm();
                    showToast("You can only update the response", "info");
                    document.getElementById("registrationForm").scrollIntoView({ behavior: "smooth", block: "start" });
                }
            } catch (error) {
                console.error("Error:", error);
                showToast("Failed to load registration", "error");
            }
        }

        // Reset Form Function
        function resetForm() {
            document.getElementById("registrationForm").reset();
            document.getElementById("registration_id").value = "";
            document.getElementById("memberInfo").innerHTML = "";
            document.getElementById("submitBtn").innerHTML = '<i class="fas fa-save"></i> Save Registration';
            document.getElementById("response").value = "yes";

            // Remove read-only state for new registrations
            document.getElementById("member_id").removeAttribute("readonly");
            document.getElementById("member_id").classList.remove("bg-light");
            document.getElementById("event_id").removeAttribute("disabled");
            document.getElementById("event_id").classList.remove("bg-light");
        }

        // Delete Registration
        async function deleteRegistration(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This registration will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#667eea"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch(`${apiUrl}/${id}`, {
                            method: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const resultData = await res.json();

                        if (res.ok && resultData.success) {
                            showToast(resultData.message, "success");
                            fetchRegistrations();
                        } else {
                            showToast("Delete failed!", "error");
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        showToast("Failed to delete registration", "error");
                    }
                }
            });
        }

        // Initialize
        document.addEventListener("DOMContentLoaded", function () {
            const addNewBtn = document.getElementById("addNewBtn");
            const cancelBtn = document.getElementById("cancelBtn");
            const viewOtherEventsBtn = document.getElementById("viewOtherEventsBtn");
            let eventSelectContainer = document.getElementById("eventSelectContainer");
            let selectEventDropdown = document.getElementById("selectEventDropdown");

            if (addNewBtn) {
                addNewBtn.addEventListener("click", showForm);
            }
            if (cancelBtn) {
                cancelBtn.addEventListener("click", hideForm);
            }
            if (viewOtherEventsBtn && eventSelectContainer) {
                viewOtherEventsBtn.addEventListener("click", function () {
                    eventSelectContainer.style.display = eventSelectContainer.style.display === "none" ? "block" : "none";
                });
            }
            // Back to Latest Event button
            const showLatestEventBtn = document.getElementById("showLatestEventBtn");
            if (showLatestEventBtn) {
                showLatestEventBtn.addEventListener("click", function () {
                    if (latestEventId) {
                        // Reset dropdown to default
                        if (selectEventDropdown) {
                            selectEventDropdown.value = "";
                        }
                        // Show latest event registrations
                        const latestRegs = allRegistrations.filter(r => r.event && r.event.id == latestEventId);
                        displayRegistrations(latestRegs);

                        // Update badge to show latest event
                        const currentEventBadge = document.getElementById("currentEventBadge");
                        const currentEventNameEl = document.getElementById("currentEventName");
                        if (currentEventBadge) {
                            currentEventBadge.innerHTML = '<span class="badge bg-success"><i class="fas fa-star"></i> Latest Event:</span> <span id="currentEventName" class="text-dark fw-bold">' + getLatestEventTitle() + '</span>';
                        }

                        showToast("Showing latest event registrations", "info");
                    }
                });
            }

            if (selectEventDropdown) {
                selectEventDropdown.addEventListener("change", function () {
                    const selectedId = selectEventDropdown.value;
                    const currentEventBadge = document.getElementById("currentEventBadge");

                    if (selectedId) {
                        const selectedEvent = allEvents.find(e => e.id == selectedId);
                        const regs = allRegistrations.filter(r => r.event && r.event.id == selectedId);
                        displayRegistrations(regs);

                        // Update badge to show selected event
                        if (currentEventBadge && selectedEvent) {
                            currentEventBadge.innerHTML = '<span class="badge bg-info"><i class="fas fa-calendar"></i> Viewing Event:</span> <span id="currentEventName" class="text-dark fw-bold">' + selectedEvent.title + '</span>';
                        }
                    } else if (latestEventId) {
                        const latestRegs = allRegistrations.filter(r => r.event && r.event.id == latestEventId);
                        displayRegistrations(latestRegs);

                        // Update badge back to latest event
                        if (currentEventBadge) {
                            currentEventBadge.innerHTML = '<span class="badge bg-success"><i class="fas fa-star"></i> Latest Event:</span> <span id="currentEventName" class="text-dark fw-bold">' + getLatestEventTitle() + '</span>';
                        }
                    } else {
                        displayRegistrations(allRegistrations);
                    }
                });
            }

            const applyFiltersBtn = document.getElementById("applyFiltersBtn");
            const clearFiltersBtn = document.getElementById("clearFiltersBtn");
            const clearFiltersBtn2 = document.getElementById("clearFiltersBtn2");
            const filterMemberIdEl = document.getElementById("filterMemberId");
            const filterNameEl = document.getElementById("filterName");
            const filterAnchalEl = document.getElementById("filterAnchal");
            const filterCityEl = document.getElementById("filterCity");
            const filterEducationEl = document.getElementById("filterEducation");
            const filterOccupationEl = document.getElementById("filterOccupation");
            const filterResponseEl = document.getElementById("filterResponse");

            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener("click", applyFilters);
            }
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener("click", clearFilters);
            }
            if (clearFiltersBtn2) {
                clearFiltersBtn2.addEventListener("click", clearFilters);
            }

            // Enter key listeners for all filter inputs
            const filterInputs = [filterMemberIdEl, filterNameEl, filterAnchalEl, filterCityEl, filterEducationEl, filterOccupationEl];
            filterInputs.forEach(el => {
                if (el) {
                    el.addEventListener("keypress", function (e) {
                        if (e.key === "Enter") {
                            applyFilters();
                        }
                    });
                }
            });

            if (filterResponseEl) {
                filterResponseEl.addEventListener("change", applyFilters);
            }

            // Load aanchals first, then events and registrations
            fetchAanchals().then(() => {
                fetchEvents().then(() => {
                    fetchRegistrations();
                });
            });
        });

        // Download PDF for specific event
        async function downloadPDF(eventId, eventTitle) {
            showToast("Generating PDF report...", "info");

            // Get registrations for this event
            const eventRegs = allRegistrations.filter(r => r.event && r.event.id == eventId);

            if (eventRegs.length === 0) {
                showToast("No registrations found for this event", "warning");
                return;
            }

            // Fetch all member details
            const reportData = [];
            for (let i = 0; i < eventRegs.length; i++) {
                const reg = eventRegs[i];
                let memberData = {
                    sno: i + 1,
                    member_id: reg.member_id || 'N/A',
                    name: 'N/A',
                    mobile: 'N/A',
                    anchal: 'N/A',
                    city: 'N/A',
                    education: 'N/A',
                    occupation: 'N/A',
                    response: reg.response || 'N/A'
                };

                if (reg.member_id) {
                    const member = await fetchMemberDetails(reg.member_id);
                    if (member) {
                        memberData.name = `${member.first_name || ''} ${member.last_name || ''}`.trim() || 'N/A';
                        memberData.mobile = member.mobile || 'N/A';
                        memberData.anchal = getAnchalName(member.anchal_id);
                        memberData.city = member.city || 'N/A';
                        memberData.education = member.education || 'N/A';
                        memberData.occupation = member.occupation || 'N/A';
                    }
                }
                reportData.push(memberData);
            }

            // Generate PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4'); // Landscape orientation

            // Title
            doc.setFontSize(16);
            doc.setTextColor(40, 40, 40);
            doc.text(`Event Registration Report`, 14, 15);
            doc.setFontSize(12);
            doc.text(`Event: ${eventTitle}`, 14, 22);
            doc.text(`Total Registrations: ${reportData.length}`, 14, 28);
            doc.text(`Generated: ${new Date().toLocaleString()}`, 14, 34);

            // Table
            doc.autoTable({
                startY: 40,
                head: [['#', 'MID', 'Name', 'Mobile', 'Anchal', 'City', 'Education', 'Occupation', 'Response']],
                body: reportData.map(row => [
                    row.sno,
                    row.member_id,
                    row.name,
                    row.mobile,
                    row.anchal,
                    row.city,
                    row.education,
                    row.occupation,
                    row.response.toUpperCase()
                ]),
                styles: { fontSize: 9, cellPadding: 2 },
                headStyles: { fillColor: [102, 126, 234], textColor: 255 },
                alternateRowStyles: { fillColor: [245, 245, 245] }
            });

            // Save PDF
            const fileName = `${eventTitle.replace(/[^a-zA-Z0-9]/g, '_')}_Registrations_${new Date().toISOString().slice(0, 10)}.pdf`;
            doc.save(fileName);
            showToast("PDF downloaded successfully!", "success");
        }

        // Download Excel for specific event
        async function downloadExcel(eventId, eventTitle) {
            showToast("Generating Excel report...", "info");

            // Get registrations for this event
            const eventRegs = allRegistrations.filter(r => r.event && r.event.id == eventId);

            if (eventRegs.length === 0) {
                showToast("No registrations found for this event", "warning");
                return;
            }

            // Fetch all member details
            const reportData = [];
            for (let i = 0; i < eventRegs.length; i++) {
                const reg = eventRegs[i];
                let memberData = {
                    'S.No': i + 1,
                    'Member ID': reg.member_id || 'N/A',
                    'Name': 'N/A',
                    'Mobile': 'N/A',
                    'Anchal': 'N/A',
                    'City': 'N/A',
                    'Education': 'N/A',
                    'Occupation': 'N/A',
                    'Response': reg.response ? reg.response.toUpperCase() : 'N/A'
                };

                if (reg.member_id) {
                    const member = await fetchMemberDetails(reg.member_id);
                    if (member) {
                        memberData['Name'] = `${member.first_name || ''} ${member.last_name || ''}`.trim() || 'N/A';
                        memberData['Mobile'] = member.mobile || 'N/A';
                        memberData['Anchal'] = getAnchalName(member.anchal_id);
                        memberData['City'] = member.city || 'N/A';
                        memberData['Education'] = member.education || 'N/A';
                        memberData['Occupation'] = member.occupation || 'N/A';
                    }
                }
                reportData.push(memberData);
            }

            // Create Excel workbook
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(reportData);

            // Set column widths
            ws['!cols'] = [
                { wch: 5 },   // S.No
                { wch: 12 },  // Member ID
                { wch: 25 },  // Name
                { wch: 15 },  // Mobile
                { wch: 20 },  // Anchal
                { wch: 15 },  // City
                { wch: 20 },  // Education
                { wch: 20 },  // Occupation
                { wch: 10 }   // Response
            ];

            XLSX.utils.book_append_sheet(wb, ws, "Registrations");

            // Save Excel
            const fileName = `${eventTitle.replace(/[^a-zA-Z0-9]/g, '_')}_Registrations_${new Date().toISOString().slice(0, 10)}.xlsx`;
            XLSX.writeFile(wb, fileName);
            showToast("Excel downloaded successfully!", "success");
        }
    </script>
@endsection