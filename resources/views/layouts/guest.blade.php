<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Distro IPM') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8ecf4 0%, #dce3f0 50%, #e4e9f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(60,80,140,0.10);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 480px;
        }
        @media (max-width: 480px) {
            .auth-card { padding: 1.8rem 1.3rem; border-radius: 16px; }
            .auth-title { font-size: 1.4rem !important; }
        }
        .auth-title { font-size: 1.7rem; font-weight: 700; color: #1a2340; letter-spacing: -0.5px; }
        .auth-subtitle { color: #7a869a; font-size: 0.97rem; }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4f6ef7;
            box-shadow: 0 0 0 3px rgba(79,110,247,0.10);
        }
        .form-label { font-weight: 500; color: #344054; font-size: 0.9rem; }
        .btn-auth {
            background: linear-gradient(90deg, #4f6ef7 0%, #7c3aed 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: opacity .2s;
        }
        .btn-auth:hover { opacity: 0.9; color: #fff; }
        .btn-auth:disabled { opacity: 0.6; }
        .divider { border-top: 1.5px solid #f0f2f7; margin: 1.5rem 0; }
        .role-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem 1.2rem;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s, background .2s;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.85rem;
            user-select: none;
        }
        .role-card:hover { border-color: #4f6ef7; box-shadow: 0 2px 12px rgba(79,110,247,0.10); }
        .role-card.selected { border-color: #4f6ef7; background: #f0f4ff; box-shadow: 0 2px 12px rgba(79,110,247,0.13); }
        .role-icon {
            width: 48px; height: 48px;
            background: #f0f4ff;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #4f6ef7; flex-shrink: 0;
        }
        .role-title { font-weight: 600; color: #1a2340; font-size: 0.97rem; }
        .role-desc { color: #7a869a; font-size: 0.84rem; }
        .role-radio { margin-left: auto; }
        .role-radio .form-check-input { width: 1.2em; height: 1.2em; border: 2px solid #cdd5e0; }
        .role-radio .form-check-input:checked { background-color: #4f6ef7; border-color: #4f6ef7; }
        .tab-btn {
            flex: 1; padding: 0.55rem; border-radius: 10px; border: none;
            background: transparent; font-weight: 600; color: #7a869a;
            font-size: 0.93rem; transition: all .2s;
        }
        .tab-btn.active { background: #fff; color: #4f6ef7; box-shadow: 0 1px 6px rgba(60,80,140,0.10); }
        .tab-switcher { background: #f0f2f7; border-radius: 12px; padding: 4px; display: flex; gap: 4px; margin-bottom: 1.5rem; }
        .invalid-feedback { font-size: 0.82rem; }
        a.link-muted { color: #4f6ef7; text-decoration: none; font-size: 0.88rem; }
        a.link-muted:hover { text-decoration: underline; }
    </style>
</head>
<body>
    {{ $slot }}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>