/**
 * Paradise of Math - Landing Page JavaScript
 * Handles Mobile Navigation, Scroll Reveals, Swiper Carousels, Book Flipping, Profile Dropdown, and Live Chat Bot.
 */

document.addEventListener('DOMContentLoaded', function () {
    // ── 1. MOBILE MENU TOGGLE ──
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (toggleBtn && mobileMenu) {
        toggleBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // ── 2. SCROLL REVEAL ANIMATIONS ──
    const revealElements = document.querySelectorAll('.reveal-element');
    if (revealElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -40px 0px'
        });

        revealElements.forEach(el => observer.observe(el));
    }

    // ── 3. SWIPER CAROUSEL (PRICING/TESTIMONIALS) ──
    if (document.querySelector('.pricing-swiper')) {
        new Swiper('.pricing-swiper', {
            slidesPerView: 'auto',
            centeredSlides: true,
            grabCursor: true,
            loop: true,
            spaceBetween: 30,
            initialSlide: 3, // Start with Kak Ika card
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
            },
            keyboard: {
                enabled: true,
            },
            breakpoints: {
                320: {
                    spaceBetween: 16
                },
                640: {
                    spaceBetween: 24
                },
                1024: {
                    spaceBetween: 30
                }
            }
        });
    }

    // ── 4. PROFILE DROPDOWN TOGGLE ──
    const dropdownBtn = document.getElementById('profile-dropdown-btn');
    const dropdownMenu = document.getElementById('profile-dropdown-menu');
    const dropdownArrow = document.getElementById('profile-dropdown-arrow');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            if (dropdownArrow) {
                dropdownArrow.classList.toggle('rotate-180');
            }
        });

        document.addEventListener('click', (e) => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
                if (dropdownArrow) {
                    dropdownArrow.classList.remove('rotate-180');
                }
            }
        });
    }

    // ── 5. REALTIME CHAT WIDGET CONTROLLER ──
    const chatTrigger = document.getElementById('chat-trigger-btn');
    const chatWindow = document.getElementById('chat-window-box');
    const chatClose = document.getElementById('chat-close-btn');
    const chatForm = document.getElementById('chat-input-form');
    const chatInput = document.getElementById('chat-input-text');

    if (chatTrigger && chatWindow && chatClose) {
        // Toggle Open/Close
        chatTrigger.addEventListener('click', () => {
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                setTimeout(() => {
                    chatWindow.style.opacity = '1';
                    chatWindow.style.transform = 'scale(1)';
                }, 10);
                
                // Remove notification badge on first open
                const badge = chatTrigger.querySelector('span.bg-rose-500');
                if (badge) badge.remove();
            } else {
                closeChat();
            }
        });

        chatClose.addEventListener('click', (e) => {
            e.stopPropagation();
            closeChat();
        });

        function closeChat() {
            chatWindow.style.opacity = '0';
            chatWindow.style.transform = 'scale(0.95)';
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
        }

        // Handle Text Send Submit
        if (chatForm) {
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const text = chatInput.value.trim();
                if (!text) return;

                appendMessage(text, 'user');
                chatInput.value = '';

                // Simulate bot typing indicator
                showTypingIndicator();
                setTimeout(() => {
                    removeTypingIndicator();
                    handleBotResponse(text);
                }, 1200);
            });
        }
    }
});

// ── 6. INTERACTIVE BOOK FLIPPING COMPONENT ──
const book = document.getElementById('book');
const dots = document.querySelectorAll('.dot');
let page = 0;

function setPage(p) {
    if (!book) return;
    page = Math.max(0, Math.min(1, p));
    book.classList.toggle('flipped', page === 1);
    dots.forEach(d => {
        const active = Number(d.dataset.p) === page;
        d.classList.toggle('bg-violet-900', active);
        d.classList.toggle('w-5', active);
        d.classList.toggle('bg-violet-900/30', !active);
        d.classList.toggle('w-2', !active);
    });
}

if (book) {
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    if (nextBtn) nextBtn.addEventListener('click', () => setPage(page + 1));
    if (prevBtn) prevBtn.addEventListener('click', () => setPage(page - 1));
    
    dots.forEach(d => d.addEventListener('click', () => setPage(Number(d.dataset.p))));

    book.addEventListener('click', (e) => {
        const r = book.getBoundingClientRect();
        const x = e.clientX - r.left;
        if (x > r.width * 0.75) setPage(page + 1);
        else if (x < r.width * 0.25) setPage(page - 1);
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') setPage(page + 1);
        if (e.key === 'ArrowLeft') setPage(page - 1);
    });

    let touchStartX = null;
    book.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    book.addEventListener('touchend', (e) => {
        if (touchStartX === null) return;
        const dx = e.changedTouches[0].clientX - touchStartX;
        if (dx < -40) setPage(page + 1);
        if (dx > 40) setPage(page - 1);
        touchStartX = null;
    }, { passive: true });
}

// ── 7. CHAT MESSAGE HELPERS ──
function appendMessage(text, sender) {
    const container = document.getElementById('chat-messages-container');
    if (!container) return;
    const msgDiv = document.createElement('div');
    msgDiv.className = `flex items-start gap-2 ${sender === 'user' ? 'justify-end' : ''}`;

    if (sender === 'user') {
        msgDiv.innerHTML = `
            <div class="bg-violet-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[80%]">
                ${text}
            </div>
        `;
    } else {
        msgDiv.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 flex-shrink-0">
                <i class="fas fa-robot text-xs"></i>
            </div>
            <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-violet-100/50 shadow-sm max-w-[80%]">
                ${text}
            </div>
        `;
    }

    container.appendChild(msgDiv);
    container.scrollTop = container.scrollHeight;
}

function showTypingIndicator() {
    const container = document.getElementById('chat-messages-container');
    if (!container) return;
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing-indicator';
    typingDiv.className = 'flex items-start gap-2';
    typingDiv.innerHTML = `
        <div class="w-7 h-7 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 flex-shrink-0">
            <i class="fas fa-robot text-xs"></i>
        </div>
        <div class="bg-white px-3 py-2 rounded-2xl rounded-tl-none border border-violet-100/50 shadow-sm max-w-[80%] flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-bounce" style="animation-delay: 0s"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-bounce" style="animation-delay: 0.15s"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-bounce" style="animation-delay: 0.3s"></span>
        </div>
    `;
    container.appendChild(typingDiv);
    container.scrollTop = container.scrollHeight;
}

function removeTypingIndicator() {
    const indicator = document.getElementById('typing-indicator');
    if (indicator) indicator.remove();
}

function sendQuickOption(text) {
    appendMessage(text, 'user');
    showTypingIndicator();
    setTimeout(() => {
        removeTypingIndicator();
        handleBotResponse(text);
    }, 1000);
}

function handleBotResponse(userInput) {
    const inputLower = userInput.toLowerCase();
    let response = '';

    if (inputLower.includes('paket') || inputLower.includes('belajar')) {
        response = `Kami menyediakan kelas bimbingan belajar Matematika, IPA, dan Bahasa Inggris untuk jenjang <strong>SD</strong> dan <strong>SMP</strong>.<br><br>Ingin tanya paket belajar WhatsApp Admin? <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Paradise%20of%20Math,%20saya%20tertarik%20tanya%20paket%20belajar..." target="_blank" class="text-violet-600 font-bold underline">Klik disini untuk WhatsApp</a>`;
    } else if (inputLower.includes('biaya') || inputLower.includes('daftar') || inputLower.includes('harga') || inputLower.includes('promo')) {
        response = `<strong>Promo Khusus Bulan Ini!</strong> 🥳<br>✨ <strong>Gratis biaya pendaftaran!</strong><br>✨ Biaya belajar les bervariasi antara Rp 90K hingga Rp 150K per sesi, tergantung dari jenjang (SD/SMP) dan jumlah sesi pertemuan yang dipilih.<br><br><a href="https://wa.me/6281234567890?text=Halo%20Admin%20Paradise%20of%20Math,%20saya%20tertarik%20tanya%20detail%20biaya..." target="_blank" class="text-violet-600 font-bold underline">Chat Admin WhatsApp</a>`;
    } else if (inputLower.includes('whatsapp') || inputLower.includes('hubungi') || inputLower.includes('admin') || inputLower.includes('kontak')) {
        response = `Tentu! Anda dapat langsung terhubung dengan Admin kami via WhatsApp untuk konsultasi lebih cepat.<br><br>👉 <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Paradise%20of%20Math..." target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-bold no-underline mt-2"><i class="fab fa-whatsapp"></i> Chat WhatsApp</a>`;
    } else {
        response = `Terima kasih atas pesan Anda! Agen Customer Service kami akan segera menanggapi Anda.<br><br>Jika ingin tanggapan instan, silakan hubungi WhatsApp kami langsung:<br><a href="https://wa.me/6281234567890?text=Halo%20Admin%20Paradise%20of%20Math..." target="_blank" class="text-violet-600 font-bold underline">Hubungi Admin di WhatsApp</a>`;
    }

    appendMessage(response, 'bot');
}
