<!-- ════════════════════════════════════════════════════════════ -->
<!-- DEVELOPER SHAKE & SYSTEM DIAGNOSTIC MODAL (COMPACT & MODERN)-->
<!-- ════════════════════════════════════════════════════════════ -->

<style>
    .dev-modal-backdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        background: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        z-index: 100000 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 14px !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transition: opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.28s ease !important;
        box-sizing: border-box !important;
    }
    .dev-modal-backdrop.show-dev-modal {
        opacity: 1 !important;
        visibility: visible !important;
    }
    .dev-modal-card {
        background: #ffffff !important;
        border-radius: 24px !important;
        max-width: 410px !important;
        width: 100% !important;
        box-shadow: 0 20px 40px -10px rgba(46, 16, 101, 0.45) !important;
        overflow: hidden !important;
        transform: scale(0.92) translateY(18px) !important;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
        border: 1px solid rgba(216, 180, 254, 0.6) !important;
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
        70% { box-shadow: 0 0 0 8px rgba(251, 191, 36, 0); }
        100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }
    .dev-handle-bar {
        width: 32px !important;
        height: 4px !important;
        background: rgba(255, 255, 255, 0.4) !important;
        border-radius: 99px !important;
        margin: 0 auto 8px auto !important;
        display: block !important;
    }

    /* Universal Scope Flex Styles with !important */
    .dev-flex { display: flex !important; }
    .dev-flex-col { flex-direction: column !important; }
    .dev-items-center { align-items: center !important; }
    .dev-justify-between { justify-content: space-between !important; }
    .dev-gap-1 { gap: 4px !important; }
    .dev-gap-2 { gap: 8px !important; }
    .dev-gap-2.5 { gap: 10px !important; }
    .dev-gap-3 { gap: 12px !important; }
    .dev-shrink-0 { flex-shrink: 0 !important; }
    .dev-w-full { width: 100% !important; }
    .dev-flex-1 { flex: 1 1 0% !important; min-width: 0 !important; }

    @media (max-width: 576px) {
        .dev-modal-backdrop {
            padding: 10px !important;
            align-items: flex-end !important;
        }
        .dev-modal-card {
            border-radius: 24px 24px 16px 16px !important;
            max-height: 86vh !important;
            margin-bottom: env(safe-area-inset-bottom, 6px) !important;
        }
    }
</style>

<div id="developerMenuModal" class="dev-modal-backdrop" role="dialog" aria-modal="true">
    <div class="dev-modal-card">
        <!-- Compact Header -->
        <div style="background: linear-gradient(135deg, #18092e 0%, #3b0764 50%, #581c87 100%); padding: 14px 16px; color: #ffffff; position: relative;">
            <div class="dev-handle-bar"></div>
            <div class="dev-flex dev-items-center dev-justify-between dev-w-full">
                <div class="dev-flex dev-items-center dev-gap-2.5 dev-flex-1">
                    <div class="dev-badge-pulse dev-shrink-0" style="width: 38px; height: 38px; background: #fbbf24; color: #3b0764; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800;">
                        <i class="fas fa-terminal"></i>
                    </div>
                    <div class="dev-flex-1">
                        <div class="dev-flex dev-items-center dev-gap-2">
                            <h6 style="margin: 0; font-weight: 800; color: #ffffff; font-size: 14px; line-height: 1.2;">Jasa Developer Menu</h6>
                            <span style="background: #fbbf24; color: #3b0764; font-weight: 800; padding: 2px 6px; border-radius: 6px; font-size: 9px; line-height: 1; text-transform: uppercase;">PRO</span>
                        </div>
                        <span style="font-size: 11px; color: #ddd6fe; font-weight: 500; display: block; margin-top: 2px;">System Diagnostic &amp; Tech Support</span>
                    </div>
                </div>
                <button type="button" style="width: 30px; height: 30px; border-radius: 9999px; background: rgba(255, 255, 255, 0.15); color: #ffffff; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; margin-left: 8px;" onclick="closeDeveloperModal()">
                    <i class="fas fa-times" style="font-size: 13px;"></i>
                </button>
            </div>
        </div>

        <!-- Compact Body -->
        <div style="padding: 14px 16px; background: #f8fafc; max-height: 64vh; overflow-y: auto;">

            <!-- Developer Profile Banner -->
            <div style="background: #ffffff; border: 1px solid #e9d5ff; border-radius: 16px; padding: 12px 14px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div class="dev-flex dev-items-center dev-gap-2.5">
                    <div class="dev-shrink-0" style="width: 36px; height: 36px; background: #f3e8ff; color: #7c3aed; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700;">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div class="dev-flex-1">
                        <h6 style="margin: 0; font-weight: 800; color: #1e293b; font-size: 13px; line-height: 1.2;">Paradise of Math Tech Lab</h6>
                        <p style="margin: 2px 0 0 0; font-size: 10px; color: #64748b;">Custom Web &amp; Mobile Application Development</p>
                    </div>
                </div>
                <div style="height: 1px; background: #f1f5f9; margin: 8px 0;"></div>
                <div class="dev-flex dev-items-center dev-justify-between" style="font-size: 10px; color: #64748b;">
                    <span><i class="fas fa-microchip" style="color: #8b5cf6; margin-right: 3px;"></i> Versi: <strong style="color: #1e293b;">v1.1.0 Stable</strong></span>
                    <span><i class="fas fa-bolt" style="color: #f59e0b; margin-right: 3px;"></i> Status: <strong style="color: #059669;">🟢 Online</strong></span>
                </div>
            </div>

            <!-- Action Buttons Grid -->
            <div style="display: flex; flex-direction: column; gap: 8px;">

                <!-- Action 1: WhatsApp Developer (Vibrant Emerald Banner) -->
                <a href="https://wa.me/6289508839313?text=Halo%20Bang%20%2F%20tim%20Paradise%20of%20Math.%20Saya%20sedang%20butuh%20bantuan%20untuk%20pembuatan%20website%20dan%20ingin%20berkonsultasi%20lebih%20lanjut%20mengenai%20detailnya.%20Apakah%20kita%20bisa%20berdiskusi%20terkait%20hal%20ini%3F" target="_blank" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 16px; padding: 11px 14px; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 10px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25); transition: transform 0.2s ease;">
                    <div class="dev-flex dev-items-center dev-gap-2.5 dev-flex-1">
                        <div class="dev-shrink-0" style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); color: #ffffff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="dev-flex-1">
                            <h6 style="margin: 0; font-weight: 800; color: #ffffff; font-size: 12px; line-height: 1.2;">Hubungi Jasa Developer</h6>
                            <span style="font-size: 10px; color: #a7f3d0; display: block; margin-top: 1px;">Konsultasi &amp; Pembuatan Website Baru</span>
                        </div>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); width: 26px; height: 26px; border-radius: 99px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-arrow-right" style="color: #ffffff; font-size: 10px;"></i>
                    </div>
                </a>

                <!-- Action 2: Clear Cache -->
                <button type="button" onclick="handleClearDevCache()" style="background: #ffffff; border: 1px solid #e9d5ff; border-radius: 16px; padding: 10px 14px; width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 10px; text-align: left; cursor: pointer; transition: all 0.2s ease;">
                    <div class="dev-flex dev-items-center dev-gap-2.5 dev-flex-1">
                        <div class="dev-shrink-0" style="width: 34px; height: 34px; background: #f3e8ff; color: #7c3aed; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            <i class="fas fa-broom"></i>
                        </div>
                        <div class="dev-flex-1">
                            <h6 style="margin: 0; font-weight: 800; color: #1e293b; font-size: 12px; line-height: 1.2;">Bersihkan Cache HP</h6>
                            <span style="font-size: 10px; color: #64748b; display: block; margin-top: 1px;">Refresh tampilan &amp; bersihkan session</span>
                        </div>
                    </div>
                    <i class="fas fa-sync-alt" style="color: #a855f7; font-size: 11px; flex-shrink: 0;"></i>
                </button>

                <!-- Action 3: Copy Diagnostics -->
                <button type="button" onclick="handleCopyDevDiagnostics()" style="background: #ffffff; border: 1px solid #fed7aa; border-radius: 16px; padding: 10px 14px; width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 10px; text-align: left; cursor: pointer; transition: all 0.2s ease;">
                    <div class="dev-flex dev-items-center dev-gap-2.5 dev-flex-1">
                        <div class="dev-shrink-0" style="width: 34px; height: 34px; background: #fff7ed; color: #ea580c; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="dev-flex-1">
                            <h6 style="margin: 0; font-weight: 800; color: #1e293b; font-size: 12px; line-height: 1.2;">Salin Diagnostik System</h6>
                            <span style="font-size: 10px; color: #64748b; display: block; margin-top: 1px;">Salin info resolusi HP &amp; browser</span>
                        </div>
                    </div>
                    <i class="far fa-copy" style="color: #f97316; font-size: 11px; flex-shrink: 0;"></i>
                </button>

            </div>

            <!-- Shake Notification Pill -->
            <div style="background: #f3e8ff; border: 1px solid #e9d5ff; border-radius: 12px; padding: 8px 10px; text-align: center; margin-top: 10px;">
                <span style="font-size: 10px; font-weight: 700; color: #6d28d9; display: block;">
                    <i class="fas fa-mobile-alt" style="color: #7c3aed; margin-right: 4px;"></i> Goyangkan HP Anda kapan saja untuk membuka menu ini.
                </span>
            </div>

        </div>

        <!-- Footer -->
        <div class="dev-flex dev-items-center dev-justify-between" style="padding: 10px 16px; background: #ffffff; border-top: 1px solid #f1f5f9;">
            <span style="font-size: 10px; color: #94a3b8; font-weight: 600;"><i class="fas fa-vial" style="margin-right: 3px;"></i> Developer Mode</span>
            <button type="button" style="background: #6d28d9; color: #ffffff; font-weight: 700; border: none; border-radius: 10px; padding: 6px 14px; font-size: 11px; cursor: pointer;" onclick="closeDeveloperModal()">
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

        // 1. Ultra-Low Sensitivity Shake Listener (Membutuhkan HP Digoyang Sangat Kencang & 3x Beruntun)
        var shakeCount = 0;
        var lastShakeResetTime = 0;

        function initShakeDetection() {
            if (window.DeviceMotionEvent) {
                window.addEventListener('devicemotion', function(e) {
                    var currentTime = new Date().getTime();
                    
                    // Reset hitungan jika tidak ada guncangan beruntun dalam 600ms
                    if (currentTime - lastShakeResetTime > 1000) {
                        shakeCount = 0;
                    }

                    if ((currentTime - lastTime) > 80) {
                        var diffTime = currentTime - lastTime;
                        lastTime = currentTime;

                        // Mengutamakan e.acceleration (akselerasi murni tanpa efek gravitasi/kemiringan)
                        var acc = (e.acceleration && e.acceleration.x !== null) ? e.acceleration : e.accelerationIncludingGravity;
                        if (acc) {
                            var x = acc.x || 0;
                            var y = acc.y || 0;
                            var z = acc.z || 0;

                            if (lastX !== null && lastY !== null && lastZ !== null) {
                                var deltaX = Math.abs(x - lastX);
                                var deltaY = Math.abs(y - lastY);
                                var deltaZ = Math.abs(z - lastZ);

                                var speed = (deltaX + deltaY + deltaZ) / diffTime * 10000;

                                // Ambang batas sangat tinggi (3500) agar gerakan biasa/kemiringan HP tidak memicu modal
                                if (speed > 3500) {
                                    shakeCount++;
                                    lastShakeResetTime = currentTime;

                                    // Membutuhkan 3 kali guncangan sangat kuat berturut-turut
                                    if (shakeCount >= 3 && !isModalOpen) {
                                        shakeCount = 0;
                                        openDeveloperModal();
                                    }
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
