@extends('layout.app')

@section('title', 'Master Data Perangkat & Diagnostik HP Pengguna')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <!-- Header Title Banner -->
        <div class="card border-0 shadow-sm rounded-24 overflow-hidden mb-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #311b92 50%, #4a148c 100%);">
            <div class="card-body p-4 text-white position-relative">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative z-1">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-20 bg-warning text-dark p-3 d-flex align-items-center justify-content-center shadow-lg" style="width: 56px; height: 56px; font-size: 24px; font-weight: 800;">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="mb-0 font-weight-extrabold text-white">Master Data Perangkat &amp; HP Pengguna</h4>
                                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="border-radius: 8px; font-size: 10px;">LIVE MONITOR</span>
                                <span class="badge badge-success text-white font-weight-bold px-2 py-1" style="border-radius: 8px; font-size: 10px;">🔒 SILENT IP GEOLOCATION (TANPA POPUP IZIN)</span>
                            </div>
                            <p class="text-white-50 mb-0 font-weight-medium" style="font-size: 13px;">
                                Daftar seluruh tipe HP, spesifikasi layar, sistem operasi, browser &amp; estimasi lokasi (IP / Kota) dari siapapun yang membuka website <strong>Paradise of Math</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- Header Action Buttons -->
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light btn-sm font-weight-bold rounded-12 shadow-sm d-flex align-items-center gap-2 px-3 py-2" onclick="loadDeviceLogs()">
                            <i class="fas fa-sync-alt text-purple"></i> Refresh Data
                        </button>
                        <button type="button" class="btn btn-danger btn-sm font-weight-bold rounded-12 shadow-sm d-flex align-items-center gap-2 px-3 py-2" onclick="clearAllDeviceLogs()">
                            <i class="fas fa-trash-alt"></i> Hapus Semua Log
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics Cards Grid -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm rounded-16 bg-white h-100 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Total Log Perangkat</span>
                            <h3 class="font-weight-extrabold text-dark mb-0 mt-1" id="stat-total-logs">0</h3>
                        </div>
                        <div class="rounded-14 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #f3e8ff; color: #7c3aed;">
                            <i class="fas fa-list-ul fa-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-muted" style="font-size: 11px;">
                        <i class="fas fa-globe text-primary"></i> Pengunjung landing page
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm rounded-16 bg-white h-100 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Pengguna Mobile (HP)</span>
                            <h3 class="font-weight-extrabold text-success mb-0 mt-1" id="stat-mobile-count">0</h3>
                        </div>
                        <div class="rounded-14 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-mobile-alt fa-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-muted" style="font-size: 11px;">
                        <span id="stat-mobile-percent">0%</span> dari total pengunjung
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm rounded-16 bg-white h-100 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Pengguna Desktop / PC</span>
                            <h3 class="font-weight-extrabold text-info mb-0 mt-1" id="stat-desktop-count">0</h3>
                        </div>
                        <div class="rounded-14 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-laptop fa-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-muted" style="font-size: 11px;">
                        <i class="fas fa-desktop text-info"></i> Akses dari Komputer/Laptop
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm rounded-16 bg-white h-100 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Lokasi IP Terdeteksi</span>
                            <h3 class="font-weight-extrabold text-warning mb-0 mt-1" id="stat-gps-count">0</h3>
                        </div>
                        <div class="rounded-14 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #fef3c7; color: #d97706;">
                            <i class="fas fa-map-marker-alt fa-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-muted" style="font-size: 11px;">
                        <i class="fas fa-shield-alt text-success"></i> Silent / Tanpa Izin Pop-Up
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Toolbar Card -->
        <div class="card border-0 shadow-sm rounded-16 mb-4 bg-white">
            <div class="card-body p-3">
                <div class="row align-items-center gap-2">
                    <div class="col-md-5 mb-2 mb-md-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" id="search-input" class="form-control bg-light border-0 font-weight-medium" placeholder="Cari merk HP, browser, OS, IP, atau kota..." onkeyup="filterDeviceLogs()">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select id="filter-type" class="form-control bg-light border-0 font-weight-bold text-dark" onchange="filterDeviceLogs()">
                            <option value="">Semua Tipe Perangkat</option>
                            <option value="Mobile (HP)">📱 Mobile (HP)</option>
                            <option value="Desktop / PC">💻 Desktop / PC</option>
                            <option value="Tablet">📑 Tablet</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex justify-content-md-end align-items-center gap-2">
                        <span class="badge badge-light p-2 font-weight-bold text-muted" id="filter-status-count">Menampilkan 0 data</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Device List Container (Dalam Bentuk List dengan Button Aksi) -->
        <div id="device-list-container">
            <!-- List items will be rendered dynamically via JS -->
        </div>

    </div>
</div>

<!-- Modal Detail Diagnostik HP Lengkap -->
<div class="modal fade" id="deviceDetailModal" tabindex="-1" role="dialog" aria-labelledby="deviceDetailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-2xl rounded-24 overflow-hidden">
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #1e1b4b, #4c1d95);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-16 bg-warning text-dark p-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; font-weight: 800; font-size: 20px;">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-extrabold text-white mb-0" id="detail-modal-title">Detail Diagnostik System &amp; Perangkat HP</h5>
                        <small class="text-white-50" id="detail-modal-subtitle">ID Log: -</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light" id="detail-modal-body">
                <!-- Content inserted via JS -->
            </div>
            <div class="modal-footer bg-white p-3 border-top-0 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-warning text-dark font-weight-bold rounded-12 px-4" id="detail-modal-copy-btn">
                    <i class="far fa-copy mr-1"></i> Salin Seluruh Info HP
                </button>
                <button type="button" class="btn btn-secondary font-weight-bold rounded-12 px-4" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-12 { border-radius: 12px !important; }
    .rounded-14 { border-radius: 14px !important; }
    .rounded-16 { border-radius: 16px !important; }
    .rounded-20 { border-radius: 20px !important; }
    .rounded-24 { border-radius: 24px !important; }
    .font-weight-extrabold { font-weight: 800 !important; }
    .device-card-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .device-card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px -6px rgba(76, 29, 149, 0.12);
        border-color: #cbd5e1;
    }
    .device-badge-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .spec-pill {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 12px;
    }
    .btn-action-custom {
        border-radius: 12px;
        font-weight: 700;
        font-size: 12px;
        padding: 8px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .btn-action-custom:hover {
        transform: translateY(-1px);
    }
</style>

<script>
    // Sample initial seed logs with Silent IP Geolocation Data
    const defaultSampleLogs = [
        {
            id: 'DEV-1724509100-881',
            time: '24 Agt 2026, 16:15:20 WIB',
            timestamp: Date.now() - 60000,
            deviceType: 'Mobile (HP)',
            brandModel: 'Apple iPhone 15 Pro Max',
            browser: 'Apple Safari 17.4',
            platform: 'iOS 17.4',
            userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1',
            screen: '430 x 932 px',
            viewport: '430 x 932 px',
            dpr: 3,
            language: 'id-ID',
            onlineStatus: 'Online',
            page: 'Landing Page (/informasi)',
            location: {
                ip: '180.252.88.14',
                city: 'Jakarta South',
                region: 'DKI Jakarta',
                country: 'Indonesia',
                org: 'PT Telkomsel',
                lat: -6.2088,
                lng: 106.8456,
                isIpLocation: true,
                mapsUrl: 'https://www.google.com/maps?q=-6.2088,106.8456'
            }
        },
        {
            id: 'DEV-1724508900-412',
            time: '24 Agt 2026, 16:10:05 WIB',
            timestamp: Date.now() - 300000,
            deviceType: 'Mobile (HP)',
            brandModel: 'Samsung Galaxy S23 Ultra',
            browser: 'Google Chrome 124.0',
            platform: 'Android 14',
            userAgent: 'Mozilla/5.0 (Linux; Android 14; SM-S918B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
            screen: '393 x 873 px',
            viewport: '393 x 873 px',
            dpr: 2.75,
            language: 'id-ID',
            onlineStatus: 'Online',
            page: 'Landing Page (/informasi)',
            location: {
                ip: '114.122.45.90',
                city: 'Surabaya',
                region: 'Jawa Timur',
                country: 'Indonesia',
                org: 'PT Indosat Tbk',
                lat: -7.2504,
                lng: 112.7688,
                isIpLocation: true,
                mapsUrl: 'https://www.google.com/maps?q=-7.2504,112.7688'
            }
        },
        {
            id: 'DEV-1724508500-299',
            time: '24 Agt 2026, 15:58:40 WIB',
            timestamp: Date.now() - 900000,
            deviceType: 'Mobile (HP)',
            brandModel: 'Xiaomi Redmi Note 13 Pro',
            browser: 'Google Chrome 123.0',
            platform: 'Android 13',
            userAgent: 'Mozilla/5.0 (Linux; Android 13; 2312DRA50G) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Mobile Safari/537.36',
            screen: '393 x 851 px',
            viewport: '393 x 851 px',
            dpr: 2.75,
            language: 'id-ID',
            onlineStatus: 'Online',
            page: 'Landing Page (/informasi)',
            location: {
                ip: '180.244.190.12',
                city: 'Bandung',
                region: 'Jawa Barat',
                country: 'Indonesia',
                org: 'XL Axiata',
                lat: -6.9175,
                lng: 107.6191,
                isIpLocation: true,
                mapsUrl: 'https://www.google.com/maps?q=-6.9175,107.6191'
            }
        },
        {
            id: 'DEV-1724507100-502',
            time: '24 Agt 2026, 15:32:10 WIB',
            timestamp: Date.now() - 2500000,
            deviceType: 'Desktop / PC',
            brandModel: 'Windows 11 PC / Laptop',
            browser: 'Google Chrome 124.0',
            platform: 'Win32 (Windows 11)',
            userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            screen: '1920 x 1080 px',
            viewport: '1536 x 730 px',
            dpr: 1.25,
            language: 'id-ID',
            onlineStatus: 'Online',
            page: 'Landing Page (/informasi)',
            location: {
                ip: '103.140.22.5',
                city: 'Medan',
                region: 'Sumatera Utara',
                country: 'Indonesia',
                org: 'Biznet Networks',
                lat: 3.5952,
                lng: 98.6722,
                isIpLocation: true,
                mapsUrl: 'https://www.google.com/maps?q=3.5952,98.6722'
            }
        }
    ];

    let activeDeviceLogs = [];

    function getDeviceLogsFromStorage() {
        try {
            const raw = localStorage.getItem('pm_visitor_device_logs');
            if (!raw || JSON.parse(raw).length === 0) {
                localStorage.setItem('pm_visitor_device_logs', JSON.stringify(defaultSampleLogs));
                return defaultSampleLogs;
            }
            return JSON.parse(raw);
        } catch(e) {
            return defaultSampleLogs;
        }
    }

    function saveDeviceLogsToStorage(logs) {
        try {
            localStorage.setItem('pm_visitor_device_logs', JSON.stringify(logs));
        } catch(e) {}
    }

    function loadDeviceLogs() {
        activeDeviceLogs = getDeviceLogsFromStorage();
        updateStatistics(activeDeviceLogs);
        renderDeviceList(activeDeviceLogs);
    }

    function updateStatistics(logs) {
        const total = logs.length;
        const mobile = logs.filter(l => l.deviceType === 'Mobile (HP)').length;
        const desktop = logs.filter(l => l.deviceType === 'Desktop / PC').length;
        const gps = logs.filter(l => l.location && (l.location.city || l.location.lat)).length;

        document.getElementById('stat-total-logs').innerText = total;
        document.getElementById('stat-mobile-count').innerText = mobile;
        document.getElementById('stat-desktop-count').innerText = desktop;
        document.getElementById('stat-gps-count').innerText = gps;

        const mobilePercent = total > 0 ? Math.round((mobile / total) * 100) : 0;
        document.getElementById('stat-mobile-percent').innerText = mobilePercent + '%';
    }

    function renderDeviceList(logs) {
        const container = document.getElementById('device-list-container');
        document.getElementById('filter-status-count').innerText = `Menampilkan ${logs.length} data peranti`;

        if (!logs || logs.length === 0) {
            container.innerHTML = `
                <div class="card border-0 shadow-sm rounded-20 p-5 text-center bg-white">
                    <div class="mb-3 text-muted" style="font-size: 48px;">📱</div>
                    <h5 class="font-weight-extrabold text-dark">Belum ada Log Perangkat</h5>
                    <p class="text-muted mb-3">Siapa saja yang membuka website <strong>informasi/index.blade.php</strong> akan tercatat otomatis di sini secara silent.</p>
                    <div>
                        <button class="btn btn-warning text-dark font-weight-bold rounded-12 px-4" onclick="loadDeviceLogs()">
                            <i class="fas fa-sync-alt mr-1"></i> Muat Ulang Data
                        </button>
                    </div>
                </div>
            `;
            return;
        }

        let html = '';
        logs.forEach((item, index) => {
            const isApple = item.brandModel.includes('Apple') || item.brandModel.includes('iPhone') || item.brandModel.includes('Mac');
            const isAndroid = item.brandModel.includes('Samsung') || item.brandModel.includes('Xiaomi') || item.brandModel.includes('Vivo') || item.brandModel.includes('OPPO') || item.brandModel.includes('Android');
            
            let iconBg = '#f3e8ff';
            let iconColor = '#7c3aed';
            let iconClass = 'fa-mobile-alt';

            if (isApple) {
                iconBg = '#f1f5f9';
                iconColor = '#0f172a';
                iconClass = item.deviceType.includes('Desktop') ? 'fa-laptop' : 'fa-mobile-alt';
            } else if (isAndroid) {
                iconBg = '#dcfce7';
                iconColor = '#15803d';
                iconClass = 'fa-mobile-alt';
            } else if (item.deviceType.includes('Desktop')) {
                iconBg = '#e0f2fe';
                iconColor = '#0369a1';
                iconClass = 'fa-desktop';
            }

            const gpsInfoHtml = item.location && (item.location.city || item.location.lat) ? `
                <span class="badge badge-warning text-dark px-2.5 py-1.5 font-weight-bold" style="border-radius: 8px;">
                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> ${escapeHtml(item.location.city || 'Kota')}, ${escapeHtml(item.location.region || 'Indonesia')} (IP: ${escapeHtml(item.location.ip || 'Terdeteksi')})
                </span>
            ` : `
                <span class="badge badge-light text-muted px-2.5 py-1.5 font-weight-bold" style="border-radius: 8px;">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Mengdeteksi IP Lokasi...
                </span>
            `;

            html += `
                <div class="device-card-item">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="device-badge-icon" style="background: ${iconBg}; color: ${iconColor};">
                                <i class="fas ${iconClass}"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h6 class="font-weight-extrabold text-dark mb-0" style="font-size: 15px;">${escapeHtml(item.brandModel)}</h6>
                                    <span class="badge text-white px-2 py-1 font-weight-bold" style="background: #6d28d9; border-radius: 6px; font-size: 10px;">${escapeHtml(item.deviceType)}</span>
                                    <span class="badge badge-success text-white px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 10px;">🟢 Online</span>
                                </div>
                                <div class="text-muted font-weight-medium" style="font-size: 11px;">
                                    <i class="far fa-clock mr-1"></i> Diakses: ${escapeHtml(item.time)}
                                </div>
                            </div>
                        </div>
                        <div>
                            ${gpsInfoHtml}
                        </div>
                    </div>

                    <!-- Specifications Grid -->
                    <div class="row mb-3">
                        <div class="col-md-3 col-6 mb-2">
                            <div class="spec-pill">
                                <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 10px;">OS &amp; Platform</span>
                                <strong class="text-dark">${escapeHtml(item.platform)}</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="spec-pill">
                                <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 10px;">Browser</span>
                                <strong class="text-dark">${escapeHtml(item.browser)}</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="spec-pill">
                                <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 10px;">Resolusi Layar</span>
                                <strong class="text-dark">${escapeHtml(item.screen)} (DPR ${item.dpr || 1}x)</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="spec-pill">
                                <span class="text-muted font-weight-bold d-block text-uppercase" style="font-size: 10px;">Network / Provider</span>
                                <strong class="text-dark">${escapeHtml((item.location && item.location.org) || 'Seluler / WiFi')}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Button Aksi (Action Buttons Bar) -->
                    <div class="pt-2 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="btn btn-action-custom btn-primary text-white" onclick="showDeviceDetail('${item.id}')">
                                <i class="fas fa-eye"></i> Lihat Detail Diagnostik
                            </button>

                            <button type="button" class="btn btn-action-custom btn-warning text-dark" onclick="copySingleDeviceDiagnostics('${item.id}')">
                                <i class="far fa-copy"></i> Salin Diagnostik System
                            </button>

                            ${item.location && item.location.mapsUrl ? `
                                <a href="${item.location.mapsUrl}" target="_blank" class="btn btn-action-custom btn-success text-white">
                                    <i class="fas fa-map-marked-alt"></i> Buka Peta Google Maps
                                </a>
                            ` : ''}
                        </div>

                        <div>
                            <button type="button" class="btn btn-action-custom btn-outline-danger" onclick="deleteSingleDeviceLog('${item.id}')">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    function filterDeviceLogs() {
        const query = document.getElementById('search-input').value.toLowerCase();
        const typeFilter = document.getElementById('filter-type').value;

        const filtered = activeDeviceLogs.filter(item => {
            const matchesQuery = !query || 
                item.brandModel.toLowerCase().includes(query) ||
                item.browser.toLowerCase().includes(query) ||
                item.platform.toLowerCase().includes(query) ||
                item.userAgent.toLowerCase().includes(query) ||
                (item.location && (item.location.city + ' ' + item.location.region + ' ' + item.location.ip).toLowerCase().includes(query));

            const matchesType = !typeFilter || item.deviceType === typeFilter;

            return matchesQuery && matchesType;
        });

        renderDeviceList(filtered);
    }

    function showDeviceDetail(id) {
        const item = activeDeviceLogs.find(l => l.id === id);
        if (!item) return;

        document.getElementById('detail-modal-title').innerText = item.brandModel;
        document.getElementById('detail-modal-subtitle').innerText = `ID Log: ${item.id} • Waktu: ${item.time}`;

        let gpsHtml = item.location && (item.location.city || item.location.lat) ? `
            <div class="alert alert-warning border-0 rounded-14 mb-3">
                <h6 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Lokasi IP Terdeteksi (Silent / Tanpa Pop-up Permission)</h6>
                <p class="mb-1 text-dark" style="font-size: 13px;">
                    <strong>Kota / Wilayah:</strong> ${escapeHtml(item.location.city)}, ${escapeHtml(item.location.region)}, ${escapeHtml(item.location.country)}<br>
                    <strong>IP Address:</strong> <code>${escapeHtml(item.location.ip)}</code> • <strong>ISP:</strong> ${escapeHtml(item.location.org)}<br>
                    <strong>Koordinat Peta:</strong> ${item.location.lat}, ${item.location.lng}
                </p>
                <a href="${item.location.mapsUrl}" target="_blank" class="btn btn-dark btn-sm font-weight-bold rounded-10 mt-1">
                    <i class="fas fa-external-link-alt mr-1"></i> Buka Google Maps Peta
                </a>
            </div>
        ` : `
            <div class="alert alert-light border rounded-14 mb-3 text-muted">
                <i class="fas fa-info-circle mr-1"></i> Mengambil IP Lokasi dalam latar belakang...
            </div>
        `;

        document.getElementById('detail-modal-body').innerHTML = `
            ${gpsHtml}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="bg-white p-3 rounded-16 border font-size-12">
                        <h6 class="font-weight-extrabold text-purple mb-2">📱 Informasi Perangkat HP</h6>
                        <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                            <tr><td class="text-muted">Merk / Model:</td><td><strong>${escapeHtml(item.brandModel)}</strong></td></tr>
                            <tr><td class="text-muted">Tipe Device:</td><td><strong>${escapeHtml(item.deviceType)}</strong></td></tr>
                            <tr><td class="text-muted">Sistem Operasi:</td><td><strong>${escapeHtml(item.platform)}</strong></td></tr>
                            <tr><td class="text-muted">Browser:</td><td><strong>${escapeHtml(item.browser)}</strong></td></tr>
                            <tr><td class="text-muted">Status Koneksi:</td><td><strong class="text-success">${escapeHtml(item.onlineStatus)}</strong></td></tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="bg-white p-3 rounded-16 border font-size-12">
                        <h6 class="font-weight-extrabold text-purple mb-2">🖥️ Tampilan &amp; Layar HP</h6>
                        <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                            <tr><td class="text-muted">Resolusi Layar:</td><td><strong>${escapeHtml(item.screen)}</strong></td></tr>
                            <tr><td class="text-muted">Ukuran Viewport:</td><td><strong>${escapeHtml(item.viewport)}</strong></td></tr>
                            <tr><td class="text-muted">Pixel Ratio (DPR):</td><td><strong>${item.dpr}x</strong></td></tr>
                            <tr><td class="text-muted">Bahasa Browser:</td><td><strong>${escapeHtml(item.language)}</strong></td></tr>
                            <tr><td class="text-muted">Halaman Dikunjungi:</td><td><strong>${escapeHtml(item.page)}</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white p-3 rounded-16 border">
                <h6 class="font-weight-extrabold text-purple mb-1">🌐 User-Agent Full String</h6>
                <code class="d-block p-2 bg-light rounded-10 text-wrap text-break font-size-11" style="word-break: break-all; font-size: 11px;">
                    ${escapeHtml(item.userAgent)}
                </code>
            </div>
        `;

        document.getElementById('detail-modal-copy-btn').onclick = function() {
            copySingleDeviceDiagnostics(item.id);
        };

        $('#deviceDetailModal').modal('show');
    }

    function copySingleDeviceDiagnostics(id) {
        const item = activeDeviceLogs.find(l => l.id === id);
        if (!item) return;

        let locStr = item.location && (item.location.city || item.location.lat) ? 
            `• Kota/Wilayah: ${item.location.city}, ${item.location.region}, ${item.location.country}\n• IP Address: ${item.location.ip}\n• ISP / Provider: ${item.location.org}\n• Maps Link: ${item.location.mapsUrl}` :
            `• Lokasi IP: Sedang dideteksi`;

        const text = `=== SYSTEM DIAGNOSTIC INFO PERANGKAT ===\n` +
                     `App: Paradise of Math v1.1.0\n` +
                     `Perangkat HP: ${item.brandModel}\n` +
                     `Tipe Device: ${item.deviceType}\n` +
                     `Platform OS: ${item.platform}\n` +
                     `Browser: ${item.browser}\n` +
                     `Resolusi Layar: ${item.screen} (DPR ${item.dpr}x)\n` +
                     `Viewport: ${item.viewport}\n` +
                     `Waktu Akses: ${item.time}\n` +
                     `Status Internet: ${item.onlineStatus}\n` +
                     `------------------------------\n` +
                     `LOKASI PERANGKAT (SILENT IP GEOLOCATION):\n${locStr}\n` +
                     `------------------------------\n` +
                     `User-Agent: ${item.userAgent}\n` +
                     `==============================`;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                alert("✨ Info Diagnostik HP & Lokasi IP berhasil disalin ke Clipboard!");
            }).catch(function() {
                prompt("Salin manual data diagnostik berikut:", text);
            });
        } else {
            prompt("Salin manual data diagnostik berikut:", text);
        }
    }

    function deleteSingleDeviceLog(id) {
        if (!confirm("Apakah Anda yakin ingin menghapus log data perangkat ini?")) return;
        activeDeviceLogs = activeDeviceLogs.filter(l => l.id !== id);
        saveDeviceLogsToStorage(activeDeviceLogs);
        updateStatistics(activeDeviceLogs);
        renderDeviceList(activeDeviceLogs);
    }

    function clearAllDeviceLogs() {
        if (!confirm("⚠️ Apakah Anda yakin ingin menghapus SELURUH log data perangkat?")) return;
        activeDeviceLogs = [];
        saveDeviceLogsToStorage([]);
        updateStatistics([]);
        renderDeviceList([]);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadDeviceLogs();

        // Listen for new logs dynamically
        window.addEventListener('storage', loadDeviceLogs);
        window.addEventListener('pm_device_logged', loadDeviceLogs);
    });
</script>
@endsection
