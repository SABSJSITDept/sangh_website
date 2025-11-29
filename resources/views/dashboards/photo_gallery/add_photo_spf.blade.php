@extends('includes.layouts.spf')

@section('content')
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

        .gallery-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 0.6s ease-out;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a202c !important;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #f7ff00 100%);
            padding: 1rem 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(255, 215, 0, 0.5), 0 0 20px rgba(255, 237, 78, 0.3);
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .page-header h1::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Info Alert */
        .info-alert {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2.5rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease-out 0.2s both;
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

        .info-alert p {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            color: #2d3748;
        }

        .info-alert strong {
            color: #667eea;
        }

        /* Upload Card */
        .upload-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            animation: fadeInLeft 0.6s ease-out 0.3s both;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .upload-card h5 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .form-control-modern {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            color: #2d3748;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .file-input-modern {
            width: 100%;
            padding: 1rem;
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            background: #f7fafc;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .file-input-modern:hover {
            border-color: #667eea;
            background: #edf2f7;
        }

        .btn-upload {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-upload:active {
            transform: translateY(0);
        }

        /* Gallery Card */
        .gallery-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            animation: fadeInRight 0.6s ease-out 0.4s both;
            height: 100%;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .gallery-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.25);
        }

        .gallery-card-img {
            height: 280px;
            object-fit: contain;
            padding: 2rem;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            transition: transform 0.4s ease;
        }

        .gallery-card:hover .gallery-card-img {
            transform: scale(1.05);
        }

        .gallery-card-body {
            padding: 2rem;
            text-align: center;
        }

        .gallery-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gallery-card-text {
            color: #718096;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .btn-view-gallery {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
        }

        .btn-view-gallery:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245, 87, 108, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .upload-card {
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            .gallery-card-img {
                height: 200px;
                padding: 1.5rem;
            }

            .gallery-card-body {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="gallery-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>📸 SPF फोटो गैलरी</h1>
        </div>

        <!-- Info Alert -->
        <div class="info-alert">
            <p>⚠️ कृपया ध्यान दें: प्रत्येक फोटो का साइज़ <strong>200 KB</strong> से अधिक नहीं होना चाहिए और आप अधिकतम
                <strong>10 फोटो</strong> ही अपलोड कर सकते हैं।
            </p>
        </div>

        <div class="row">
            <!-- Upload Form -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="upload-card">
                    <h5>📤 Upload Photos</h5>
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control-modern" name="category" required>
                                <option value="spf">SPF</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Event Name</label>
                            <input type="text" class="form-control-modern" name="event_name" placeholder="Enter event name"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Photos (Max 10)</label>
                            <input type="file" class="file-input-modern" id="photosInput" name="photos[]" accept="image/*"
                                multiple required>
                        </div>
                        <button type="submit" class="btn-upload">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Photos
                        </button>
                    </form>
                </div>
            </div>

            <!-- Gallery Card -->
            <div class="col-lg-7 col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="gallery-card" onclick="window.location='/spf_photo_gallery_view'">
                            <img src="{{ asset('images/SPF.png') }}" class="gallery-card-img" alt="SPF Gallery">
                            <div class="gallery-card-body">
                                <h5 class="gallery-card-title">🌸 SPF Gallery</h5>
                                <p class="gallery-card-text">SPF के अद्भुत पलों की झलकियां देखें</p>
                                <button class="btn-view-gallery">
                                    <i class="bi bi-images me-2"></i>View Gallery
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let photosInput = document.getElementById('photosInput');
            let files = photosInput.files;

            // Max 10 photos check
            if (files.length > 9) {
                showToast('आप अधिकतम 9 फोटो ही अपलोड कर सकते हैं!', 'error');
                return;
            }

            // Size check (200 KB)
            for (let file of files) {
                if (file.size > 200 * 1024) {
                    showToast(`"${file.name}" 200 KB से अधिक है!`, 'error');
                    return;
                }
            }

            let formData = new FormData(this);

            // Show loading
            Swal.fire({
                title: 'Uploading...',
                html: 'Please wait while we upload your photos',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/api/photo-gallery/store', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    Swal.close();
                    if (data.error) {
                        showToast(data.error, 'error');
                    } else {
                        showToast(data.message, 'success');
                        this.reset();
                    }
                })
                .catch(err => {
                    Swal.close();
                    showToast('Upload failed. Please try again.', 'error');
                });
        });

        function showToast(message, icon) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    </script>
@endsection