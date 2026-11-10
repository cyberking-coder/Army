<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { margin: 0; padding: 0; background: #f4f6f2; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #1a1f13; }
        .wrap { max-width: 560px; margin: 32px auto; background: #fff; border-radius: 10px; overflow: hidden; }
        .hdr { background: #4A5A30; color: #fff; padding: 20px 24px; }
        .hdr h1 { margin: 0; font-size: 18px; font-weight: 700; }
        .body { padding: 28px 24px; line-height: 1.6; font-size: 15px; color: #374151; }
        .box { background: #F4F6F2; border-left: 4px solid #C9A227; padding: 16px 20px; margin: 16px 0; border-radius: 0 6px 6px 0; }
        .otp { font-size: 32px; font-weight: 700; letter-spacing: 8px; color: #4A5A30; text-align: center; margin: 24px 0; font-family: monospace; }
        .btn { display: inline-block; background: #C9A227; color: #1a1f13 !important; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; margin: 12px 0; }
        .ftr { background: #f4f6f2; padding: 16px 24px; font-size: 12px; color: #6B7280; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { background: #f4f6f2; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; color: #4A5A30; }
        .total { font-weight: 700; color: #1a1f13; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="hdr">
            <h1>{{ \App\Models\Setting::get('site_title', 'Territorial Army') }}</h1>
        </div>
        <div class="body">
            @yield('content')
        </div>
        <div class="ftr">
            @yield('footer', 'Sent by ' . \App\Models\Setting::get('site_title') . ' · ' . now()->format('d M Y'))
        </div>
    </div>
</body>
</html>
