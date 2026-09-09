@php
    $currentUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
    $isSiswa = auth()->guard('siswa')->check();
    $isGuru = $currentUser && method_exists($currentUser, 'isGuru') && $currentUser->isGuru();
    $hasSeenOnboarding = $currentUser ? (bool)$currentUser->has_seen_onboarding : true;
    $userRole = $isSiswa ? 'siswa' : ($isGuru ? 'guru' : 'other');
@endphp

@if($currentUser && ($isSiswa || $isGuru))
    <!-- Driver.js CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css">
    <style>
        .driver-popover {
            background-color: #2e1065 !important;
            color: #ffffff !important;
            border: 2px solid #fbbf24 !important;
            border-radius: 14px !important;
            box-shadow: 0 12px 30px -5px rgba(0, 0, 0, 0.45) !important;
            padding: 18px !important;
            max-width: 360px !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            box-sizing: border-box !important;
        }
        .driver-popover-title {
            color: #fbbf24 !important;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            margin-bottom: 8px !important;
            line-height: 1.4 !important;
        }
        .driver-popover-description {
            color: #f3f4f6 !important;
            font-size: 0.9rem !important;
            line-height: 1.5 !important;
        }
        .driver-popover-footer {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 12px !important;
            margin-top: 14px !important;
            padding-top: 10px !important;
            border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .driver-popover-progress-text {
            color: #fbbf24 !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            white-space: nowrap !important;
            margin: 0 !important;
            padding-right: 8px !important;
            flex-shrink: 0 !important;
        }
        .driver-popover-navigation-btns {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            flex-shrink: 0 !important;
        }
        .driver-popover-footer button {
            background-color: #4c1d95 !important;
            color: #ffffff !important;
            border: 1px solid #7c3aed !important;
            text-shadow: none !important;
            border-radius: 6px !important;
            padding: 6px 12px !important;
            font-weight: 600 !important;
            font-size: 0.82rem !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            line-height: 1.2 !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .driver-popover-footer button:hover {
            background-color: #fbbf24 !important;
            color: #2e1065 !important;
            border-color: #f59e0b !important;
        }
        .driver-popover-footer .driver-popover-btn-disabled {
            opacity: 0.35 !important;
            cursor: not-allowed !important;
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #9ca3af !important;
        }
        .driver-popover-close-btn {
            color: #9ca3af !important;
            font-size: 1.2rem !important;
            top: 10px !important;
            right: 12px !important;
            transition: color 0.2s ease !important;
        }
        .driver-popover-close-btn:hover {
            color: #fbbf24 !important;
        }
        .driver-popover-arrow-side-left.driver-popover-arrow { border-left-color: #2e1065 !important; }
        .driver-popover-arrow-side-right.driver-popover-arrow { border-right-color: #2e1065 !important; }
        .driver-popover-arrow-side-top.driver-popover-arrow { border-top-color: #2e1065 !important; }
        .driver-popover-arrow-side-bottom.driver-popover-arrow { border-bottom-color: #2e1065 !important; }

        /* Responsive Mobile Styles */
        @media (max-width: 767px) {
            .driver-popover {
                max-width: calc(100vw - 28px) !important;
                padding: 14px 16px !important;
            }
            .driver-popover-title {
                font-size: 0.98rem !important;
            }
            .driver-popover-description {
                font-size: 0.84rem !important;
            }
            .driver-popover-footer {
                margin-top: 10px !important;
                padding-top: 8px !important;
            }
            .driver-popover-footer button {
                padding: 5px 10px !important;
                font-size: 0.78rem !important;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const role = "{{ $userRole }}";
            const autoStart = {{ $hasSeenOnboarding ? 'false' : 'true' }};
            const isMobile = window.innerWidth < 768;

            // ══════════════════ SISWA DESKTOP STEPS ══════════════════
            const stepsSiswaDesktop = [
                {
                    element: '.brand-link',
                    popover: {
                        title: '👋 Selamat Datang di Paradise of Math!',
                        description: 'Halo {{ e($currentUser->name) }}! Mari kita mulai tur singkat untuk mengenal fitur-fitur utama akun Anda.',
                        side: "bottom", align: 'start'
                    }
                },
                {
                    element: '[data-tour="siswa-tambah-pelajaran"]',
                    popover: {
                        title: '📚 Tambah Pelajaran',
                        description: 'Di menu ini Anda dapat memilih mata pelajaran baru.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="siswa-jadwal"]',
                    popover: {
                        title: '📅 Jadwal Belajar',
                        description: 'Cek jadwal sesi les dan agenda kelas Anda agar tidak terlewatkan.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="siswa-ujian"]',
                    popover: {
                        title: '✏️ Latihan Soal & Ujian',
                        description: 'Kerjakan soal-soal latihan interaktif dan evaluasi hasil belajar Anda di menu ini.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="siswa-transkip"]',
                    popover: {
                        title: '📊 Transkrip Nilai',
                        description: 'Pantau perkembangan skor, grafik kemampuan, dan laporan belajar Anda secara berkala.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="siswa-chat"]',
                    popover: {
                        title: '💬 Chat Guru',
                        description: 'Ingin bertanya soal kesulitan materi matematika? Anda bisa langsung berkonsultasi dengan guru di sini.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="siswa-panduan"]',
                    popover: {
                        title: '💡 Panduan Penggunaan',
                        description: 'Jika suatu saat Anda ingin mengulang tur ini, klik menu "Panduan Penggunaan" di sidebar. Selamat belajar!',
                        side: "right", align: 'center'
                    }
                }
            ];

            // ══════════════════ SISWA MOBILE STEPS ══════════════════
            const stepsSiswaMobile = [
                {
                    element: '.brand-link',
                    popover: {
                        title: '👋 Selamat Datang di Paradise of Math!',
                        description: 'Halo {{ e($currentUser->name) }}! Mari mengenal tombol navigasi utama di bagian bawah ponsel Anda.',
                        side: "bottom", align: 'start'
                    }
                },
                {
                    element: '[data-tour-mobile="siswa-dashboard"]',
                    popover: {
                        title: '🏠 Home Dashboard',
                        description: 'Halaman utama untuk melihat rangkuman aktivitas & paket belajar Anda.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="siswa-jadwal"]',
                    popover: {
                        title: '📅 Class Room & Jadwal',
                        description: 'Akses ruang kelas dan cek jadwal sesi bimbingan matematika Anda di sini.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="siswa-tambah-pelajaran"]',
                    popover: {
                        title: '📚 Tambah Mapel',
                        description: 'Buka menu ini untuk mendaftar dan menambah mata pelajaran les baru.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="siswa-chat"]',
                    popover: {
                        title: '💬 Chat Guru',
                        description: 'Fitur pesan langsung untuk bertanya atau berkonsultasi dengan Guru pembimbing.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="siswa-account"]',
                    popover: {
                        title: '👤 Akun & Profil',
                        description: 'Akses menu akun untuk melihat biodata, pengubahan password, dan opsi profil Anda.',
                        side: "top", align: 'center'
                    }
                }
            ];

            // ══════════════════ GURU DESKTOP STEPS ══════════════════
            const stepsGuruDesktop = [
                {
                    element: '.brand-link',
                    popover: {
                        title: '👋 Selamat Datang Guru Paradise of Math!',
                        description: 'Halo {{ e($currentUser->name) }}! Mari ikuti tur singkat untuk mengenal menu pengelolaan kelas Anda.',
                        side: "bottom", align: 'start'
                    }
                },
                {
                    element: '[data-tour="guru-jadwal"]',
                    popover: {
                        title: '📅 Jadwal Mengajar',
                        description: 'Pantau agenda jam les mengajar harian dan sesi bimbingan siswa di menu ini.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="guru-siswa"]',
                    popover: {
                        title: '👥 Daftar Siswa Anda',
                        description: 'Lihat seluruh siswa yang terdaftar dalam bimbingan Anda beserta status belajar mereka.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="guru-ujian"]',
                    popover: {
                        title: '📝 Penugasan Ujian Siswa',
                        description: 'Kelola rilis ujian, atur waktu pelaksanaan, serta periksa hasil jawaban siswa.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="guru-modul"]',
                    onHighlightStarted: () => {
                        const parent = document.getElementById('tour-modul-parent');
                        if (parent) {
                            parent.classList.add('menu-open');
                            const treeview = parent.querySelector('.nav-treeview');
                            if (treeview) treeview.style.display = 'block';
                        }
                    },
                    popover: {
                        title: '📖 Modul & Bank Soal',
                        description: 'Menu ini berisi "Input Soal" (membuat bank soal baru) dan "List Soal" (melihat & mengedit daftar soal).',
                        side: "right", align: 'center',
                        onPopoverRendered: () => {
                            const parent = document.getElementById('tour-modul-parent');
                            if (parent) {
                                parent.classList.add('menu-open');
                                const treeview = parent.querySelector('.nav-treeview');
                                if (treeview) treeview.style.display = 'block';
                            }
                        }
                    }
                },
                {
                    element: '[data-tour="guru-chat"]',
                    popover: {
                        title: '💬 Chat Realtime Guru',
                        description: 'Diskusikan pertanyaan materi atau beri arahan kepada siswa secara langsung melalui perpesanan ini.',
                        side: "right", align: 'center'
                    }
                },
                {
                    element: '[data-tour="guru-panduan"]',
                    popover: {
                        title: '💡 Panduan Penggunaan',
                        description: 'Anda selalu bisa memicu ulang tur petunjuk ini kapan saja via tombol "Panduan Penggunaan" di sidebar.',
                        side: "right", align: 'center'
                    }
                }
            ];

            // ══════════════════ GURU MOBILE STEPS ══════════════════
            const stepsGuruMobile = [
                {
                    element: '.brand-link',
                    popover: {
                        title: '👋 Selamat Datang Guru Paradise of Math!',
                        description: 'Halo {{ e($currentUser->name) }}! Mari mengenal tombol navigasi utama di bagian bawah ponsel Anda.',
                        side: "bottom", align: 'start'
                    }
                },
                {
                    element: '[data-tour-mobile="guru-dashboard"]',
                    popover: {
                        title: '🏠 Dashboard Guru',
                        description: 'Ringkasan aktivitas mengajar dan info siswa bimbingan aktif Anda.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="guru-modul"]',
                    popover: {
                        title: '📖 Input Soal & Modul',
                        description: 'Menu cepat untuk membuat bank soal baru dan menyusun materi kuis.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="guru-ujian"]',
                    popover: {
                        title: '📝 Penugasan Ujian',
                        description: 'Kelola rilis ujian siswa dan periksa hasil evaluasi pengerjaan.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="guru-jadwal"]',
                    popover: {
                        title: '📅 Jadwal Mengajar',
                        description: 'Pantau agenda jam bimbingan dan kelas les matematika Anda.',
                        side: "top", align: 'center'
                    }
                },
                {
                    element: '[data-tour-mobile="guru-chat"]',
                    popover: {
                        title: '💬 Chat Siswa',
                        description: 'Konsultasi & diskusi perpesanan langsung dengan siswa bimbingan.',
                        side: "top", align: 'center'
                    }
                }
            ];

            // Select step list based on Role and Screen Size (Mobile vs Desktop)
            let selectedList = [];
            if (role === 'siswa') {
                selectedList = isMobile ? stepsSiswaMobile : stepsSiswaDesktop;
            } else {
                selectedList = isMobile ? stepsGuruMobile : stepsGuruDesktop;
            }

            const activeSteps = selectedList.filter(step => {
                const el = document.querySelector(step.element);
                return el !== null && el.offsetParent !== null;
            });

            if (typeof window.driver === 'undefined' || typeof window.driver.js === 'undefined') {
                console.error("Driver.js library failed to load.");
                return;
            }

            const driver = window.driver.js.driver;
            const driverObj = driver({
                showProgress: true,
                animate: true,
                allowClose: true,
                doneBtnText: 'Selesai ✨',
                nextBtnText: 'Lanjut ›',
                prevBtnText: '‹ Kembali',
                progressText: 'Langkah @{{current}} dari @{{total}}',
                steps: activeSteps,
                onDestroyed: function () {
                    fetch("{{ route('onboarding.complete') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).catch(err => console.log('Onboarding status updated.'));
                }
            });

            // Global trigger function to restart tour anytime
            window.startOnboardingTour = function () {
                $('#modalMobileGuruMenu').modal('hide');
                driverObj.drive();
            };

            // Auto start if user hasn't seen it yet
            if (autoStart && activeSteps.length > 0) {
                setTimeout(function () {
                    driverObj.drive();
                }, 700);
            }
        });
    </script>
@endif
