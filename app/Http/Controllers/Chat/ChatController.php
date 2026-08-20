<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Get messages for a session (used by guest).
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $messages = Chat::where('session_id', $request->session_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send message from visitor.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'sender_name' => 'required|string',
            'message' => 'required|string',
            'sender_role' => 'nullable|string|in:visitor,admin,bot',
        ]);

        $senderName = $request->sender_name;
        $chatUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
        if (($senderName === 'Anonymous' || empty($senderName) || str_starts_with($senderName, 'visitor_')) && $chatUser) {
            $senderName = $chatUser->name;
        }

        $role = $request->input('sender_role', 'visitor');
        if (in_array($senderName, ['Customer Service PM', 'System Bot', 'Admin'])) {
            $role = 'admin';
        }

        $chat = Chat::create([
            'session_id' => $request->session_id,
            'sender_name' => $senderName,
            'sender_role' => $role,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($chat);
    }

    /**
     * Admin chat page view.
     */
    public function adminChatPage()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }
        return view('admin.chet');
    }

    /**
     * Get all active chat sessions (grouped by session_id, ordered by latest activity).
     */
    public function getChatSessions()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Fetch registered user & siswa roles into a fast memory map
        $userRoles = \App\Models\User::pluck('role', 'name')->toArray();
        $siswaNames = \App\Models\Siswa::pluck('name')->toArray();
        foreach ($siswaNames as $sName) {
            if (!empty($sName) && !isset($userRoles[$sName])) {
                $userRoles[$sName] = 'siswa';
            }
        }

        // 1. Single optimized aggregation query
        $rawSessions = Chat::select('session_id')
            ->selectRaw('MAX(created_at) as last_activity')
            ->selectRaw('SUM(CASE WHEN sender_role = "visitor" AND is_read = 0 THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('session_id')
            ->orderBy('last_activity', 'desc')
            ->get();

        if ($rawSessions->isEmpty()) {
            return response()->json([]);
        }

        // 2. Batch fetch latest non-admin sender_name per session
        $sessionIds = $rawSessions->pluck('session_id')->toArray();
        $latestVisitorMsgs = Chat::whereIn('session_id', $sessionIds)
            ->where('sender_role', 'visitor')
            ->whereNotIn('sender_name', ['Customer Service PM', 'System Bot', 'Admin', 'admin'])
            ->orderBy('id', 'desc')
            ->get()
            ->unique('session_id')
            ->keyBy('session_id');

        // 3. Batch fetch absolute latest message per session for preview text
        $latestAllMsgs = Chat::whereIn('session_id', $sessionIds)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('session_id')
            ->keyBy('session_id');

        $sessions = [];
        foreach ($rawSessions as $s) {
            $sessionId = $s->session_id;
            $visitorMsg = $latestVisitorMsgs->get($sessionId);
            $senderName = ($visitorMsg && !empty($visitorMsg->sender_name)) ? $visitorMsg->sender_name : 'Anonymous';

            $latestMsg = $latestAllMsgs->get($sessionId);
            $lastMessageText = $latestMsg ? \Illuminate\Support\Str::limit(trim(strip_tags($latestMsg->message)), 35) : 'Belum ada pesan';

            $userRole = null;
            if (isset($userRoles[$senderName])) {
                $userRole = ucfirst($userRoles[$senderName]);
            }

            $sessions[] = [
                'session_id' => $sessionId,
                'sender_name' => $senderName,
                'user_role' => $userRole,
                'last_message' => $lastMessageText,
                'last_activity' => $s->last_activity,
                'unread_count' => (int) $s->unread_count,
            ];
        }

        return response()->json($sessions);
    }

    /**
     * Get messages of a specific session for the admin.
     */
    public function getSessionMessages($session_id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark visitor's messages as read
        Chat::where('session_id', $session_id)
            ->where('sender_role', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Chat::where('session_id', $session_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send message from admin.
     */
    public function adminSendMessage(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $chat = Chat::create([
            'session_id' => $request->session_id,
            'sender_name' => Auth::user()->name ?: 'Admin',
            'sender_role' => 'admin',
            'message' => $request->message,
            'is_read' => true,
        ]);

        return response()->json($chat);
    }

    // ══════════════════════════════════════════════════════════════
    //   CHAT SISWA ⇄ GURU (dibatasi hanya pada guru/siswa yang
    //   memang ditugaskan/diajar sesuai data Admin)
    // ══════════════════════════════════════════════════════════════

    /**
     * Halaman Chat Guru untuk Siswa.
     */
    public function siswaChatPage()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login');
        }

        return view('siswa.chat', compact('siswa'));
    }

    /**
     * Ambil daftar guru yang boleh diajak chat oleh siswa ini (kontak WA-style).
     */
    public function siswaContacts()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $gurus = $this->getSiswaAssignedGurus($siswa);

        if (empty($gurus)) {
            return response()->json([]);
        }

        $sessionIds = array_column($gurus, 'session_id');

        $lastMsgs = Chat::whereIn('session_id', $sessionIds)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('session_id')
            ->keyBy('session_id');

        $unreadCounts = Chat::whereIn('session_id', $sessionIds)
            ->where('sender_role', 'guru')
            ->where('is_read', false)
            ->selectRaw('session_id, COUNT(*) as cnt')
            ->groupBy('session_id')
            ->pluck('cnt', 'session_id');

        $contacts = [];
        foreach ($gurus as $g) {
            $lastMsg = $lastMsgs->get($g['session_id']);
            $contacts[] = [
                'session_id'    => $g['session_id'],
                'contact_name'  => $g['guru_name'],
                'mapels'        => $g['mapels'],
                'last_message'  => $lastMsg ? \Illuminate\Support\Str::limit(trim(strip_tags($lastMsg->message)), 35) : 'Belum ada pesan',
                'last_activity' => $lastMsg ? $lastMsg->created_at : null,
                'unread_count'  => (int) ($unreadCounts[$g['session_id']] ?? 0),
            ];
        }

        // Urutkan berdasarkan aktivitas terakhir (terbaru di atas)
        usort($contacts, function ($a, $b) {
            $aTime = $a['last_activity'] ? strtotime($a['last_activity']) : 0;
            $bTime = $b['last_activity'] ? strtotime($b['last_activity']) : 0;
            return $bTime <=> $aTime;
        });

        return response()->json($contacts);
    }

    /**
     * Ambil pesan untuk 1 sesi chat siswa-guru (dari sisi Siswa).
     */
    public function siswaMessages(Request $request, $session_id)
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$this->siswaCanAccessSession($siswa, $session_id)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Tandai pesan dari guru sudah dibaca
        Chat::where('session_id', $session_id)
            ->where('sender_role', 'guru')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Chat::where('session_id', $session_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Kirim pesan dari Siswa ke Guru.
     */
    public function siswaSendMessage(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'session_id' => 'required|string',
            'message'    => 'required|string',
        ]);

        if (!$this->siswaCanAccessSession($siswa, $request->session_id)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $chat = Chat::create([
            'session_id'  => $request->session_id,
            'sender_name' => $siswa->name,
            'sender_role' => 'siswa',
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return response()->json($chat);
    }

    /**
     * Halaman Chat Siswa untuk Guru.
     */
    public function guruChatPage()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        return view('guru.chat', compact('user'));
    }

    /**
     * Ambil daftar siswa yang boleh diajak chat oleh guru ini (kontak WA-style).
     */
    public function guruContacts()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $siswaList = $this->getGuruAssignedSiswa($user);

        if ($siswaList->isEmpty()) {
            return response()->json([]);
        }

        $sessionMap = [];
        foreach ($siswaList as $siswa) {
            $sessionMap[$siswa->id] = 'sg_' . $siswa->id . '_' . $user->id;
        }
        $sessionIds = array_values($sessionMap);

        $lastMsgs = Chat::whereIn('session_id', $sessionIds)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('session_id')
            ->keyBy('session_id');

        $unreadCounts = Chat::whereIn('session_id', $sessionIds)
            ->where('sender_role', 'siswa')
            ->where('is_read', false)
            ->selectRaw('session_id, COUNT(*) as cnt')
            ->groupBy('session_id')
            ->pluck('cnt', 'session_id');

        $contacts = [];
        foreach ($siswaList as $siswa) {
            $sessionId = $sessionMap[$siswa->id];
            $lastMsg = $lastMsgs->get($sessionId);
            $contacts[] = [
                'session_id'    => $sessionId,
                'contact_name'  => $siswa->name,
                'sekolah'       => $siswa->sekolah,
                'last_message'  => $lastMsg ? \Illuminate\Support\Str::limit(trim(strip_tags($lastMsg->message)), 35) : 'Belum ada pesan',
                'last_activity' => $lastMsg ? $lastMsg->created_at : null,
                'unread_count'  => (int) ($unreadCounts[$sessionId] ?? 0),
            ];
        }

        usort($contacts, function ($a, $b) {
            $aTime = $a['last_activity'] ? strtotime($a['last_activity']) : 0;
            $bTime = $b['last_activity'] ? strtotime($b['last_activity']) : 0;
            return $bTime <=> $aTime;
        });

        return response()->json($contacts);
    }

    /**
     * Ambil pesan untuk 1 sesi chat siswa-guru (dari sisi Guru).
     */
    public function guruMessages(Request $request, $session_id)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$this->guruCanAccessSession($user, $session_id)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Tandai pesan dari siswa sudah dibaca
        Chat::where('session_id', $session_id)
            ->where('sender_role', 'siswa')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Chat::where('session_id', $session_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Kirim pesan dari Guru ke Siswa.
     */
    public function guruSendMessage(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'session_id' => 'required|string',
            'message'    => 'required|string',
        ]);

        if (!$this->guruCanAccessSession($user, $request->session_id)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $chat = Chat::create([
            'session_id'  => $request->session_id,
            'sender_name' => $user->name,
            'sender_role' => 'guru',
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return response()->json($chat);
    }

    // ══════════════════════════════════════════════════════════════
    //   HELPERS
    // ══════════════════════════════════════════════════════════════

    /**
     * Pecah session_id format "sg_{siswaId}_{guruId}" menjadi array, atau null jika invalid.
     */
    private function resolveSgSession(string $sessionId): ?array
    {
        if (!preg_match('/^sg_(\d+)_(\d+)$/', $sessionId, $m)) {
            return null;
        }
        return ['siswa_id' => (int) $m[1], 'guru_id' => (int) $m[2]];
    }

    /**
     * Cek apakah siswa ini boleh akses session_id tertentu
     * (session harus miliknya sendiri & guru tujuan memang ditugaskan admin ke dia).
     */
    private function siswaCanAccessSession($siswa, string $sessionId): bool
    {
        $parsed = $this->resolveSgSession($sessionId);
        if (!$parsed || $parsed['siswa_id'] !== $siswa->id) {
            return false;
        }

        $assignedGurus = $this->getSiswaAssignedGurus($siswa);
        foreach ($assignedGurus as $g) {
            if ($g['guru_id'] === $parsed['guru_id']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Cek apakah guru ini boleh akses session_id tertentu
     * (session harus miliknya sendiri & siswa tujuan memang diajar olehnya).
     */
    private function guruCanAccessSession($guruUser, string $sessionId): bool
    {
        $parsed = $this->resolveSgSession($sessionId);
        if (!$parsed || $parsed['guru_id'] !== $guruUser->id) {
            return false;
        }

        $siswaList = $this->getGuruAssignedSiswa($guruUser);
        return $siswaList->contains('id', $parsed['siswa_id']);
    }

    /**
     * Ambil daftar guru (user_id + nama + mapel) yang ditugaskan Admin ke siswa ini.
     */
    private function getSiswaAssignedGurus($siswa): array
    {
        $biodata = $siswa->biodata ?? [];
        $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];

        $guruMapelMap = []; // [nama_guru => [mapel1, mapel2, ...]]

        if (is_array($tutorPerMapel) && !empty($tutorPerMapel)) {
            foreach ($tutorPerMapel as $mapelName => $guruName) {
                if (!empty($guruName)) {
                    $guruMapelMap[$guruName][] = $mapelName;
                }
            }
        }

        // Fallback: tutor_names flat (checkbox lama, tanpa mapping per mapel)
        if (empty($guruMapelMap)) {
            $tutorNames = $biodata['tutor_names'] ?? [];
            if (is_array($tutorNames)) {
                foreach ($tutorNames as $t) {
                    if (!empty($t)) {
                        $guruMapelMap[$t][] = 'Bimbingan Belajar';
                    }
                }
            }
        }

        // Fallback: parsing dari tipe_paket "Guru: Mapel: Nama, ..."
        if (empty($guruMapelMap) && $siswa->tipe_paket && preg_match('/Guru:\s*([^|)]+)/i', $siswa->tipe_paket, $m)) {
            $parts = array_map('trim', explode(',', $m[1]));
            foreach ($parts as $part) {
                if (str_contains($part, ':')) {
                    [$mp, $gn] = array_map('trim', explode(':', $part, 2));
                    if (!empty($gn)) {
                        $guruMapelMap[$gn][] = $mp;
                    }
                } elseif (!empty($part)) {
                    $guruMapelMap[$part][] = 'Bimbingan Belajar';
                }
            }
        }

        $result = [];
        foreach ($guruMapelMap as $guruName => $mapels) {
            $guruUser = \App\Models\User::where('name', $guruName)->where('role', 'guru')->first();
            if ($guruUser) {
                $result[] = [
                    'guru_id'    => $guruUser->id,
                    'guru_name'  => $guruUser->name,
                    'mapels'     => array_values(array_unique($mapels)),
                    'session_id' => 'sg_' . $siswa->id . '_' . $guruUser->id,
                ];
            }
        }

        return $result;
    }

    /**
     * Ambil daftar siswa aktif yang diajar oleh guru ini (sama seperti logika di GuruController).
     */
    private function getGuruAssignedSiswa($guruUser)
    {
        $guruNameNorm = strtolower(trim($guruUser->name));

        return \App\Models\Siswa::where('status', 'active')
            ->get()
            ->filter(function ($siswa) use ($guruNameNorm) {
                $biodata = $siswa->biodata ?? [];

                $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];
                if (is_array($tutorPerMapel) && !empty($tutorPerMapel)) {
                    foreach ($tutorPerMapel as $tName) {
                        if (strtolower(trim($tName)) === $guruNameNorm) {
                            return true;
                        }
                    }
                }

                $tutorNames = $biodata['tutor_names'] ?? [];
                if (is_array($tutorNames) && !empty($tutorNames)) {
                    foreach ($tutorNames as $tName) {
                        if (strtolower(trim($tName)) === $guruNameNorm) {
                            return true;
                        }
                    }
                }

                if ($siswa->tipe_paket && str_contains(strtolower($siswa->tipe_paket), $guruNameNorm)) {
                    return true;
                }

                return false;
            })
            ->values();
    }
}