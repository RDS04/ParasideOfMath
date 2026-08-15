@extends('layout.app')

@section('content')

<style>
    /* =========================
       ACCOUNT SETTINGS
    ========================== */
    .settings-page {
        padding: 28px 30px 40px;
    }

    .settings-header {
        margin-bottom: 26px;
    }

    .settings-header .title-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .settings-header .title-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #7c3aed, #4c1d95);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 8px 20px rgba(76, 29, 149, 0.18);
    }

    .settings-header h1 {
        margin: 0;
        font-size: 29px;
        font-weight: 700;
        color: #1f2937;
    }

    .settings-header p {
        margin: 5px 0 0 64px;
        color: #6b7280;
        font-size: 14px;
    }

    /* Main settings card */
    .settings-card {
        max-width: 820px;
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(31, 41, 55, 0.07);
        overflow: hidden;
    }

    .settings-card-header {
        padding: 18px 22px;
        background: linear-gradient(135deg, #4c1d95, #5b21b6);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .settings-card-header .header-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .settings-card-header .header-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,0.14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .settings-card-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #fff;
    }

    .settings-card-header small {
        color: rgba(255,255,255,0.72);
        font-size: 12px;
    }

    /* Setting item */
    .setting-item {
        padding: 20px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none !important;
        color: inherit !important;
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.22s ease;
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-item:hover {
        background: #faf8ff;
        padding-left: 26px;
    }

    .setting-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .setting-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .setting-icon.email {
        background: #ede9fe;
        color: #5b21b6;
    }

    .setting-icon.password {
        background: #fef3c7;
        color: #d97706;
    }

    .setting-info strong {
        display: block;
        color: #1f2937;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .setting-info span {
        color: #6b7280;
        font-size: 13px;
    }

    .setting-arrow {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f5f3ff;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .setting-item:hover .setting-arrow {
        background: #6d28d9;
        color: #fff;
        transform: translateX(2px);
    }

    /* Success alert */
    .settings-alert {
        max-width: 820px;
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 18px rgba(16, 185, 129, 0.10);
        margin-bottom: 20px;
    }

    /* =========================
       MODAL
    ========================== */
    .settings-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.18);
    }

    .settings-modal .modal-header {
        padding: 18px 22px;
        border-bottom: none;
    }

    .settings-modal .modal-title {
        font-size: 17px;
        font-weight: 600;
    }

    .settings-modal .modal-body {
        padding: 24px;
        background: #fff;
    }

    .settings-modal .modal-footer {
        padding: 16px 24px;
        background: #fafafa;
        border-top: 1px solid #eee;
    }

    .settings-modal label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .settings-modal .form-control {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .settings-modal .form-control:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.10);
    }

    .settings-modal .btn {
        border-radius: 9px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-purple {
        background: #4c1d95;
        color: #fff;
        border: none;
    }

    .btn-purple:hover {
        background: #3b1478;
        color: #fff;
    }

    .btn-gold {
        background: #fbbf24;
        color: #4c1d95;
        border: none;
    }

    .btn-gold:hover {
        background: #f59e0b;
        color: #4c1d95;
    }

    .current-email {
        background: #f8f7ff;
        border: 1px solid #e9e5ff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .settings-page {
            padding: 20px 15px 30px;
        }

        .settings-header h1 {
            font-size: 24px;
        }

        .settings-header p {
            margin-left: 0;
            margin-top: 8px;
        }

        .settings-card {
            max-width: 100%;
        }

        .setting-item {
            padding: 17px;
        }

        .setting-item:hover {
            padding-left: 20px;
        }

        <style>
    
    }
    /* =========================
       ACCOUNT SETTINGS
    ========================== */
    .settings-page {
        padding: 28px 30px 40px;
    }

    .settings-header {
        margin-bottom: 26px;
    }

    .settings-header .title-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .settings-header .title-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #7c3aed, #4c1d95);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(76, 29, 149, 0.18);
    }

    .settings-header h1 {
        margin: 0;
        font-size: 29px;
        font-weight: 700;
        color: #1f2937;
    }

    .settings-header p {
        margin: 5px 0 0 64px;
        color: #6b7280;
        font-size: 14px;
    }

    /* =========================
       SETTINGS CARD
    ========================== */
    .settings-card {
        max-width: 820px;
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(31, 41, 55, 0.07);
        overflow: hidden;
    }

    .settings-card-header {
        padding: 18px 22px;
        background: linear-gradient(135deg, #4c1d95, #5b21b6);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .settings-card-header .header-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .settings-card-header .header-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,0.14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .settings-card-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #fff;
    }

    .settings-card-header small {
        color: rgba(255,255,255,0.72);
        font-size: 12px;
    }

    /* =========================
       SETTING ITEM
    ========================== */
    .setting-item {
        padding: 20px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none !important;
        color: inherit !important;
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.22s ease;
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-item:hover {
        background: #faf8ff;
        padding-left: 26px;
    }

    .setting-left {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }

    .setting-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .setting-icon.email {
        background: #ede9fe;
        color: #5b21b6;
    }

    .setting-icon.password {
        background: #fef3c7;
        color: #d97706;
    }

    .setting-info {
        min-width: 0;
    }

    .setting-info strong {
        display: block;
        color: #1f2937;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .setting-info span {
        display: block;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.55;
    }

    .setting-arrow {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f5f3ff;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: 12px;
        transition: all 0.2s ease;
    }

    .setting-item:hover .setting-arrow {
        background: #6d28d9;
        color: #fff;
        transform: translateX(2px);
    }

    /* =========================
       ALERT
    ========================== */
    .settings-alert {
        max-width: 820px;
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 18px rgba(16, 185, 129, 0.10);
        margin-bottom: 20px;
    }

    /* =========================
       MODAL
    ========================== */
    .settings-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.18);
    }

    .settings-modal .modal-header {
        padding: 18px 22px;
        border-bottom: none;
    }

    .settings-modal .modal-title {
        font-size: 17px;
        font-weight: 600;
    }

    .settings-modal .modal-body {
        padding: 24px;
        background: #fff;
    }

    .settings-modal .modal-footer {
        padding: 16px 24px;
        background: #fafafa;
        border-top: 1px solid #eee;
    }

    .settings-modal label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .settings-modal .form-control {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .settings-modal .form-control:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.10);
    }

    .settings-modal .btn {
        border-radius: 9px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-purple {
        background: #4c1d95;
        color: #fff;
        border: none;
    }

    .btn-purple:hover {
        background: #3b1478;
        color: #fff;
    }

    .btn-gold {
        background: #fbbf24;
        color: #4c1d95;
        border: none;
    }

    .btn-gold:hover {
        background: #f59e0b;
        color: #4c1d95;
    }

    .current-email {
        background: #f8f7ff;
        border: 1px solid #e9e5ff;
    }

    /* ==================================================
       MOBILE
    ================================================== */
    @media (max-width: 767.98px) {

        /* Area utama */
        .settings-page {
            padding: 18px 14px 85px !important;
        }

        /*
         * Menghindari halaman mobile terlalu tinggi
         * karena min-height bawaan layout.
         */
        .content-wrapper {
            min-height: calc(100vh - 105px) !important;
        }

        /* =========================
           PAGE HEADER
        ========================== */
        .settings-header {
            margin-bottom: 18px;
        }

        .settings-header .title-row {
            gap: 11px;
        }

        .settings-header .title-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            font-size: 19px;
            box-shadow: 0 6px 15px rgba(76, 29, 149, 0.16);
        }

        .settings-header h1 {
            font-size: 23px;
            line-height: 1.2;
        }

        .settings-header p {
            margin: 8px 0 0 0;
            font-size: 12.5px;
            line-height: 1.5;
            max-width: 330px;
        }

        /* =========================
           CARD
        ========================== */
        .settings-card {
            width: 100%;
            max-width: none;
            border-radius: 16px;
            box-shadow: 0 7px 22px rgba(31, 41, 55, 0.07);
        }

        .settings-card-header {
            padding: 15px 16px;
        }

        .settings-card-header .header-left {
            gap: 10px;
        }

        .settings-card-header .header-icon {
            width: 35px;
            height: 35px;
            border-radius: 9px;
            font-size: 14px;
        }

        .settings-card-header h3 {
            font-size: 15px;
        }

        .settings-card-header small {
            display: block;
            font-size: 10.5px;
            margin-top: 2px;
        }

        .settings-card-header > i {
            font-size: 15px;
        }

        /* =========================
           SETTING ITEM MOBILE
        ========================== */
        .setting-item {
            padding: 16px 15px;
        }

        .setting-item:hover {
            padding-left: 15px;
            background: #fff;
        }

        .setting-left {
            gap: 12px;
        }

        .setting-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            font-size: 16px;
        }

        .setting-info strong {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .setting-info span {
            font-size: 11.5px;
            line-height: 1.45;

            /*
             * Batasi lebar supaya teks tidak
             * terlalu dekat dengan tombol panah.
             */
            max-width: 190px;
        }

        .setting-arrow {
            width: 31px;
            height: 31px;
            border-radius: 9px;
            margin-left: 8px;
            font-size: 11px;
        }

        /* =========================
           ALERT MOBILE
        ========================== */
        .settings-alert {
            margin-bottom: 15px;
            font-size: 13px;
            padding: 11px 13px;
        }

        /* =========================
           MODAL MOBILE
        ========================== */
        .settings-modal .modal-dialog {
            margin: 12px;
        }

        .settings-modal .modal-content {
            border-radius: 16px;
        }

        .settings-modal .modal-header {
            padding: 15px 17px;
        }

        .settings-modal .modal-title {
            font-size: 15px;
        }

        .settings-modal .modal-body {
            padding: 18px 17px;
        }

        .settings-modal .modal-footer {
            padding: 13px 17px;
            gap: 7px;
        }

        .settings-modal .modal-footer .btn {
            flex: 1;
            padding: 9px 12px;
            font-size: 12px;
        }

        .settings-modal label {
            font-size: 12px;
        }

        .settings-modal .form-control {
            height: 42px;
            font-size: 13px;
        }
    }

    /* ==================================================
       EXTRA SMALL PHONE
       Contoh: 320px - 360px
    ================================================== */
    @media (max-width: 360px) {

        .settings-page {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .settings-header h1 {
            font-size: 21px;
        }

        .settings-header p {
            font-size: 12px;
        }

        .settings-card-header {
            padding: 14px 13px;
        }

        .setting-item {
            padding: 15px 12px;
        }

        .setting-left {
            gap: 10px;
        }

        .setting-icon {
            width: 39px;
            height: 39px;
            font-size: 15px;
        }

        .setting-info strong {
            font-size: 13px;
        }

        .setting-info span {
            font-size: 10.5px;
            max-width: 165px;
        }

        .setting-arrow {
            width: 29px;
            height: 29px;
            margin-left: 5px;
        }
    }
</style>

<div class="content-wrapper">
    <div class="settings-page">

        {{-- HEADER --}}
        <div class="settings-header">
            <div class="title-row">
                <div class="title-icon">
                    <i class="fas fa-cog"></i>
                </div>

                <div>
                    <h1>Pengaturan Akun</h1>
                </div>
            </div>

            <p>
                Kelola informasi akun dan keamanan password kamu.
            </p>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success settings-alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- SETTINGS CARD --}}
        <div class="settings-card">

            {{-- CARD HEADER --}}
            <div class="settings-card-header">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>

                    <div>
                        <h3>Menu Pengaturan</h3>
                        <small>Kelola informasi akun kamu</small>
                    </div>
                </div>

                <i class="fas fa-shield-alt" style="opacity:.55;"></i>
            </div>

            {{-- EMAIL --}}
            <a href="#"
               class="setting-item"
               data-toggle="modal"
               data-target="#modalEmail">

                <div class="setting-left">

                    <div class="setting-icon email">
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="setting-info">
                        <strong>Ganti Email</strong>
                        <span>Perbarui alamat email yang digunakan pada akun.</span>
                    </div>

                </div>

                <div class="setting-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>

            </a>

            {{-- PASSWORD --}}
            <a href="#"
               class="setting-item"
               data-toggle="modal"
               data-target="#modalPassword">

                <div class="setting-left">

                    <div class="setting-icon password">
                        <i class="fas fa-key"></i>
                    </div>

                    <div class="setting-info">
                        <strong>Ganti Password</strong>
                        <span>Ubah password untuk menjaga keamanan akun.</span>
                    </div>

                </div>

                <div class="setting-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>

            </a>

        </div>

    </div>
</div>


{{-- =========================================================
     MODAL GANTI EMAIL
========================================================= --}}
<div class="modal fade settings-modal"
     id="modalEmail"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form action="{{ route('pengaturan.email') }}" method="POST">

                @csrf
                @method('PUT')

                {{-- MODAL HEADER --}}
                <div class="modal-header"
                     style="background:linear-gradient(135deg,#4c1d95,#6d28d9);color:#fff;">

                    <h5 class="modal-title">
                        <i class="fas fa-envelope mr-2"></i>
                        Ubah Alamat Email
                    </h5>

                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            style="color:#fff;opacity:1;">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                {{-- MODAL BODY --}}
                <div class="modal-body">

                    <div class="form-group">
                        <label>Email Saat Ini</label>

                        <input type="text"
                               class="form-control current-email"
                               value="{{ $currentUser->email }}"
                               disabled>
                    </div>

                    <div class="form-group">
                        <label>Email Baru</label>

                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="Masukkan email baru"
                               required>

                        @error('email')
                            <span class="text-danger small">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label>Kata Sandi (konfirmasi)</label>

                        <input type="password"
                               name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Masukkan password saat ini"
                               required>

                        @error('current_password')
                            <span class="text-danger small">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>

                {{-- MODAL FOOTER --}}
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-purple">

                        <i class="fas fa-save mr-1"></i>
                        Simpan Email

                    </button>

                </div>

            </form>

        </div>
    </div>
</div>


{{-- =========================================================
     MODAL GANTI PASSWORD
========================================================= --}}
<div class="modal fade settings-modal"
     id="modalPassword"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form action="{{ route('pengaturan.password') }}" method="POST">

                @csrf
                @method('PUT')

                {{-- MODAL HEADER --}}
                <div class="modal-header"
                     style="background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#4c1d95;">

                    <h5 class="modal-title">
                        <i class="fas fa-key mr-2"></i>
                        Ubah Kata Sandi
                    </h5>

                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            style="color:#4c1d95;opacity:1;">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                {{-- MODAL BODY --}}
                <div class="modal-body">

                    <div class="form-group">
                        <label>Kata Sandi Saat Ini</label>

                        <input type="password"
                               name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Masukkan password saat ini"
                               required>

                        @error('current_password')
                            <span class="text-danger small">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kata Sandi Baru</label>

                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Masukkan password baru"
                               required>

                        @error('password')
                            <span class="text-danger small">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label>Konfirmasi Kata Sandi Baru</label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password baru"
                               required>
                    </div>

                </div>

                {{-- MODAL FOOTER --}}
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-gold">

                        <i class="fas fa-lock mr-1"></i>
                        Simpan Kata Sandi

                    </button>

                </div>

            </form>

        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // Kalau ada error validasi dari salah satu form,
    // otomatis buka modal terkait.

    @if ($errors->has('password'))

        $('#modalPassword').modal('show');

    @elseif ($errors->has('email') || $errors->has('current_password'))

        $('#modalEmail').modal('show');

    @endif

});
</script>

@endsection