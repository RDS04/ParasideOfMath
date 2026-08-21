<!-- ════════════════════════════════════════════════════════════ -->
<!-- DEVELOPER SHAKE & SYSTEM DIAGNOSTIC MODAL                  -->
<!-- ════════════════════════════════════════════════════════════ -->

<style>
    .dev-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 100000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s ease;
    }
    .dev-modal-backdrop.show-dev-modal {
        opacity: 1;
        visibility: visible;
    }
    .dev-modal-card {
        background: #ffffff;
        border-radius: 24px;
        max-width: 480px;
        width: 100%;
        box-shadow: 0 25px 50px -12px rgba(76, 29, 149, 0.35);
        overflow: hidden;
        transform: scale(0.9) translateY(20px);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(216, 180, 254, 0.4);
    }
    .dev-modal-backdrop.show-dev-modal .dev-modal-card {
        transform: scale(1) translateY(0);
    }
    .dev-badge-pulse {
        animation: pulseDevGlow 2s infinite;
    }
    @keyframes pulseDevGlow {
        0% { box-shadow: 0 0 0 0 rgba(168, 85, 247, 0.5); }
        70% { box-shadow: 0 0 0 10px rgba(168, 85, 247, 0); }
        100% { box-shadow: 0 0 0 0 rgba(168, 85, 247, 0); }
    }
</style>

<div id="developerMenuModal" class="dev-modal-backdrop" role="dialog" aria-modal="true">
    <div class="dev-modal-card">
        <!-- Header -->
        <div class="p-4 text-white position-relative" style="background: linear-gradient(135deg, #2e1065 0%, #581c87 50%, #7c3aed 100%);">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-2xl bg-amber-400 text-purple-950 p-3 font-extrabold d-flex align-items-center justify-content-center shrink-0 dev-badge-pulse" style="width: 46px; height: 46px; font-size: 20px;">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="font-extrabold text-white mb-0 text-base">Jasa Developer &amp; System</h6>
                            <span class="badge bg-amber-400 text-purple-950 font-bold px-2 py-0.5 rounded-md text-[10px]">PRO</span>
                        </div>
                        <span class="text-xs text-purple-200 font-medium">Pengaturan Pengembang &amp; Bantuan Teknis</span>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-circle text-white bg-white/10 hover:bg-white/20 rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" onclick="closeDeveloperModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-4 bg-slate-50/60" style="max-height: 68vh; overflow-y: auto;">

            <!-- Developer Profile Box -->
            <div class="p-3.5 rounded-2xl bg-white border border-purple-100 shadow-xs mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-2 bg-purple-100 text-purple-700 font-bold shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 18px;">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div>
                        <h6 class="font-bold text-slate-800 mb-0 text-sm">Paradise of Math Tech Lab</h6>
                        <p class="text-xs text-slate-500 mb-0">Custom Web Application &amp; Mobile Responsive System</p>
                    </div>
                </div>
                <hr class="my-2.5 border-slate-100">
                <div class="d-flex align-items-center justify-content-between text-xs text-slate-500">
                    <span><i class="fas fa-microchip text-purple-500 mr-1"></i> Versi Aplikasi: <strong>v1.1.0 Stable</strong></span>
                    <span><i class="fas fa-bolt text-amber-500 mr-1"></i> Status: <strong class="text-emerald-600">Online</strong></span>
                </div>
            </div>

            <!-- Features / Actions Grid -->
            <div class="space-y-2.5">
                <!-- Action 1: WhatsApp Developer -->
                <a href="https://wa.me/628136379216?text=Halo%20Bang%20%2F%20tim%20Paradise%20of%20Math.%20Saya%20sedang%20butuh%20bantuan%20untuk%20pembuatan%20website%20dan%20ingin%20berkonsultasi%20lebih%20lanjut%20mengenai%20detailnya.%20Apakah%20kita%20bisa%20berdiskusi%20terkait%20hal%20ini%3F" target="_blank" class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950 d-flex align-items-center justify-content-between text-decoration-none hover:bg-emerald-100 transition-all">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-xl p-2 bg-emerald-500 text-white font-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </div>
                        <div>
                            <h6 class="font-bold mb-0 text-xs text-emerald-950">Hubungi Jasa Developer</h6>
                            <span class="text-[11px] text-emerald-700">Tanya fitur baru,Developer Aplikasu,atau konsultasi</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-600 text-xs"></i>
                </a>

                <!-- Action 2: Clear Application Cache -->
                <button type="button" onclick="handleClearDevCache()" class="w-100 p-3 rounded-2xl bg-white border border-slate-200 text-slate-800 d-flex align-items-center justify-content-between hover:border-purple-300 hover:bg-purple-50/50 transition-all text-left">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-xl p-2 bg-purple-100 text-purple-700 font-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-broom text-sm"></i>
                        </div>
                        <div>
                            <h6 class="font-bold mb-0 text-xs text-slate-800">Bersihkan Cache &amp; Refresh Session</h6>
                            <span class="text-[11px] text-slate-500">Refresh tampilan &amp; bersihkan cache lokal HP</span>
                        </div>
                    </div>
                    <i class="fas fa-sync-alt text-slate-400 text-xs"></i>
                </button>

                <!-- Action 3: Copy Diagnostic Info -->
                <button type="button" onclick="handleCopyDevDiagnostics()" class="w-100 p-3 rounded-2xl bg-white border border-slate-200 text-slate-800 d-flex align-items-center justify-content-between hover:border-purple-300 hover:bg-purple-50/50 transition-all text-left">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-xl p-2 bg-amber-100 text-amber-700 font-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-info-circle text-sm"></i>
                        </div>
                        <div>
                            <h6 class="font-bold mb-0 text-xs text-slate-800">Salin Diagnostik Perangkat</h6>
                            <span class="text-[11px] text-slate-500">Salin resolusi HP, User-Agent &amp; info browser</span>
                        </div>
                    </div>
                    <i class="far fa-copy text-slate-400 text-xs"></i>
                </button>
            </div>

            <!-- Shake Notification Banner -->
            <div class="mt-3.5 p-3 rounded-xl bg-purple-100/70 border border-purple-200 text-purple-950 text-center">
                <p class="text-xs font-semibold mb-0">
                    <i class="fas fa-mobile-alt text-purple-600 mr-1"></i> Fitur Deteksi Goyang HP Aktif!
                </p>
                <span class="text-[11px] text-purple-700 d-block mt-0.5">Goyangkan HP Anda kapan saja untuk membuka menu developer ini secara instan.</span>
            </div>

        </div>

        <!-- Footer -->
        <div class="p-3 bg-white border-top d-flex align-items-center justify-content-between">
            <span class="text-[11px] text-slate-400 font-medium"><i class="fas fa-vial mr-1"></i> Developer Mode</span>
            <button type="button" class="btn btn-purple btn-sm font-bold rounded-xl px-4 py-1.5 text-xs shadow-xs" onclick="closeDeveloperModal()">
                Tutup Menu
            </button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════ -->
<!-- SHAKE DETECTION & VIBRATION SCRIPT                           -->
<!-- ════════════════════════════════════════════════════════════ -->
<script>
    (function() {
        var lastX = null, lastY = null, lastZ = null;
        var lastTime = 0;
        var shakeThreshold = 18; // Sensitivity threshold for physical phone shake
        var isModalOpen = false;

        window.openDeveloperModal = function() {
            var modal = document.getElementById('developerMenuModal');
            if (modal) {
                modal.classList.add('show-dev-modal');
                isModalOpen = true;

                // Trigger Haptic Feedback / Vibration on phone
                if (navigator.vibrate) {
                    try {
                        navigator.vibrate([120, 60, 120]);
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
                       "App: Paradise of Math v2.5.0\n" +
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

        // 1. Device Motion / Shake Event Listener (Mobile Phone Accelerometer)
        function initShakeDetection() {
            if (window.DeviceMotionEvent) {
                window.addEventListener('devicemotion', function(e) {
                    var currentTime = new Date().getTime();
                    if ((currentTime - lastTime) > 100) {
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

                                if (speed > 800 && !isModalOpen) { // Phone Shake Detected!
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
