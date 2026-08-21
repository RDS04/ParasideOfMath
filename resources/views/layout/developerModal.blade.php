<!-- ════════════════════════════════════════════════════════════ -->
<!-- DEVELOPER SHAKE & SYSTEM DIAGNOSTIC MODAL (FRAMEWORK AGNOSTIC) -->
<!-- ════════════════════════════════════════════════════════════ -->

<style>
    .dev-modal-backdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        background: rgba(15, 23, 42, 0.78) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        z-index: 100000 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 16px !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s ease !important;
        box-sizing: border-box !important;
    }
    .dev-modal-backdrop.show-dev-modal {
        opacity: 1 !important;
        visibility: visible !important;
    }
    .dev-modal-card {
        background: #ffffff !important;
        border-radius: 24px !important;
        max-width: 440px !important;
        width: 100% !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.45) !important;
        overflow: hidden !important;
        transform: scale(0.92) translateY(20px) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        border: 1px solid rgba(216, 180, 254, 0.5) !important;
        box-sizing: border-box !important;
        margin: 0 auto !important;
    }
    .dev-modal-backdrop.show-dev-modal .dev-modal-card {
        transform: scale(1) translateY(0) !important;
    }
    .dev-badge-pulse {
        animation: pulseDevGlow 2s infinite !important;
    }
    @keyframes pulseDevGlow {
        0% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.6); }
        70% { box-shadow: 0 0 0 10px rgba(251, 191, 36, 0); }
        100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }
    .dev-handle-bar {
        width: 36px !important;
        height: 4px !important;
        background: rgba(255, 255, 255, 0.35) !important;
        border-radius: 99px !important;
        margin: 0 auto 10px auto !important;
        display: block !important;
    }

    /* Standard Flex Utilities with !important for full framework compatibility */
    .dev-flex { display: flex !important; }
    .dev-flex-col { flex-direction: column !important; }
    .dev-items-center { align-items: center !important; }
    .dev-justify-between { justify-content: space-between !important; }
    .dev-gap-1 { gap: 4px !important; }
    .dev-gap-2 { gap: 8px !important; }
    .dev-gap-3 { gap: 12px !important; }
    .dev-shrink-0 { flex-shrink: 0 !important; }
    .dev-w-full { width: 100% !important; }
    .dev-flex-1 { flex: 1 1 0% !important; min-width: 0 !important; }

    @media (max-width: 576px) {
        .dev-modal-backdrop {
            padding: 12px !important;
            align-items: flex-end !important;
        }
        .dev-modal-card {
            border-radius: 24px 24px 18px 18px !important;
            max-height: 88vh !important;
            margin-bottom: env(safe-area-inset-bottom, 8px) !important;
        }
        .dev-handle-bar {
            display: block !important;
        }
    }
</style>

<div id="developerMenuModal" class="dev-modal-backdrop" role="dialog" aria-modal="true">
    <div class="dev-modal-card">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 60%, #6d28d9 100%); padding: 16px; color: #ffffff; position: relative;">
            <div class="dev-handle-bar"></div>
            <div class="dev-flex dev-items-center dev-justify-between dev-w-full">
                <div class="dev-flex dev-items-center dev-gap-3 dev-flex-1">
                    <div class="dev-badge-pulse dev-shrink-0" style="width: 44px; height: 44px; background: #fbbf24; color: #3b0764; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800;">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div class="dev-flex-1">
                        <div class="dev-flex dev-items-center dev-gap-2">
                            <h6 style="margin: 0; font-weight: 800; color: #ffffff; font-size: 14px; line-height: 1.2;">Developer &amp; System Info</h6>
                            <span style="background: #fbbf24; color: #3b0764; font-weight: 800; padding: 2px 7px; border-radius: 6px; font-size: 10px; line-height: 1;">PRO</span>
                        </div>
                        <span style="font-size: 11px; color: #ddd6fe; font-weight: 500; display: block; margin-top: 3px;">Bantuan Teknis &amp; Pengaturan App</span>
                    </div>
                </div>
                <button type="button" style="width: 32px; height: 32px; border-radius: 9999px; background: rgba(255, 255, 255, 0.15); color: #ffffff; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; margin-left: 8px;" onclick="closeDeveloperModal()">
                    <i class="fas fa-times" style="font-size: 14px;"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div style="padding: 16px; background: #f8fafc; max-height: 62vh; overflow-y: auto;">

            <!-- Developer Profile Box -->
            <div style="background: #ffffff; border: 1px solid #e9d5ff; border-radius: 16px; padding: 14px; margin-bottom: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                <div class="dev-flex dev-items-center dev-gap-3">
                    <div class="dev-shrink-0" style="width: 40px; height: 40px; background: #f3e8ff; color: #7c3aed; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700;">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div class="dev-flex-1">
                        <h6 style="margin: 0; font-weight: 800; color: #1e293b; font-size: 13px; line-height: 1.2;">Paradise of Math Tech Lab</h6>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b;">Custom Web Application &amp; Mobile System</p>
                    </div>
                </div>
                <div style="height: 1px; background: #f1f5f9; margin: 10px 0;"></div>
                <div class="dev-flex dev-items-center dev-justify-between" style="font-size: 11px; color: #64748b;">
                    <span><i class="fas fa-microchip" style="color: #8b5cf6; margin-right: 4px;"></i> Versi: <strong style="color: #1e293b;">v1.1.0 Stable</strong></span>
                    <span><i class="fas fa-bolt" style="color: #f59e0b; margin-right: 4px;"></i> Status: <strong style="color: #059669;">Online</strong></span>
                </div>
            </div>

            <!-- Action Buttons Stack -->
            <div style="display: flex; flex-direction: column; gap: 10px;">

                <!-- Action 1: WhatsApp Developer -->
                <a href="https://wa.me/6289508839313?text=Halo%20Bang%20%2F%20tim%20Paradise%20of%20Math.%20Saya%20sedang%20butuh%20bantuan%20untuk%20pembuatan%20website%20dan%20ingin%20berkonsultasi%20lebih%20lanjut%20mengenai%20detailnya.%20Apakah%20kita%20bisa%20berdiskusi%20terkait%20hal%20ini%3F" target="_blank" style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 16px; padding: 12px; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 10px; transition: all 0.2s ease;">
                    <div class="dev-flex dev-items-center dev-gap-3 dev-flex-1">
                        <div class="dev-shrink-0" style="width: 38px; height: 38px; background: #10b981; color: #ffffff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="dev-flex-1">
                            <h6 style="margin: 0; font-weight: 800; color: #064e3b; font-size: 12px; line-height: 1.2;">Hubungi Jasa Developer</h6>
                            <span style="font-size: 10px; color: #047857; display: block; margin-top: 2px;">Tanya fitur baru, Developer Aplikasi, atau konsultasi</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right" style="color: #059669; font-size: 12px; flex-shrink: 0;"></i>
                </a>

                <!-- Action 2: Clear Cache -->
                <button type="button" onclick="handleClearDevCache()" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 12px; width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 10px; text-align: left; cursor: pointer; transition: all 0.2s ease;">
                    <div class="dev-flex dev-items-center dev-gap-3 dev-flex-1">
                        <div class="dev-shrink-0" style="width: 38px; height: 38px; background: #f3e8ff; color: #7c3aed; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                            <i class="fas fa-broom"></i>
                        </div>
                        <div class="dev-flex-1">
                            <h6 style="margin: 0; font-weight: 800; color: #1e293b; font-size: 12px; line-height: 1.2;">Bersihkan Cache &amp; Refresh</h6>
                            <span style="font-size: 10px; color: #64748b; display: block; margin-top: 2px;">Refresh tampilan &amp; bersihkan cache HP</span>
                        </div>
                    </div>
                    <i class="fas fa-sync-alt" style="color: #94a3b8; font-size: 12px; flex-shrink: 0;"></i>
                </button>

                <!-- Action 3: Copy Diagnostics -->
                <button type="button" onclick="handleCopyDevDiagnostics()" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 12px; width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 10px; text-align: left; cursor: pointer; transition: all 0.2s ease;">
                    <div class="dev-flex dev-items-center dev-gap-3 dev-flex-1">
                        <div class="dev-shrink-0" style="width: 38px; height: 38px; background: #fef3c7; color: #d97706; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="dev-flex-1">
                            <h6 style="margin: 0; font-weight: 800; color: #1e293b; font-size: 12px; line-height: 1.2;">Salin Diagnostik Perangkat</h6>
                            <span style="font-size: 10px; color: #64748b; display: block; margin-top: 2px;">Salin resolusi HP, User-Agent &amp; info browser</span>
                        </div>
                    </div>
                    <i class="far fa-copy" style="color: #94a3b8; font-size: 12px; flex-shrink: 0;"></i>
                </button>

            </div>

            <!-- Notification Banner -->
            <div style="background: #f3e8ff; border: 1px solid #e9d5ff; border-radius: 14px; padding: 10px 12px; text-align: center; margin-top: 12px;">
                <p style="margin: 0; font-size: 11px; font-weight: 700; color: #581c87;">
                    <i class="fas fa-mobile-alt" style="color: #7c3aed; margin-right: 4px;"></i> Deteksi Goyang HP Aktif!
                </p>
                <span style="font-size: 10px; color: #6d28d9; display: block; margin-top: 2px;">Goyangkan HP Anda kapan saja untuk membuka menu ini.</span>
            </div>

        </div>

        <!-- Footer -->
        <div class="dev-flex dev-items-center dev-justify-between" style="padding: 12px 16px; background: #ffffff; border-top: 1px solid #f1f5f9;">
            <span style="font-size: 11px; color: #94a3b8; font-weight: 600;"><i class="fas fa-vial" style="margin-right: 4px;"></i> Developer Mode</span>
            <button type="button" style="background: #6d28d9; color: #ffffff; font-weight: 700; border: none; border-radius: 12px; padding: 7px 16px; font-size: 12px; cursor: pointer;" onclick="closeDeveloperModal()">
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
