<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $eventTitle ?? 'Event' }} - Sadhumargi App</title>
    
    <!-- App Deep Link Meta Tags for Android -->
    <meta name="al:android:url" content="sadhumargi://event/{{ $eventId }}">
    <meta name="al:android:package" content="com.sabsjs.laravel_auth_flutter">
    <meta name="al:android:app_name" content="Sadhumargi">
    
    <!-- App Deep Link Meta Tags for iOS -->
    <meta name="al:ios:url" content="sadhumargi://event/{{ $eventId }}">
    <meta name="al:ios:app_store_id" content="YOUR_APP_STORE_ID">
    <meta name="al:ios:app_name" content="Sadhumargi">
    
    <!-- Open Graph Meta Tags for Social Sharing -->
    <meta property="og:title" content="{{ $eventTitle ?? 'Event' }}">
    <meta property="og:description" content="View this event in Sadhumargi App">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://website.sadhumargi.in/app/event/{{ $eventId }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
        }
        
        h1 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #718096;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
            color: #667eea;
        }
        
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #e2e8f0;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        #fallback {
            display: none;
        }
        
        .fallback-content {
            margin-top: 20px;
        }
        
        .app-info {
            background: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .app-info h3 {
            color: #2d3748;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .app-info p {
            color: #718096;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .download-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }
        
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #f7fafc;
            color: #2d3748;
            border: 2px solid #e2e8f0;
        }
        
        .icon {
            width: 24px;
            height: 24px;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">📅</div>
        <h1>Opening Event...</h1>
        <p class="subtitle">Redirecting to Sadhumargi App</p>
        
        <div class="loading">
            <div class="spinner"></div>
            <span>Please wait</span>
        </div>
        
        <div id="fallback">
            <div class="fallback-content">
                <div class="app-info">
                    <h3>App Not Installed?</h3>
                    <p>Download the Sadhumargi App to view this event and stay updated with all our activities.</p>
                </div>
                
                <div class="download-buttons">
                    <a href="{{ $playStoreLink }}" class="btn btn-primary">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                        </svg>
                        Download for Android
                    </a>
                    
                    <a href="{{ $appStoreLink }}" class="btn btn-secondary">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z"/>
                        </svg>
                        Download for iOS
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Attempt to open the app using custom scheme
        const deepLink = "sadhumargi://event/{{ $eventId }}";
        const universalLink = "https://sadhumargi.in/app/event/{{ $eventId }}";
        
        // Try to open app immediately
        window.location.href = deepLink;
        
        // Show fallback after 2.5 seconds if app didn't open
        setTimeout(function() {
            document.querySelector('.loading').style.display = 'none';
            document.getElementById('fallback').style.display = 'block';
        }, 2500);
        
        // Additional attempt for iOS using universal link
        setTimeout(function() {
            if (document.hidden || document.webkitHidden) {
                // App likely opened, do nothing
            } else {
                // Try universal link as fallback
                window.location.href = universalLink;
            }
        }, 500);
    </script>
</body>
</html>
