<!-- Modal Perizinan Sistem Mobile (Notifikasi & Lokasi Presisi GPS) -->
<div class="modal fade" id="mobilePermissionModal" tabindex="-1" role="dialog" aria-labelledby="mobilePermissionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content border-0 rounded-24 shadow-2xl overflow-hidden" style="background: #ffffff;">
            
            <!-- Modal Header Banner -->
            <div class="p-4 text-white position-relative" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #7c3aed 100%);">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge badge-warning text-dark font-weight-extrabold px-3 py-1 text-uppercase" style="border-radius: 8px; font-size: 10px; letter-spacing: 0.5px;">
                        <i class="fas fa-shield-alt mr-1"></i> Perizinan Sistem Mobile HP
                    </span>
                    <button type="button" class="close text-white opacity-75 hover-opacity-100" data-dismiss="modal" aria-label="Close" onclick="closePermissionModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="font-weight-extrabold text-white mb-1" id="mobilePermissionModalLabel" style="font-size: 20px;">
                    🔔 Active System Permissions
                </h5>
                <p class="mb-0 text-white-50" style="font-size: 13px; line-height: 1.5;">
                    Aktifkan perizinan <strong>Notifikasi HP</strong> dan <strong>Lokasi Presisi GPS</strong> untuk mendapatkan pengingat les & rekomendasi lokasi terdekat.
                </p>
            </div>

            <!-- Modal Body Content -->
            <div class="modal-body p-4 bg-light">
                
                <!-- Permission Card 1: Notifikasi -->
                <div class="bg-white p-3 rounded-16 border shadow-xs mb-3 transition-all hover-shadow-md">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-12 p-3 text-purple d-flex align-items-center justify-content-center" style="background: #f3e8ff; width: 48px; height: 48px; min-width: 48px;">
                            <i class="fas fa-bell fa-lg text-purple"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="font-weight-extrabold text-dark mb-0" style="font-size: 14px;">1. Notifikasi Sistem HP</h6>
                                <span id="notif-perm-badge" class="badge badge-secondary px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 10px;">
                                    Belum Diaktifkan
                                </span>
                            </div>
                            <p class="text-muted mb-2" style="font-size: 12px; line-height: 1.4;">
                                Menerima notifikasi pengingat jadwal les, info materi baru, dan pemberitahuan dari tutor les secara langsung di HP.
                            </p>
                            <button type="button" class="btn btn-outline-purple btn-sm font-weight-bold rounded-10 px-3" onclick="requestNotificationPermissionOnly()">
                                <i class="fas fa-bell mr-1"></i> Minta Izin Notifikasi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Permission Card 2: Lokasi Presisi GPS -->
                <div class="bg-white p-3 rounded-16 border shadow-xs mb-3 transition-all hover-shadow-md">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-12 p-3 text-warning d-flex align-items-center justify-content-center" style="background: #fef3c7; width: 48px; height: 48px; min-width: 48px;">
                            <i class="fas fa-location-arrow fa-lg text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="font-weight-extrabold text-dark mb-0" style="font-size: 14px;">2. Lokasi Presisi GPS Hardware</h6>
                                <span id="location-perm-badge" class="badge badge-secondary px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 10px;">
                                    Belum Diaktifkan
                                </span>
                            </div>
                            <p class="text-muted mb-2" style="font-size: 12px; line-height: 1.4;">
                                Mendeteksi titik koordinat GPS fisik lokasi HP Anda untuk penyesuaian lokasi les dan peta presisi.
                            </p>
                            <button type="button" class="btn btn-outline-warning text-dark btn-sm font-weight-bold rounded-10 px-3" onclick="requestLocationPermissionOnly()">
                                <i class="fas fa-map-marker-alt mr-1"></i> Minta Izin Lokasi GPS
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Primary Combined Action Button -->
                <button type="button" class="btn btn-block btn-purple font-weight-extrabold rounded-14 py-3 shadow-lg text-white d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 100%); border: none; font-size: 15px;" onclick="requestAllMobilePermissions()">
                    <i class="fas fa-check-circle text-warning fa-lg"></i>
                    <span>Aktifkan Semua Perizinan Mobile HP</span>
                </button>

                <div class="text-center mt-2">
                    <button type="button" class="btn btn-link btn-sm text-muted font-weight-bold text-decoration-none" onclick="closePermissionModal()" style="font-size: 12px;">
                        Nanti Saja (Lewati Saat Ini)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkCurrentPermissionsStatus() {
        // Check Notification Permission
        var notifBadge = document.getElementById('notif-perm-badge');
        if (notifBadge && window.Notification) {
            if (Notification.permission === 'granted') {
                notifBadge.className = 'badge badge-success text-white px-2 py-1 font-weight-bold';
                notifBadge.innerHTML = '🟢 Diizinkan';
            } else if (Notification.permission === 'denied') {
                notifBadge.className = 'badge badge-danger text-white px-2 py-1 font-weight-bold';
                notifBadge.innerHTML = '🔴 Ditolak Sistem';
            }
        }

        // Check Geolocation Permission
        var locBadge = document.getElementById('location-perm-badge');
        if (locBadge && navigator.permissions) {
            navigator.permissions.query({ name: 'geolocation' }).then(function(result) {
                if (result.state === 'granted') {
                    locBadge.className = 'badge badge-success text-white px-2 py-1 font-weight-bold';
                    locBadge.innerHTML = '🟢 Diizinkan';
                } else if (result.state === 'denied') {
                    locBadge.className = 'badge badge-danger text-white px-2 py-1 font-weight-bold';
                    locBadge.innerHTML = '🔴 Ditolak Sistem';
                }
            }).catch(function(e) {});
        }
    }

    function requestNotificationPermissionOnly() {
        if (!('Notification' in window)) {
            alert('Browser Anda tidak mendukung notifikasi.');
            return;
        }

        Notification.requestPermission().then(function(permission) {
            checkCurrentPermissionsStatus();
            if (permission === 'granted') {
                showPermissionSuccessToast('✨ Izin Notifikasi HP berhasil diaktifkan!');
            }
        });
    }

    function requestLocationPermissionOnly() {
        if (!('geolocation' in navigator)) {
            alert('Browser Anda tidak mendukung lokasi GPS.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                checkCurrentPermissionsStatus();
                showPermissionSuccessToast('✨ Izin Lokasi Presisi GPS berhasil diaktifkan!');
                
                // Reverse geocode and sync to server
                if (pos && pos.coords) {
                    syncGpsLocationToServer(pos.coords.latitude, pos.coords.longitude);
                }
            },
            function(err) {
                checkCurrentPermissionsStatus();
                if (err.code === err.PERMISSION_DENIED) {
                    alert('Izin lokasi ditolak pada pengaturan sistem browser HP Anda.');
                }
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
        );
    }

    function requestAllMobilePermissions() {
        // 1. Request Notification
        if ('Notification' in window && Notification.permission !== 'granted') {
            Notification.requestPermission().then(function(perm) {
                checkCurrentPermissionsStatus();
            });
        }

        // 2. Request Location GPS
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    checkCurrentPermissionsStatus();
                    if (pos && pos.coords) {
                        syncGpsLocationToServer(pos.coords.latitude, pos.coords.longitude);
                    }
                    showPermissionSuccessToast('✨ Seluruh Perizinan Notifikasi & Lokasi HP Berhasil Diaktifkan!');
                    setTimeout(closePermissionModal, 1500);
                },
                function(err) {
                    checkCurrentPermissionsStatus();
                    showPermissionSuccessToast('✨ Perizinan Notifikasi Berhasil Diproses!');
                    setTimeout(closePermissionModal, 1500);
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
            );
        } else {
            setTimeout(closePermissionModal, 1500);
        }
    }

    function syncGpsLocationToServer(lat, lng) {
        try {
            fetch('https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=' + lat + '&longitude=' + lng + '&localityLanguage=id')
                .then(function(r) { return r.json(); })
                .then(function(geoData) {
                    var city = geoData.city || geoData.locality || geoData.principalSubdivision || 'Kota Terdeteksi';
                    var region = geoData.principalSubdivision || 'Indonesia';
                    var country = geoData.countryName || 'Indonesia';

                    var payload = {
                        logCode: 'DEV-USER-' + Date.now(),
                        deviceType: (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(navigator.userAgent)) ? 'Tablet' : ((/Mobile|iP(hone|od)|Android/i.test(navigator.userAgent)) ? 'Mobile (HP)' : 'Desktop / PC'),
                        brandModel: (window.navigator.userAgentData && window.navigator.userAgentData.brands) ? 'Mobile Device' : 'Perangkat HP / Komputer',
                        browser: navigator.userAgent.includes('Chrome') ? 'Google Chrome' : 'Web Browser',
                        platform: navigator.platform || 'N/A',
                        userAgent: navigator.userAgent,
                        screen: window.screen.width + ' x ' + window.screen.height + ' px',
                        viewport: window.innerWidth + ' x ' + window.innerHeight + ' px',
                        dpr: window.devicePixelRatio || 1,
                        language: navigator.language || 'id-ID',
                        onlineStatus: 'Online',
                        page: window.location.pathname,
                        city: city,
                        region: region,
                        country: country,
                        lat: lat,
                        lng: lng,
                        mapsUrl: 'https://www.google.com/maps?q=' + lat + ',' + lng
                    };

                    var endpointUrl = "{{ url('/api/device-log/store') }}";
                    fetch(endpointUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payload)
                    }).catch(function(e){});
                }).catch(function(e){});
        } catch(e) {}
    }

    function showPermissionSuccessToast(msg) {
        var toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 right-0 p-3';
        toast.style.zIndex = '999999';
        toast.innerHTML = `
            <div class="toast show bg-success text-white border-0 rounded-14 shadow-lg p-3 d-flex align-items-center gap-2">
                <i class="fas fa-check-circle fa-lg"></i>
                <span class="font-weight-bold" style="font-size: 13px;">${msg}</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function closePermissionModal() {
        localStorage.setItem('pm_mobile_permission_prompted', 'true');
        if (window.jQuery && $('#mobilePermissionModal').modal) {
            $('#mobilePermissionModal').modal('hide');
        } else {
            var modalEl = document.getElementById('mobilePermissionModal');
            if (modalEl) {
                modalEl.style.display = 'none';
                modalEl.classList.remove('show');
                document.body.classList.remove('modal-open');
            }
        }
    }

    function triggerMobilePermissionModalManual() {
        checkCurrentPermissionsStatus();
        if (window.jQuery && $('#mobilePermissionModal').modal) {
            $('#mobilePermissionModal').modal('show');
        } else {
            var modalEl = document.getElementById('mobilePermissionModal');
            if (modalEl) {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
                document.body.classList.add('modal-open');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        checkCurrentPermissionsStatus();

        // Tampilkan otomatis untuk siswa yang baru daftar / login jika belum diminta
        var wasPrompted = localStorage.getItem('pm_mobile_permission_prompted');
        var isSiswaSession = @json(auth()->guard('siswa')->check() || session()->has('success') || request()->routeIs('siswa.*'));

        if (isSiswaSession && !wasPrompted) {
            setTimeout(function() {
                triggerMobilePermissionModalManual();
            }, 1000);
        }
    });
</script>
