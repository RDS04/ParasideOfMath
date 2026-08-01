import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Find logo source
    let logoSrc = '/images/logoPM.webp';
    const existingLogo = document.querySelector('img[src*="logoPM.webp"]');
    if (existingLogo) {
        logoSrc = existingLogo.src;
    }

    // 2. Create the loading overlay element
    const overlay = document.createElement('div');
    overlay.id = 'globalLoadingOverlay';
    overlay.className = 'fixed inset-0 z-[9999] flex flex-col items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xs transition-all duration-300 opacity-0 pointer-events-none';
    overlay.innerHTML = `
        <div class="relative w-24 h-24 flex items-center justify-center">
            <!-- Ripple/Radar waves -->
            <div class="absolute inset-0 rounded-full border-4 border-purple-500/20 animate-ping"></div>
            <div class="absolute inset-2 rounded-full border-4 border-purple-500/40"></div>
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-purple-500 border-b-purple-500 animate-spin" style="animation-duration: 1.5s;"></div>
            <div class="absolute inset-2 rounded-full border-4 border-transparent border-r-purple-500 border-l-purple-500 animate-spin" style="animation-duration: 1s; animation-direction: reverse;"></div>
            <img src="${logoSrc}" alt="Logo" class="w-12 h-12 object-contain animate-pulse" />
        </div>
        <p id="globalLoadingText" class="text-white font-bold mt-6 tracking-wide text-sm text-center max-w-xs animate-pulse">
            Memuat halaman...
        </p>
    `;
    document.body.appendChild(overlay);

    // Helper functions to show/hide
    window.showLoading = (text = 'Memproses data...') => {
        const textEl = overlay.querySelector('#globalLoadingText');
        if (textEl) textEl.textContent = text;
        
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
    };

    window.hideLoading = () => {
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0', 'pointer-events-none');
    };

    // If the page is still loading, show the preloader and hide it on load
    if (document.readyState !== 'complete') {
        window.showLoading('Memuat halaman...');
        window.addEventListener('load', window.hideLoading);
        // Fallback hide after 8 seconds in case window.load doesn't fire or resources fail to load
        setTimeout(window.hideLoading, 8000);
    } else {
        window.hideLoading();
    }

    // Handle form submissions
    document.addEventListener('submit', (e) => {
        // Skip if default prevented
        if (e.defaultPrevented) return;
        
        // Skip if form has target="_blank"
        if (e.target.target === '_blank') return;
        
        // Check if there is a custom loading text or a generic one
        let loadingText = 'Memproses data...';
        const submitBtn = e.target.querySelector('[type="submit"]');
        if (submitBtn && submitBtn.dataset.loadingText) {
            loadingText = submitBtn.dataset.loadingText;
        } else if (e.target.dataset.loadingText) {
            loadingText = e.target.dataset.loadingText;
        } else {
            // Context-aware messages based on forms
            const action = e.target.getAttribute('action') || '';
            const method = e.target.getAttribute('method') || 'get';
            
            if (action.includes('login')) {
                loadingText = 'Masuk ke akun Anda...';
            } else if (action.includes('register')) {
                loadingText = 'Membuat akun Anda...';
            } else if (action.includes('siswa/biodata') || action.includes('biodata')) {
                loadingText = 'Menyimpan biodata Anda...';
            } else if (action.includes('payment') || action.includes('bayar')) {
                loadingText = 'Memproses pembayaran Anda...';
            } else if (method.toLowerCase() === 'post') {
                loadingText = 'Menyimpan data...';
            }
        }
        
        window.showLoading(loadingText);
    });

    // Handle link clicks (Page transitions)
    document.addEventListener('click', (e) => {
        // Find nearest <a> tag
        const link = e.target.closest('a');
        if (!link) return;

        // Skip middle-clicks, right-clicks, cmd/ctrl-clicks
        if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        const href = link.getAttribute('href');
        if (!href) return;

        // Skip internal hash links, javascript void, external links, file downloads, mailto/tel
        if (
            href.startsWith('#') ||
            href.startsWith('javascript:') ||
            link.getAttribute('target') === '_blank' ||
            link.hasAttribute('download') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            href.startsWith('sms:')
        ) {
            return;
        }

        // Parse URLs to compare hosts
        try {
            const url = new URL(href, window.location.href);
            // Check if same origin (same domain and port)
            if (url.origin !== window.location.origin) return;
            
            // Skip if it's the exact same page with a different hash
            if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash !== window.location.hash) {
                return;
            }
        } catch (err) {
            // If parsing fails, skip
            return;
        }

        // Skip elements with data-no-loading or class "no-loading"
        if (link.hasAttribute('data-no-loading') || link.classList.contains('no-loading')) return;

        // Custom loading text if present
        const customText = link.dataset.loadingText || 'Mengalihkan halaman...';
        window.showLoading(customText);
    });

    // 3. Register FCM if Admin
    const userRoleMeta = document.querySelector('meta[name="user-role"]');
    const userRole = userRoleMeta ? userRoleMeta.getAttribute('content') : '';

    if (userRole === 'admin') {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then((registration) => {
                    console.log('FCM SW registered successfully:', registration);
                    
                    // Request Notification Permission
                    Notification.requestPermission().then((permission) => {
                        if (permission === 'granted') {
                            console.log('Notification permission granted.');
                            if (window.firebase && window.FCM_VAPID_KEY) {
                                try {
                                    const messaging = firebase.messaging();
                                    messaging.getToken({ vapidKey: window.FCM_VAPID_KEY })
                                        .then((currentToken) => {
                                            if (currentToken) {
                                                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                                                const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
                                                
                                                fetch('/admin/save-token', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken
                                                    },
                                                    body: JSON.stringify({ token: currentToken })
                                                })
                                                .then(r => r.json())
                                                .then(data => console.log('FCM token saved on server:', data))
                                                .catch(err => console.error('Failed to save FCM token:', err));
                                            }
                                        });
                                } catch (e) {
                                    console.error('Firebase messaging token retrieval failed:', e);
                                }
                            }
                        }
                    });
                })
                .catch((err) => {
                    console.error('FCM SW registration failed:', err);
                });
        }
    }
});

