<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Paradise of Math')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        :root {
            --pom-violet-deep: #2e1065;
            --pom-violet: #4c1d95;
            --pom-violet-light: #7c3aed;
            --pom-amber: #f59e0b;
            --pom-amber-light: #fbbf24;
        }
        body, .wrapper { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #f4f2fa; }
        .main-sidebar { background: linear-gradient(180deg, var(--pom-violet-deep) 0%, var(--pom-violet) 100%) !important; }
        .brand-link { border-bottom: 1px solid rgba(255,255,255,0.08) !important; }
        .brand-image { max-height: 32px; }
        .brand-text { font-weight: 700; letter-spacing: .2px; }
        .sidebar .nav-sidebar > .nav-item > .nav-link { border-radius: 8px; margin: 2px 8px; color: rgba(255,255,255,0.75); }
        .sidebar .nav-sidebar > .nav-item > .nav-link:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .sidebar .nav-sidebar > .nav-item > .nav-link.active { background: rgba(251, 191, 36, 0.16); color: #fbbf24; box-shadow: inset 3px 0 0 #fbbf24; }
        .sidebar .nav-sidebar .nav-icon { color: inherit; opacity: .9; }
        .nav-sidebar .nav-header { color: rgba(255,255,255,0.35); font-size: .72rem; letter-spacing: .6px; }
        .user-panel .info a { color: #fff; font-weight: 600; }
        .main-header.navbar { background: #fff; border-bottom: 1px solid #ece7f7; }
        .main-header .nav-link { color: #4b4560; }
        .main-header .nav-link:hover { color: var(--pom-violet-light); }
        .content-wrapper { background: #f4f2fa; }
        .small-box, .card { border-radius: 14px; border: 1px solid #ece7f7; box-shadow: 0 1px 2px rgba(46,16,101,0.04), 0 10px 24px -14px rgba(76,29,149,0.14); }
        .card-header { border-radius: 14px 14px 0 0 !important; border-bottom: 1px solid #f0edf9; }
        .btn-brand { background: linear-gradient(135deg, var(--pom-amber-light), var(--pom-amber)); border: none; color: #40206b; font-weight: 700; }
        .main-footer { background: #fff; border-top: 1px solid #ece7f7; color: #7a7391; font-size: .85rem; }
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
    
    @include('layout.header')
    @include('layout.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    @include('layout.footer')

</div>
<!-- ./wrapper -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body> 
</html>