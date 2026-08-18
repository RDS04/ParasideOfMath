@extends('layout.app')

@section('title', 'Chat Guru · Paradise of Math')

@section('content')
    <div id="chat-page-scope">
        <!-- Content Header -->
        <div class="content-header py-3">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center page-title-mobile">
                            <i class="fas fa-comments text-purple-600 mr-2.5"></i> Chat Guru
                        </h1>
                        <p class="text-sm text-slate-500 mb-0 mt-1">
                            Hubungi langsung guru pendamping mata pelajaran Anda.
                        </p>
                    </div>
                    <div class="col-sm-5">
                        <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0 mt-2 mt-sm-0">
                            <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="text-purple-600 font-semibold">Dashboard</a></li>
                            <li class="breadcrumb-item active text-slate-500">Chat Guru</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content pb-4">
            <div class="container-fluid">

                <div class="row mb-3 chat-shell" style="height: 600px; max-height: calc(100vh - 200px);">

                    <!-- LEFT COLUMN: CONTACTS LIST -->
                    <div class="col-md-4 h-100 mb-3 mb-md-0" id="contacts-column">
                        <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100 flex flex-col bg-white">
                            <div class="card-header bg-purple-950 text-white py-3">
                                <h5 class="card-title font-weight-bold mb-0 text-sm">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i> Guru Anda
                                </h5>
                            </div>

                            <div id="contacts-list-container" class="card-body p-0 overflow-y-auto flex-1">
                                <div class="text-center py-5 text-muted text-xs">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-3 text-purple-400"></i>
                                    <p class="mb-0">Memuat daftar guru...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: ACTIVE CHAT SCREEN -->
                    <div class="col-md-8 h-100" id="chat-column">
                        <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100 flex flex-col bg-white" id="active-chat-card">
                            <!-- Welcome Screen -->
                            <div id="chat-welcome-screen" class="h-100 flex flex-col items-center justify-center text-center p-5">
                                <div class="w-20 h-20 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center mb-4 shadow-xs">
                                    <i class="fas fa-comments text-3xl"></i>
                                </div>
                                <h4 class="font-weight-extrabold text-purple-950 mb-2">Chat dengan Guru</h4>
                                <p class="text-xs text-muted max-w-sm">
                                    Pilih salah satu guru di panel sebelah kiri untuk mulai mengirimkan pesan.
                                </p>
                            </div>

                            <!-- Active Chat Screen -->
                            <div id="chat-active-screen" class="h-100 flex flex-col hidden">
                                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <button class="btn btn-sm btn-link d-md-none text-purple-600 mr-2 p-0 border-0" onclick="goBackToContacts()">
                                            <i class="fas fa-arrow-left text-lg"></i>
                                        </button>
                                        <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 40px; height: 40px; font-size: 16px;">
                                            G
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-purple-950 mb-0" id="active-contact-name">Guru</h6>
                                            <small class="text-[10px] text-muted" id="active-contact-sub">Mata Pelajaran</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-200 px-2.5 py-1 text-xxs font-bold uppercase rounded-pill">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 d-inline-block mr-1"></span> Guru Pendamping
                                    </span>
                                </div>

                                <div id="messages-container" class="flex-1 p-4 overflow-y-auto bg-slate-50/50 space-y-3 text-xs">
                                    <!-- Loaded dynamically -->
                                </div>

                                <form id="send-form" class="card-footer bg-white border-top p-3 d-flex align-items-center gap-3">
                                    <input type="text" id="input-text" placeholder="Ketik pesan Anda..." autocomplete="off" class="form-control rounded-xl px-4 py-3 text-sm flex-1 focus:ring-purple-600 border-light bg-light" required>
                                    <button type="submit" class="btn btn-purple rounded-xl px-4 py-2.5 font-weight-bold text-sm shadow-sm" style="background-color: #4c1d95; color: white;">
                                        <i class="fas fa-paper-plane mr-1.5"></i> Kirim
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Empty state ketika belum ada guru sama sekali (ditampilkan via JS jika kosong) -->
                <div id="no-guru-state" class="card border-0 shadow-sm rounded-2xl text-center py-5 px-4 bg-white hidden">
                    <div class="card-body">
                        <div class="mx-auto mb-3 rounded-full bg-purple-50 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="fas fa-user-slash text-purple-400 fa-2x"></i>
                        </div>
                        <h5 class="font-bold text-purple-950 mb-1">Belum Ada Guru Pendamping</h5>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-0">
                            Guru pendamping Anda belum ditentukan oleh Admin. Silakan hubungi Admin untuk informasi lebih lanjut.
                        </p>
                    </div>
                </div>

            </div>
        </section>
    </div>
    
    <style>
        #chat-page-scope .flex { display: flex !important; }
        #chat-page-scope .flex-col { flex-direction: column !important; }
        #chat-page-scope .flex-1 { flex: 1 1 0% !important; }
        #chat-page-scope .overflow-y-auto { overflow-y: auto !important; }
        #chat-page-scope .h-100 { height: 100% !important; }
        #chat-page-scope .hidden { display: none !important; }
        #chat-page-scope .gap-2\.5 { gap: 10px; }
        #chat-page-scope .gap-3 { gap: 12px; }
        #chat-page-scope .rounded-2xl { border-radius: 20px !important; }
        #chat-page-scope .rounded-xl { border-radius: 12px !important; }
        #chat-page-scope .text-xxs { font-size: 0.65rem; }
        #chat-page-scope .text-\[10px\] { font-size: 10px; }
        #chat-page-scope .bg-light { background-color: #f8fafc !important; }
        #chat-page-scope .border-light { border-color: #f1f5f9 !important; }

        #chat-page-scope .contact-item {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        #chat-page-scope .contact-item:hover {
            background-color: #f8fafc;
        }
        #chat-page-scope .contact-item.active {
            background-color: #f3e8ff !important;
            border-left: 4px solid #7c3aed;
        }
        #chat-page-scope .btn-purple {
            transition: all 0.2s ease;
        }
        #chat-page-scope .btn-purple:hover {
            background-color: #3b0764 !important;
        }

        #chat-page-scope #messages-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto !important;
            max-height: calc(600px - 140px) !important;
            min-height: 0;
        }

        #chat-page-scope .page-title-mobile { font-size: 1.15rem; }

        @media (max-width: 767.98px) {
            #chat-page-scope #chat-column {
                display: none;
            }
        }

        @media (max-width: 576px) {
            #chat-page-scope .content-header {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
            #chat-page-scope .chat-shell {
                height: calc(100vh - 260px) !important;
            }
            #chat-page-scope #messages-container {
                max-height: none !important;
            }
        }
    </style>

    <script>
        let currentSessionId = null;
        let currentContactName = 'Guru';
        let contactPoll = null;
        let messagePoll = null;
        let renderedMsgIds = new Set();

        document.addEventListener('DOMContentLoaded', () => {
            updateResponsiveView();
            window.addEventListener('resize', updateResponsiveView);

            loadContacts();
            contactPoll = setInterval(loadContacts, 4000);

            const sendForm = document.getElementById('send-form');
            const inputText = document.getElementById('input-text');

            if (sendForm) {
                sendForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const text = inputText.value.trim();
                    if (!text || !currentSessionId) return;

                    sendChatMessage(text);
                    inputText.value = '';
                });
            }
        });

        function updateResponsiveView() {
            const leftCol = document.getElementById('contacts-column');
            const rightCol = document.getElementById('chat-column');

            if (window.innerWidth < 768) {
                if (currentSessionId === null) {
                    leftCol.style.display = 'block';
                    rightCol.style.display = 'none';
                } else {
                    leftCol.style.display = 'none';
                    rightCol.style.display = 'block';
                }
            } else {
                leftCol.style.display = 'block';
                rightCol.style.display = 'block';
            }
        }

        function goBackToContacts() {
            currentSessionId = null;
            if (messagePoll) {
                clearInterval(messagePoll);
                messagePoll = null;
            }

            document.getElementById('chat-active-screen').classList.add('hidden');
            document.getElementById('chat-active-screen').classList.remove('flex');
            document.getElementById('chat-welcome-screen').classList.remove('hidden');
            document.getElementById('chat-welcome-screen').classList.add('flex');

            updateResponsiveView();
            loadContacts();
        }

        function loadContacts() {
            fetch('{{ route('siswa.chat.contacts') }}')
                .then(res => res.json())
                .then(data => {
                    const listContainer = document.getElementById('contacts-list-container');
                    const noGuruState = document.getElementById('no-guru-state');
                    const chatShell = document.querySelector('.chat-shell');
                    if (!listContainer) return;

                    if (data.length === 0) {
                        chatShell.classList.add('hidden');
                        noGuruState.classList.remove('hidden');
                        return;
                    }
                    chatShell.classList.remove('hidden');
                    noGuruState.classList.add('hidden');

                    let html = '';
                    data.forEach(c => {
                        const isActive = c.session_id === currentSessionId ? 'active' : '';
                        const timeStr = c.last_activity ? new Date(c.last_activity).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                        const unreadBadge = c.unread_count > 0
                            ? `<span class="badge badge-danger rounded-circle p-1.5 text-[9px] font-bold d-flex justify-content-center align-items-center" style="width:18px; height:18px; background-color:#ef4444; color: white;">${c.unread_count}</span>`
                            : '';
                        const mapelChips = (c.mapels || []).map(m => `<span class="badge bg-purple-100 text-purple-800 text-[9px] px-1.5 py-0.5 rounded ml-1 font-bold">${m}</span>`).join('');

                        html += `
                            <div onclick="selectContact('${c.session_id}', '${c.contact_name.replace(/'/g, "\\'")}', '${(c.mapels || []).join(', ').replace(/'/g, "\\'")}')" class="contact-item p-3 d-flex align-items-center justify-content-between ${isActive}">
                                <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                    <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; font-size: 14px;">
                                        ${c.contact_name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="font-weight-bold text-purple-950 text-xs d-flex align-items-center flex-wrap">${c.contact_name}${mapelChips}</div>
                                        <small class="text-[11px] text-slate-500 font-medium block text-truncate mt-0.5" style="max-width: 170px;">${c.last_message}</small>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 flex flex-col items-end gap-1">
                                    <span class="text-[9px] text-slate-400 font-semibold">${timeStr}</span>
                                    ${unreadBadge}
                                </div>
                            </div>
                        `;
                    });

                    listContainer.innerHTML = html;
                })
                .catch(err => console.error("Error loading contacts:", err));
        }

        function selectContact(sessionId, name, mapelStr) {
            currentSessionId = sessionId;
            currentContactName = name;
            renderedMsgIds.clear();

            const container = document.getElementById('messages-container');
            if (container) container.innerHTML = '';

            document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));

            updateResponsiveView();

            document.getElementById('chat-welcome-screen').classList.add('hidden');
            document.getElementById('chat-welcome-screen').classList.remove('flex');
            const activeScreen = document.getElementById('chat-active-screen');
            activeScreen.classList.remove('hidden');
            activeScreen.classList.add('flex');

            document.getElementById('active-contact-name').textContent = name;
            document.getElementById('active-contact-sub').textContent = mapelStr || 'Guru Pendamping';
            const avatar = document.querySelector('#chat-active-screen .avatar');
            if (avatar) avatar.textContent = name.charAt(0).toUpperCase();

            loadMessages();
            if (messagePoll) clearInterval(messagePoll);
            messagePoll = setInterval(loadMessages, 1500);

            loadContacts();
        }

        function loadMessages() {
            if (!currentSessionId) return;

            fetch(`/siswa/chat/messages/${currentSessionId}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('messages-container');
                    if (!container) return;

                    let hasNewMessages = false;

                    data.forEach(msg => {
                        const msgId = msg.id || (msg.created_at + '_' + msg.message);
                        if (!renderedMsgIds.has(msgId)) {
                            renderedMsgIds.add(msgId);
                            hasNewMessages = true;

                            const isOwn = msg.sender_role === 'siswa';
                            const msgDiv = document.createElement('div');

                            if (isOwn) {
                                msgDiv.className = 'flex items-start gap-2 justify-end chat-msg-row';
                                msgDiv.innerHTML = `
                                    <div class="bg-purple-600 text-white p-3 rounded-2xl rounded-tr-none shadow-xs max-w-[70%] break-words">
                                        ${msg.message}
                                    </div>
                                `;
                            } else {
                                msgDiv.className = 'flex items-start gap-2 chat-msg-row';
                                msgDiv.innerHTML = `
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center flex-shrink-0" style="font-size: 11px;">
                                        ${(msg.sender_name || 'G').charAt(0).toUpperCase()}
                                    </div>
                                    <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-slate-100 shadow-xs max-w-[70%] break-words">
                                        ${msg.message}
                                    </div>
                                `;
                            }
                            container.appendChild(msgDiv);
                        }
                    });

                    if (hasNewMessages) {
                        container.scrollTop = container.scrollHeight;
                    }
                })
                .catch(err => console.error("Error loading messages:", err));
        }

        function sendChatMessage(text) {
            if (!currentSessionId) return;

            fetch('{{ route('siswa.chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_id: currentSessionId,
                    message: text
                })
            })
            .then(res => res.json())
            .then(data => {
                loadMessages();
                loadContacts();
            })
            .catch(err => console.error("Error sending message:", err));
        }
    </script>
@endsection