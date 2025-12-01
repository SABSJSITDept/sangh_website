@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .spf-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Modern Card with Glassmorphism */
        .spf-card {
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Premium Header */
        .spf-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            padding: 2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .spf-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .spf-card-header span {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .spf-card-header i {
            font-size: 1.75rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Modern Add Button */
        .spf-btn-add {
            position: relative;
            z-index: 1;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.75rem;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .spf-btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        }

        .spf-btn-add:active {
            transform: translateY(0);
        }

        /* Card Body */
        .spf-card-body {
            padding: 2.5rem;
        }

        /* Premium Toast */
        .spf-toast {
            min-width: 300px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            border: none;
            animation: slideInRight 0.4s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Modern Form */
        #spfForm {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            animation: expandIn 0.4s ease-out;
        }

        @keyframes expandIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Floating Labels */
        .spf-form-floating {
            position: relative;
            margin-bottom: 1.75rem;
        }

        .spf-form-floating .form-control,
        .spf-form-floating select {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            color: #2d3748;
        }

        .spf-form-floating .form-control:focus,
        .spf-form-floating select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .spf-form-floating label {
            position: absolute;
            top: 1rem;
            left: 1.25rem;
            color: #718096;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            padding: 0 0.5rem;
            font-weight: 500;
        }

        .spf-form-floating .form-control:focus~label,
        .spf-form-floating .form-control:not(:placeholder-shown)~label,
        .spf-form-floating select:focus~label,
        .spf-form-floating select:not([value=""])~label {
            top: -0.75rem;
            left: 1rem;
            font-size: 0.85rem;
            color: #667eea;
            background: #fff;
            font-weight: 600;
        }

        /* File Input */
        .file-input-wrapper {
            margin-bottom: 1.75rem;
        }

        .file-input-wrapper label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .file-input-wrapper input[type="file"] {
            width: 100%;
            padding: 1rem;
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            background: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-wrapper input[type="file"]:hover {
            border-color: #667eea;
            background: #f7fafc;
        }

        /* Modern Tabs */
        .nav-tabs {
            border: none;
            gap: 0.5rem;
            margin-top: 2rem;
            padding: 0.5rem;
            background: #f7fafc;
            border-radius: 16px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: #4a5568;
            transition: all 0.3s ease;
            background: transparent;
            position: relative;
        }

        .nav-tabs .nav-link:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Premium Table */
        .spf-table-wrapper {
            margin-top: 1.5rem;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .spf-table {
            width: 100%;
            margin: 0;
            background: #fff;
        }

        .spf-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .spf-table thead th {
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .spf-table tbody td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
            font-weight: 500;
        }

        .spf-table tbody tr {
            transition: all 0.3s ease;
        }

        .spf-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .spf-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Member Photo */
        .member-photo {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .member-photo:hover {
            transform: scale(1.1);
        }

        .member-info {
            display: flex;
            align-items: center;
        }

        /* Action Buttons */
        .spf-action-btn {
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            margin-right: 0.5rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .spf-action-btn.edit {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
        }

        .spf-action-btn.edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 172, 254, 0.4);
        }

        .spf-action-btn.delete {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(250, 112, 154, 0.3);
        }

        .spf-action-btn.delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(250, 112, 154, 0.4);
        }

        .spf-action-btn:active {
            transform: translateY(0);
        }

        /* Form Buttons */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary-custom {
            background: #fff;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
            transform: translateY(-2px);
        }

        /* Anchal Filter */
        #anchalFilter {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            background: #fff;
            color: #2d3748;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        #anchalFilter:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        /* Loading State */
        .loading-row td {
            text-align: center;
            padding: 3rem !important;
            color: #718096;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .loading-row td::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #e2e8f0;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 1rem;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0;
            }

            .spf-card {
                border-radius: 16px;
            }

            .spf-card-header {
                font-size: 1.25rem;
                padding: 1.5rem 1.25rem;
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .spf-btn-add {
                width: 100%;
                justify-content: center;
            }

            .spf-card-body {
                padding: 1.5rem;
            }

            #spfForm {
                padding: 1.5rem;
            }

            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .spf-table thead th,
            .spf-table tbody td {
                padding: 1rem;
                font-size: 0.9rem;
            }

            .member-photo {
                width: 40px;
                height: 40px;
            }

            .spf-action-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #718096;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
    </style>

    <div class="spf-container">
        <div class="spf-card">
            <div class="spf-card-header">
                <span><i class="bi bi-people-fill"></i>SPF Committee Management</span>
                <button class="spf-btn-add" onclick="openForm()">
                    <i class="bi bi-plus-circle"></i> Add Member
                </button>
            </div>
            <div class="spf-card-body">

                {{-- Toast --}}
                <div id="toast"
                    class="toast spf-toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3"
                    role="alert" aria-live="assertive" aria-atomic="true" style="display:none;z-index:9999;">
                    <div class="d-flex">
                        <div class="toast-body" id="toast-message"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="hideToast()"></button>
                    </div>
                </div>

                {{-- Form --}}
                <form id="spfForm" class="mb-4" style="display:none;max-width:520px;margin:auto;"
                    onsubmit="return submitForm(event)">
                    <input type="hidden" id="member_id">

                    <div class="spf-form-floating">
                        <input type="text" class="form-control" id="name" placeholder=" " required>
                        <label for="name">Name</label>
                        <div class="invalid-feedback">Name is required.</div>
                    </div>

                    <div class="file-input-wrapper">
                        <label for="photo">Photo</label>
                        <input type="file" id="photo" accept="image/*">
                    </div>

                    <div class="spf-form-floating">
                        <select class="form-control" id="post" placeholder=" " required>
                            <option value="">Select Post</option>
                            <option value="Advisory Board">Advisory Board</option>
                            <option value="Core Committee">Core Committee</option>
                            <option value="Anchal Coordinators">Anchal Coordinators</option>
                        </select>
                        <label for="post">Post</label>
                        <div class="invalid-feedback">Post is required.</div>
                    </div>

                    <div class="spf-form-floating">
                        <select class="form-control" id="anchalSelect" placeholder=" ">
                            <option value="">Select Anchal</option>
                        </select>
                        <label for="anchalSelect">Anchal</label>
                    </div>

                    <div class="spf-form-floating">
                        <input type="text" class="form-control" id="city" placeholder=" ">
                        <label for="city">City</label>
                    </div>

                    <div class="spf-form-floating">
                        <input type="text" class="form-control" id="session" placeholder=" ">
                        <label for="session">Session</label>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary-custom" onclick="closeForm()">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-save me-1"></i>Save
                        </button>
                    </div>
                </form>

                {{-- Tabs --}}
                <ul class="nav nav-tabs mt-3" id="committeeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="advisory-tab" data-bs-toggle="tab"
                            data-bs-target="#advisory-pane" type="button" role="tab" aria-controls="advisory-pane"
                            aria-selected="true">
                            Advisory Board
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="core-tab" data-bs-toggle="tab" data-bs-target="#core-pane"
                            type="button" role="tab" aria-controls="core-pane" aria-selected="false">
                            Core Committee
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="anchal-tab" data-bs-toggle="tab" data-bs-target="#anchal-pane"
                            type="button" role="tab" aria-controls="anchal-pane" aria-selected="false">
                            Anchal Coordinators
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="committeeTabsContent">
                    {{-- Advisory --}}
                    <div class="tab-pane fade show active" id="advisory-pane" role="tabpanel"
                        aria-labelledby="advisory-tab">
                        <div class="spf-table-wrapper">
                            <table class="table spf-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>City</th>
                                        <th>Session</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="advisoryTable"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Core --}}
                    <div class="tab-pane fade" id="core-pane" role="tabpanel" aria-labelledby="core-tab">
                        <div class="spf-table-wrapper">
                            <table class="table spf-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>City</th>
                                        <th>Session</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="coreTable"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Anchal --}}
                    <div class="tab-pane fade" id="anchal-pane" role="tabpanel" aria-labelledby="anchal-tab">
                        <select class="form-control mb-3" id="anchalFilter">
                            <option value="">Select Anchal</option>
                        </select>
                        <div class="spf-table-wrapper">
                            <table class="table spf-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>City</th>
                                        <th>Session</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="anchalTable"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> {{-- spf-card-body --}}
        </div> {{-- spf-card --}}
    </div> {{-- spf-container --}}


    <script>
        const apiUrl = '/api/spf-committee';

        function showToast(message, success = true) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            if (!toast || !toastMsg) return;

            toastMsg.textContent = message;
            toast.classList.remove('bg-danger', 'bg-success');
            toast.classList.add(success ? 'bg-success' : 'bg-danger');
            toast.style.display = 'block';

            setTimeout(hideToast, 3000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            if (toast) toast.style.display = 'none';
        }

        function openForm(id = null, name = '', post = '', anchal_id = '', city = '', session = '') {
            const form = document.getElementById('spfForm');
            form.style.display = 'block';
            form.style.setProperty('display', 'block', 'important');
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });

            document.getElementById('member_id').value = id || '';
            document.getElementById('name').value = name || '';
            document.getElementById('post').value = post || '';
            const anchalSelect = document.getElementById('anchalSelect');
            if (anchalSelect) anchalSelect.value = anchal_id || '';
            document.getElementById('city').value = city || '';
            document.getElementById('session').value = session || '';
            // photo input blank hi rahega edit ke time (optional)
        }

        function closeForm() {
            const form = document.getElementById('spfForm');
            form.reset();
            form.style.display = 'none';
            document.getElementById('member_id').value = '';
            document.getElementById('name').classList.remove('is-invalid');
            document.getElementById('post').classList.remove('is-invalid');
        }

        function validateForm() {
            let valid = true;
            const name = document.getElementById('name');
            const post = document.getElementById('post');

            if (!name.value.trim()) {
                name.classList.add('is-invalid');
                valid = false;
            } else {
                name.classList.remove('is-invalid');
            }

            if (!post.value.trim()) {
                post.classList.add('is-invalid');
                valid = false;
            } else {
                post.classList.remove('is-invalid');
            }

            return valid;
        }

        async function submitForm(event) {
            event.preventDefault();
            if (!validateForm()) return false;

            const id = document.getElementById('member_id').value;
            const name = document.getElementById('name').value.trim();
            const post = document.getElementById('post').value.trim();
            const anchalSelect = document.getElementById('anchalSelect');
            const anchal_id = (anchalSelect && anchalSelect.value) ? anchalSelect.value : null;
            const city = document.getElementById('city').value.trim();
            const session = document.getElementById('session').value.trim();
            const photoInput = document.getElementById('photo');

            const url = id ? `${apiUrl}/${id}` : apiUrl;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // 🔴 File bhejne ke liye FormData use karna zaruri hai
            const formData = new FormData();
            formData.append('name', name);
            formData.append('post', post);
            if (anchal_id) formData.append('anchal_id', anchal_id);
            if (city) formData.append('city', city);
            if (session) formData.append('session', session);

            if (photoInput && photoInput.files.length > 0) {
                formData.append('photo', photoInput.files[0]);
            }

            if (id) {
                // Laravel resource route ke liye PUT ko simulate kar rahe
                formData.append('_method', 'PUT');
            }

            try {
                const res = await fetch(url, {
                    method: 'POST', // actual HTTP method
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        // 'Content-Type' ko manually set nahi karna, browser multipart/form-data set karega
                    },
                    body: formData
                });

                if (!res.ok) throw new Error('Failed to save member');

                showToast('Member saved successfully!');
                closeForm();

                // Reload active tab
                const activeTab = document.querySelector('#committeeTabs .nav-link.active');
                const anchalFilter = document.getElementById('anchalFilter');
                if (activeTab) {
                    const tabId = activeTab.id;
                    if (tabId === 'advisory-tab') {
                        loadCommittee('/api/spf-committee/advisory-board');
                    } else if (tabId === 'core-tab') {
                        loadCommittee('/api/spf-committee/core-committee');
                    } else if (tabId === 'anchal-tab') {
                        let url = '/api/spf-committee/anchal-coordinators';
                        if (anchalFilter && anchalFilter.value) url += `/${anchalFilter.value}`;
                        loadCommittee(url);
                    }
                } else {
                    loadCommittee('/api/spf-committee/advisory-board');
                }
            } catch (err) {
                showToast(err.message, false);
            }
            return false;
        }

        async function loadCommittee(endpoint) {
            let tableId = 'advisoryTable';
            const coreTab = document.getElementById('core-tab');
            const anchalTab = document.getElementById('anchal-tab');

            if (coreTab && coreTab.classList.contains('active')) {
                tableId = 'coreTable';
            } else if (anchalTab && anchalTab.classList.contains('active')) {
                tableId = 'anchalTable';
            }

            const table = document.getElementById(tableId);
            if (!table) return;

            table.innerHTML = '<tr class="loading-row"><td colspan="6">Loading...</td></tr>';

            try {
                const res = await fetch(endpoint);
                const data = await res.json();
                table.innerHTML = '';

                if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
                    table.innerHTML = `<tr><td colspan="6" class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No members found</p>
                                    </td></tr>`;
                    return;
                }

                data.data.forEach((member, idx) => {
                    const safeName = member.name ? member.name.replace(/'/g, "\\'") : '';
                    const safePost = member.post ? member.post.replace(/'/g, "\\'") : '';
                    const anchalId = member.anchal_id || '';
                    const safeCity = member.city ? member.city.replace(/'/g, "\\'") : '';
                    const safeSession = member.session ? member.session.replace(/'/g, "\\'") : '';

                    const photoUrl = member.photo ? `/storage/${member.photo}` : '/default-user.png';

                    table.innerHTML += `<tr>
                                        <td>${idx + 1}</td>
                                        <td>
                                            <div class="member-info">
                                                <img src="${photoUrl}" class="member-photo" alt="${member.name}">
                                                <span>${member.name}</span>
                                            </div>
                                        </td>
                                        <td>${member.post}</td>
                                        <td>${member.city || '-'}</td>
                                        <td>${member.session || '-'}</td>
                                        <td>
                                            <button class="spf-action-btn edit" title="Edit"
                                                onclick="openForm(${member.id}, '${safeName}', '${safePost}', '${anchalId}', '${safeCity}', '${safeSession}')">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button class="spf-action-btn delete" title="Delete"
                                                onclick="deleteMember(${member.id})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                });
            } catch (err) {
                table.innerHTML = `<tr><td colspan="6" class="empty-state">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <p>${err.message}</p>
                                </td></tr>`;
            }
        }

        async function deleteMember(id) {
            if (!confirm('Are you sure you want to delete this member?')) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const res = await fetch(`${apiUrl}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!res.ok) throw new Error('Failed to delete member');

                showToast('Member deleted successfully!');

                const activeTab = document.querySelector('#committeeTabs .nav-link.active');
                const anchalFilter = document.getElementById('anchalFilter');
                if (activeTab) {
                    const tabId = activeTab.id;
                    if (tabId === 'advisory-tab') {
                        loadCommittee('/api/spf-committee/advisory-board');
                    } else if (tabId === 'core-tab') {
                        loadCommittee('/api/spf-committee/core-committee');
                    } else if (tabId === 'anchal-tab') {
                        let url = '/api/spf-committee/anchal-coordinators';
                        if (anchalFilter && anchalFilter.value) url += `/${anchalFilter.value}`;
                        loadCommittee(url);
                    }
                } else {
                    loadCommittee('/api/spf-committee/advisory-board');
                }
            } catch (err) {
                showToast(err.message, false);
            }
        }

        async function loadAnchalOptions() {
            const anchalSelect = document.getElementById('anchalSelect');
            const anchalFilter = document.getElementById('anchalFilter');
            if (!anchalSelect && !anchalFilter) return;

            try {
                const res = await fetch('/api/aanchal');
                const data = await res.json();

                if (Array.isArray(data)) {
                    data.forEach(anchal => {
                        if (anchalSelect) {
                            const opt1 = document.createElement('option');
                            opt1.value = anchal.id;
                            opt1.textContent = anchal.name;
                            anchalSelect.appendChild(opt1);
                        }
                        if (anchalFilter) {
                            const opt2 = document.createElement('option');
                            opt2.value = anchal.id;
                            opt2.textContent = anchal.name;
                            anchalFilter.appendChild(opt2);
                        }
                    });
                }
            } catch (err) {
                console.error('Failed to load anchal options:', err);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('#committeeTabs button');
            const anchalFilter = document.getElementById('anchalFilter');

            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', (event) => {
                    const tabId = event.target.id;
                    if (tabId === 'advisory-tab') {
                        loadCommittee('/api/spf-committee/advisory-board');
                    } else if (tabId === 'core-tab') {
                        loadCommittee('/api/spf-committee/core-committee');
                    } else if (tabId === 'anchal-tab') {
                        let url = '/api/spf-committee/anchal-coordinators';
                        if (anchalFilter && anchalFilter.value) url += `/${anchalFilter.value}`;
                        loadCommittee(url);
                    }
                });
            });

            if (anchalFilter) {
                anchalFilter.addEventListener('change', function () {
                    let url = '/api/spf-committee/anchal-coordinators';
                    if (anchalFilter.value) url += `/${anchalFilter.value}`;
                    loadCommittee(url);
                });
            }

            // Initial load
            loadCommittee('/api/spf-committee/advisory-board');
            loadAnchalOptions();
        });

        // Expose to global
        window.openForm = openForm;
        window.deleteMember = deleteMember;
        window.closeForm = closeForm;
    </script>
@endsection