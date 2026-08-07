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
        if (($senderName === 'Anonymous' || empty($senderName)) && Auth::check()) {
            $senderName = Auth::user()->name;
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

        // Fetch registered user roles into a fast memory map
        $userRoles = \App\Models\User::pluck('role', 'name')->toArray();

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
}
