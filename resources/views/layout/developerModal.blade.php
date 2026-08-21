<!-- ════════════════════════════════════════════════════════════ -->
<!-- DEVELOPER SHAKE & SYSTEM DIAGNOSTIC MODAL (MOBILE OPTIMIZED)-->
<!-- ════════════════════════════════════════════════════════════ -->

<style>
    .dev-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 100000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.32s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.32s ease;
    }
    .dev-modal-backdrop.show-dev-modal {
        opacity: 1;
        visibility: visible;
    }
    .dev-modal-card {
        background: #ffffff;
        border-radius: 28px;
        max-width: 440px;
        width: 100%;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.45);
        overflow: hidden;
        transform: scale(0.92) translateY(24px);
        transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(216, 180, 254, 0.4);
    }
    .dev-modal-backdrop.show-dev-modal .dev-modal-card {
        transform: scale(1) translateY(0);
    }
    .dev-badge-pulse {
        animation: pulseDevGlow 2s infinite;
    }
    @keyframes pulseDevGlow {
        0% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.6); }
        70% { box-shadow: 0 0 0 10px rgba(251, 191, 36, 0); }
        100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }
    .dev-handle-bar {
        width: 36px;
        height: 4px;
        background: rgba(255, 255, 255, 0.35);
        border-radius: 99px;
        margin: 0 auto 10px auto;
    }

    @media (max-width: 576px) {
        .dev-modal-backdrop {
            padding: 12px;
            align-items: flex-end;
        }
        .dev-modal-card {
            border-radius: 28px 28px 20px 20px;
            max-height: 88vh;
            margin-bottom: env(safe-area-inset-bottom, 8px);
        }
    }
</style>

<div id="developerMenuModal" class="dev-modal-backdrop" role="dialog" aria-modal="true">
    <div class="dev-modal-card">
        <!-- Header -->
        <div class="p-3.5 p-sm-4 text-white position-relative" style="background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 60%, #6d28d9 100%);">
            <div class="dev-handle-bar d-sm-none"></div>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-2xl bg-amber-400 text-purple-950 p-2.5 font-extrabold d-flex align-items-center justify-content-center shrink-0 dev-badge-pulse" style="width: 42px; height: 42px; font-size: 18px;">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-1.5">
                            <h6 class="font-extrabold text-white mb-0 text-xs sm:text-base">Developer &amp; System Info</h6>
                            <span class="badge bg-amber-400 text-purple-950 font-bold px-2 py-0.5 rounded-md text-[9px] sm:text-[10px]">PRO</span>
                        </div>
                        <span class="text-[11px] sm:text-xs text-purple-200 font-medium">Bantuan Teknis &amp; Pengaturan App</span>
                    </div>
                </div>
                <button type="button" class="btn btn-sm text-white bg-white/10 hover:bg-white/20 rounded-circle p-0" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" onclick="closeDeveloperModal()">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-3.5 p-sm-4 bg-slate-50/70" style="max-height: 65vh; overflow-y: auto;">

            <!-- Developer Profile Box -->
            <div class="p-3 rounded-2xl bg-white border border-purple-100 shadow-xs mb-3">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-xl p-2 bg-purple-100 text-purple-700 font-bold shrink-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px;">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h6 class="font-bold text-slate-800 mb-0 text-xs sm:text-sm text-truncate">Paradise of Math Tech Lab</h6>
                        <p class="text-[11px] text-slate-500 mb-0 text-truncate">Custom Web Application &amp; Mobile System</p>
                    </div>
                </div>
                <hr class="my-2 border-slate-100">
                <div class="d-flex align-items-center justify-content-between text-[11px] sm:text-xs text-slate-500">
                    <span><i class="fas fa-microchip text-purple-500 mr-1"></i> Versi: <strong>v1.1.0 Stable</strong></span>
                    <span><i class="fas fa-bolt text-amber-500 mr-1"></i> Status: <strong class="text-emerald-600">Online</strong></span>
                </div>
            </div>

            <!-- Features / Actions Grid -->
            <div class="space-y-2">
                <!-- Action 1: WhatsApp Developer -->
                <a href="https://wa.me/628136379216?text=Halo%20Bang%20%2F%20tim%20Paradise%20of%20Math.%20Saya%20sedang%20butuh%20bantuan%20untuk%20pembuatan%20website%20dan%20ingin%20berkonsultasi%20lebih%20lanjut%20mengenai%20detailnya.%20Apakah%20kita%20bisa%20berdiskusi%20terkait%20hal%20ini%3F" target="_blank" class="p-2.5 sm:p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950 d-flex align-items-center justify-content-between text-decoration-none hover:bg-emerald-100 transition-all">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                        <div class="rounded-xl p-2 bg-emerald-500 text-white font-bold d-flex align-items-center justify-content-center shrink-0" style="width: 36px; height: 36px;">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h6 class="font-bold mb-0 text-xs text-emerald-950 text-truncate">Hubungi Jasa Developer</h6>
                            <span class="text-[10px] sm:text-[11px] text-emerald-700 text-truncate d-block">Tanya fitur baru, Developer Aplikasi, atau konsultasi</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-600 text-xs ml-1 shrink-0"></i>
                </a>

                <!-- Action 2: Clear Application Cache -->
                <button type="button" onclick="handleClearDevCache()" class="w-100 p-2.5 sm:p-3 rounded-2xl bg-white border border-slate-200 text-slate-800 d-flex align-items-center justify-content-between hover:border-purple-300 hover:bg-purple-50/50 transition-all text-left">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                        <div class="rounded-xl p-2 bg-purple-100 text-purple-700 font-bold d-flex align-items-center justify-content-center shrink-0" style="width: 36px; height: 36px;">
                            <i class="fas fa-broom text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h6 class="font-bold mb-0 text-xs text-slate-800 text-truncate">Bersihkan Cache &amp; Refresh</h6>
                            <span class="text-[10px] sm:text-[11px] text-slate-500 text-truncate d-block">Refresh tampilan &amp; bersihkan cache HP</span>
                        </div>
                    </div>
                    <i class="fas fa-sync-alt text-slate-400 text-xs ml-1 shrink-0"></i>
                </button>

                <!-- Action 3: Copy Diagnostic Info -->
                <button type="button" onclick="handleCopyDevDiagnostics()" class="w-100 p-2.5 sm:p-3 rounded-2xl bg-white border border-slate-200 text-slate-800 d-flex align-items-center justify-content-between hover:border-purple-300 hover:bg-purple-50/50 transition-all text-left">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                        <div class="rounded-xl p-2 bg-amber-100 text-amber-700 font-bold d-flex align-items-center justify-content-center shrink-0" style="width: 36px; height: 36px;">
                            <i class="fas fa-info-circle text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h6 class="font-bold mb-0 text-xs text-slate-800 text-truncate">Salin Diagnostik Perangkat</h6>
                            <span class="text-[10px] sm:text-[11px] text-slate-500 text-truncate d-block">Salin resolusi HP, User-Agent &amp; info browser</span>
                        </div>
                    </div>
                    <i class="far fa-copy text-slate-400 text-xs ml-1 shrink-0"></i>
                </button>
            </div>

            <!-- Shake Notification Banner -->
            <div class="mt-3 p-2.5 rounded-xl bg-purple-100/80 border border-purple-200 text-purple-950 text-center">
                <p class="text-[11px] sm:text-xs font-bold mb-0 text-purple-900">
                    <i class="fas fa-mobile-alt text-purple-600 mr-1"></i> Deteksi Goyang HP Aktif!
                </p>
                <span class="text-[10px] sm:text-[11px] text-purple-700 d-block mt-0.5">Goyangkan HP Anda kapan saja untuk membuka menu ini.</span>
            </div>

        </div>

        <!-- Footer -->
        <div class="p-3 bg-white border-top d-flex align-items-center justify-content-between">
            <span class="text-[10px] sm:text-[11px] text-slate-400 font-semibold"><i class="fas fa-vial mr-1"></i> Developer Mode</span>
            <button type="button" class="btn btn-purple btn-sm font-bold rounded-xl px-4 py-1.5 text-xs shadow-xs" onclick="closeDeveloperModal()">
                Tutup Menu
            </button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════ -->
<!-- SHAKE DETECTION & VIBRATION SCRIPT (HIGH SENSITIVITY)       -->
<!-- ════════════════════════════════════════════════════════════ -->
<script>
    (function() {
        var lastX = null, lastY = null, lastZ = null;
        var lastTime = 0;
        var isModalOpen = false;

        window.openDeveloperModal = function() {
            var modal = document.getElementById('developerMenuModal');
            if (modal) {
                modal.classList.add('show-dev-modal');
                isModalOpen = true;

                // Trigger Haptic Feedback / Vibration on phone
                if (navigator.vibrate) {
                    try {
                        navigator.vibrate([140, 60, 140]);
                    } catch(e) {}
                }
            }
        };

        window.closeDeveloperModal = function() {
            var modal = document.getElementById('developerMenuModal');
            if (modal) {
                modal.classList.remove('show-dev-modal');
                isModalOpen = false;
            }
        };

        window.handleClearDevCache = function() {
            if (navigator.vibrate) navigator.vibrate(80);
            try {
                localStorage.clear();
                sessionStorage.clear();
            } catch(e) {}
            alert("Cache lokal & session browser berhasil dibersihkan! Halaman akan dimuat ulang.");
            window.location.reload();
        };

        window.handleCopyDevDiagnostics = function() {
            if (navigator.vibrate) navigator.vibrate(80);
            var info = "=== SYSTEM DIAGNOSTIC INFO ===\n" +
                       "App: Paradise of Math v1.1.0\n" +
                       "User-Agent: " + navigator.userAgent + "\n" +
                       "Screen: " + window.innerWidth + "x" + window.innerHeight + "\n" +
                       "Device Pixel Ratio: " + (window.devicePixelRatio || 1) + "\n" +
                       "Time: " + new Date().toISOString();
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(info).then(function() {
                    alert("Info Diagnostik Perangkat berhasil disalin ke clipboard!");
                });
            } else {
                alert(info);
            }
        };

        // 1. High Sensitivity Device Motion / Shake Listener for Mobile Phones
        function initShakeDetection() {
            if (window.DeviceMotionEvent) {
                window.addEventListener('devicemotion', function(e) {
                    var currentTime = new Date().getTime();
                    if ((currentTime - lastTime) > 80) {
                        var diffTime = currentTime - lastTime;
                        lastTime = currentTime;

                        var acc = e.accelerationIncludingGravity || e.acceleration;
                        if (acc) {
                            var x = acc.x || 0;
                            var y = acc.y || 0;
                            var z = acc.z || 0;

                            if (lastX !== null && lastY !== null && lastZ !== null) {
                                var deltaX = Math.abs(x - lastX);
                                var deltaY = Math.abs(y - lastY);
                                var deltaZ = Math.abs(z - lastZ);

                                var speed = (deltaX + deltaY + deltaZ) / diffTime * 10000;

                                // Sensitivity threshold lowered to 450 for easy shaking on smartphones
                                if (speed > 450 && !isModalOpen) { 
                                    openDeveloperModal();
                                }
                            }

                            lastX = x;
                            lastY = y;
                            lastZ = z;
                        }
                    }
                }, false);
            }
        }

        // 2. Desktop Hotkey Shortcut (Ctrl + Shift + D) & Modal Backdrop Click
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && (e.key === 'D' || e.key === 'd')) {
                e.preventDefault();
                openDeveloperModal();
            }
            if (e.key === 'Escape' && isModalOpen) {
                closeDeveloperModal();
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'developerMenuModal') {
                closeDeveloperModal();
            }
        });

        // Request Device Motion permission for iOS 13+ if required
        if (typeof DeviceMotionEvent !== 'undefined' && typeof DeviceMotionEvent.requestPermission === 'function') {
            document.addEventListener('click', function requestMotionPermissionOnce() {
                DeviceMotionEvent.requestPermission().then(function(response) {
                    if (response === 'granted') {
                        initShakeDetection();
                    }
                }).catch(function() {});
                document.removeEventListener('click', requestMotionPermissionOnce);
            });
        } else {
            initShakeDetection();
        }
    })();
</script>
