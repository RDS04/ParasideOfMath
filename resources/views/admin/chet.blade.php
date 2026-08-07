@extends('layout.app')

@section('title', 'Admin Realtime Chat · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Chat Realtime Pengunjung</h1>
                    <p class="text-sm text-muted mb-0">Hubungi langsung pengunjung landing page (Anonymous) secara real-time.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Chat Realtime</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <div class="row mb-5" style="height: 600px; max-height: calc(100vh - 200px);">
                
                <!-- LEFT COLUMN: SESSIONS LIST (4 cols) -->
                <div class="col-md-4 h-100 mb-3 mb-md-0" id="sessions-column">
                    <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100 flex flex-col bg-white">
                        <div class="card-header bg-purple-950 text-white py-3">
                            <h5 class="card-title font-weight-bold mb-0 text-sm">
                                <i class="fas fa-comments mr-2"></i> Daftar Percakapan
                            </h5>
                        </div>
                        
                        <!-- Sessions scrollable list -->
                        <div id="sessions-list-container" class="card-body p-0 overflow-y-auto flex-1">
                            <div class="text-center py-5 text-muted text-xs">
                                <i class="fas fa-spinner fa-spin fa-2x mb-3 text-purple-400"></i>
                                <p class="mb-0">Memuat percakapan...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: ACTIVE CHAT SCREEN (8 cols) -->
                <div class="col-md-8 h-100" id="chat-column">
                    <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100 flex flex-col bg-white" id="active-chat-card">
                        <!-- Welcome Screen (Default before selecting a chat) -->
                        <div id="chat-welcome-screen" class="h-100 flex flex-col items-center justify-center text-center p-5">
                            <div class="w-20 h-20 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center mb-4 shadow-xs">
                                <i class="fas fa-paper-plane text-3xl"></i>
                            </div>
                            <h4 class="font-weight-extrabold text-purple-950 mb-2">Konsultasi Live Chat</h4>
                            <p class="text-xs text-muted max-w-sm">
                                Silakan pilih salah satu percakapan di panel sebelah kiri untuk mulai mengirimkan pesan bantuan realtime kepada pengunjung.
                            </p>
                        </div>

                        <!-- Main Chat Screen (Hidden by default) -->
                        <div id="chat-active-screen" class="h-100 flex flex-col hidden">
                            <!-- Active Chat Header -->
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2.5">
                                    <!-- Back button visible only on mobile -->
                                    <button class="btn btn-sm btn-link d-md-none text-purple-600 mr-2 p-0 border-0" onclick="goBackToSessions()">
                                        <i class="fas fa-arrow-left text-lg"></i>
                                    </button>
                                    <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 40px; height: 40px; font-size: 16px;">
                                        A
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-purple-950 mb-0" id="active-visitor-name">Anonymous</h6>
                                        <small class="text-[10px] text-muted font-mono" id="active-visitor-id">visitor_xxxxx</small>
                                    </div>
                                </div>
                                <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-200 px-2.5 py-1 text-xxs font-bold uppercase rounded-pill">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 d-inline-block mr-1"></span> Terhubung
                                </span>
                            </div>

                            <!-- Messages Area -->
                            <div id="admin-messages-container" class="flex-1 p-4 overflow-y-auto bg-slate-50/50 space-y-3 text-xs">
                                <!-- Loaded dynamically -->
                            </div>

                            <!-- Footer Send Form -->
                            <form id="admin-send-form" class="card-footer bg-white border-top p-3 d-flex align-items-center gap-3">
                                <input type="text" id="admin-input-text" placeholder="Ketik balasan Anda..." autocomplete="off" class="form-control rounded-xl px-4 py-3 text-sm flex-1 focus:ring-purple-600 border-light bg-light" required>
                                <button type="submit" class="btn btn-purple rounded-xl px-4 py-2.5 font-weight-bold text-sm shadow-sm" style="background-color: #4c1d95; color: white;">
                                    <i class="fas fa-paper-plane mr-1.5"></i> Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <style>
        .flex { display: flex !important; }
        .flex-col { flex-direction: column !important; }
        .flex-1 { flex: 1 1 0% !important; }
        .overflow-y-auto { overflow-y: auto !important; }
        .h-100 { height: 100% !important; }
        .hidden { display: none !important; }
        .gap-2.5 { gap: 10px; }
        .gap-3 { gap: 12px; }
        .rounded-2xl { border-radius: 20px !important; }
        .rounded-xl { border-radius: 12px !important; }
        .text-xxs { font-size: 0.65rem; }
        .text-[10px] { font-size: 10px; }
        .bg-light { background-color: #f8fafc !important; }
        .border-light { border-color: #f1f5f9 !important; }
        
        .session-item {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        .session-item:hover {
            background-color: #f8fafc;
        }
        .session-item.active {
            background-color: #f3e8ff !important;
            border-left: 4px solid #7c3aed;
        }
        .btn-purple {
            transition: all 0.2s ease;
        }
        .btn-purple:hover {
            background-color: #3b0764 !important;
        }

        /* Message scrolling layout configuration */
        #admin-messages-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto !important;
            max-height: calc(600px - 140px) !important;
            min-height: 0;
        }

        /* Responsive UI rules */
        @media (max-width: 767.98px) {
            #chat-column {
                display: none;
            }
            .content-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>

    <script>
        let currentSessionId = null;
        let currentVisitorName = 'Anonymous';
        let sessionPoll = null;
        let messagePoll = null;
        let renderedMsgIds = new Set();

        document.addEventListener('DOMContentLoaded', () => {
            // Initial responsive layouts check
            updateResponsiveView();
            window.addEventListener('resize', updateResponsiveView);

            // Start sessions list polling
            loadSessions();
            sessionPoll = setInterval(loadSessions, 3000);

            // Handle Send Form
            const sendForm = document.getElementById('admin-send-form');
            const inputText = document.getElementById('admin-input-text');

            if (sendForm) {
                sendForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const text = inputText.value.trim();
                    if (!text || !currentSessionId) return;

                    sendAdminMessage(text);
                    inputText.value = '';
                });
            }
        });

        // Responsive visibility handler
        function updateResponsiveView() {
            const leftCol = document.getElementById('sessions-column');
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

        // Return back to session list on mobile view
        function goBackToSessions() {
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
            loadSessions();
        }

        // Load sessions list from server
        function loadSessions() {
            fetch('/admin/chat/sessions')
                .then(res => res.json())
                .then(data => {
                    const listContainer = document.getElementById('sessions-list-container');
                    if (!listContainer) return;

                    if (data.length === 0) {
                        listContainer.innerHTML = `
                            <div class="text-center py-5 text-muted text-xs">
                                <i class="fas fa-comments text-slate-300 fa-2x mb-3"></i>
                                <p class="mb-0">Belum ada percakapan masuk.</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    data.forEach(sess => {
                        const isActive = sess.session_id === currentSessionId ? 'active' : '';
                        const dateFormatted = new Date(sess.last_activity).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                        const unreadBadge = sess.unread_count > 0 
                            ? `<span class="badge badge-danger rounded-circle p-1.5 text-[9px] font-bold d-flex justify-content-center align-items-center" style="width:18px; height:18px; background-color:#ef4444; color: white;">${sess.unread_count}</span>` 
                            : '';
                        const displayId = sess.session_id.startsWith('visitor_') 
                            ? 'Pengunjung #' + sess.session_id.replace('visitor_', '') 
                            : 'Pengunjung #' + sess.session_id;
                        
                        const roleBadge = sess.user_role 
                            ? `<span class="badge ${sess.user_role.toLowerCase() === 'siswa' ? 'bg-purple-600 text-white' : 'bg-emerald-600 text-white'} text-[9px] px-1.5 py-0.5 rounded ml-1 font-extrabold">${sess.user_role}</span>` 
                            : '';
                        const avatarBg = sess.user_role ? 'bg-purple-600 text-white' : 'bg-purple-100 text-purple-700';

                        html += `
                            <div onclick="selectSession('${sess.session_id}', '${sess.sender_name.replace(/'/g, "\\'")}')" class="session-item p-3 d-flex align-items-center justify-content-between ${isActive}">
                                <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                    <div class="avatar ${avatarBg} font-bold d-flex justify-content-center align-items-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; font-size: 14px;">
                                        ${sess.sender_name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="font-weight-bold text-purple-950 text-xs d-flex align-items-center flex-wrap">${sess.sender_name} ${roleBadge}</div>
                                        <small class="text-[11px] text-slate-500 font-medium block text-truncate mt-0.5" style="max-width: 170px;">${sess.last_message || displayId}</small>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 flex flex-col items-end gap-1">
                                    <span class="text-[9px] text-slate-400 font-semibold">${dateFormatted}</span>
                                    ${unreadBadge}
                                </div>
                            </div>
                        `;
                    });

                    listContainer.innerHTML = html;
                })
                .catch(err => console.error("Error loading sessions:", err));
        }
        
        // Select a session to load details
        function selectSession(sessionId, name) {
            currentSessionId = sessionId;
            currentVisitorName = name;
            renderedMsgIds.clear();

            const container = document.getElementById('admin-messages-container');
            if (container) container.innerHTML = '';

            // Highlight in list
            document.querySelectorAll('.session-item').forEach(el => el.classList.remove('active'));
            
            // Adjust responsive views
            updateResponsiveView();

            // Hide welcome screen, show chat screen
            document.getElementById('chat-welcome-screen').classList.add('hidden');
            document.getElementById('chat-welcome-screen').classList.remove('flex');
            const activeScreen = document.getElementById('chat-active-screen');
            activeScreen.classList.remove('hidden');
            activeScreen.classList.add('flex');

            // Update Header Name & Avatar
            document.getElementById('active-visitor-name').textContent = name;
            const headerDisplayId = sessionId.startsWith('visitor_') 
                ? 'Pengunjung #' + sessionId.replace('visitor_', '') 
                : 'Pengunjung #' + sessionId;
            document.getElementById('active-visitor-id').textContent = headerDisplayId;
            const avatar = document.querySelector('#chat-active-screen .avatar');
            if (avatar) avatar.textContent = name.charAt(0).toUpperCase();

            // Load messages and restart polling
            loadMessages();
            if (messagePoll) clearInterval(messagePoll);
            messagePoll = setInterval(loadMessages, 1500);
            
            // Immediately reload sessions to update unread badge
            loadSessions();
        }

        // Fetch messages for active session (Incremental Append)
        function loadMessages() {
            if (!currentSessionId) return;

            fetch(`/admin/chat/messages/${currentSessionId}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('admin-messages-container');
                    if (!container) return;

                    let hasNewMessages = false;

                    data.forEach(msg => {
                        const msgId = msg.id || (msg.created_at + '_' + msg.message);
                        if (!renderedMsgIds.has(msgId)) {
                            renderedMsgIds.add(msgId);
                            hasNewMessages = true;

                            const isUser = msg.sender_role === 'admin';
                            const msgDiv = document.createElement('div');
                            
                            if (isUser) {
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
                                        ${(msg.sender_name || 'A').charAt(0).toUpperCase()}
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

        // Send Admin Response
        function sendAdminMessage(text) {
            if (!currentSessionId) return;

            fetch('/admin/chat/send', {
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
                loadSessions();
            })
            .catch(err => console.error("Error sending admin message:", err));
        }
    </script>
@endsection
