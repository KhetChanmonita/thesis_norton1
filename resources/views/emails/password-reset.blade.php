<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កំណត់ពាក្យសម្ងាត់ - LS Trucking Service</title>
    <style>
        body { margin:0; padding:0; background:#f4f6f8; font-family: Arial, sans-serif; }
        .wrap { max-width:580px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
        .header { background:linear-gradient(135deg,#FF6B00,#FF8A3D); padding:36px 40px; text-align:center; }
        .header-icon { width:64px; height:64px; background:rgba(255,255,255,0.2); border-radius:14px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:14px; }
        .header h1 { color:#fff; margin:0; font-size:22px; font-weight:700; }
        .header p { color:rgba(255,255,255,0.85); margin:6px 0 0; font-size:13px; }
        .body { padding:40px; }
        .greeting { font-size:16px; color:#1E293B; margin-bottom:16px; }
        .message { font-size:14px; color:#475569; line-height:1.8; margin-bottom:28px; }
        .btn-wrap { text-align:center; margin-bottom:28px; }
        .btn { display:inline-block; padding:15px 40px; background:linear-gradient(135deg,#FF6B00,#FF8A3D); color:#fff; text-decoration:none; border-radius:10px; font-size:15px; font-weight:700; letter-spacing:0.3px; }
        .divider { border:none; border-top:1px solid #e2e8f0; margin:24px 0; }
        .link-label { font-size:12px; color:#94a3b8; margin-bottom:8px; }
        .link-text { font-size:12px; color:#FF6B00; word-break:break-all; }
        .warning { background:#fff7ed; border:1px solid #fed7aa; border-radius:10px; padding:14px 18px; font-size:13px; color:#c2410c; margin-bottom:24px; }
        .footer { background:#f8fafc; padding:20px 40px; text-align:center; font-size:12px; color:#94a3b8; border-top:1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <div class="header-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M1 3h15v13H1V3zm15 5h3l3 3v5h-6V8zM5.5 17a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm12 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/></svg>
        </div>
        <h1>LS Trucking Service</h1>
        <p>ប្រព័ន្ធដឹកជញ្ជូនដែលជឿជាក់</p>
    </div>

    <div class="body">
        <div class="greeting">
            សួស្តី <strong>{{ $userName }}</strong>,
        </div>
        <div class="message">
            យើងបានទទួលសំណើកំណត់ពាក្យសម្ងាត់ឡើងវិញសម្រាប់គណនីរបស់អ្នក។
            សូមចុចប៊ូតុងខាងក្រោម ដើម្បីកំណត់ពាក្យសម្ងាត់ថ្មី។
        </div>

        <div class="btn-wrap">
            <a href="{{ $resetUrl }}" class="btn">🔑 &nbsp;កំណត់ពាក្យសម្ងាត់ឡើងវិញ</a>
        </div>

        <div class="warning">
            ⏱ &nbsp;តំណភ្ជាប់នេះនឹងផុតកំណត់ក្នុងរយៈពេល <strong>៦០ នាទី</strong>។
            បើអ្នកមិនបានស្នើសុំកំណត់ពាក្យសម្ងាត់ទេ សូមមិនខ្វល់ — គណនីរបស់អ្នកនៅសុវត្ថិភាព។
        </div>

        <hr class="divider">
        <div class="link-label">ប្រសិនបើប៊ូតុងខាងលើមិនដំណើរការ សូមចម្លង URL ខាងក្រោមទៅកាន់ Browser:</div>
        <div class="link-text">{{ $resetUrl }}</div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} LS Trucking Service · Cambodia
        <br>អ៊ីម៉ែលនេះត្រូវបានផ្ញើដោយស្វ័យប្រវត្តិ សូមមិនឆ្លើយតប។
    </div>
</div>
</body>
</html>
