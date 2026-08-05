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
        ]);

        $chat = Chat::create([
            'session_id' => $request->session_id,
            'sender_name' => $request->sender_name, // e.g. "Anonymous"
            'sender_role' => 'visitor',
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

        // Group chats by session_id and find the latest message for each session
        $sessions = Chat::select('session_id', 'sender_name')
            ->selectRaw('MAX(created_at) as last_activity')
            ->selectRaw('SUM(CASE WHEN sender_role = "visitor" AND is_read = 0 THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('session_id', 'sender_name')
            ->orderBy('last_activity', 'desc')
            ->get();

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
            ->update(['is_read' => true]);

        $messages = Chat::where('session_id', $session_id)
            ->orderBy('created_at', 'asc')
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
            'is_read' => true, // admin message is read by default
        ]);

        return response()->json($chat);
    }
}
