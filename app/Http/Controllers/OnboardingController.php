<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Mark onboarding as completed for current authenticated user/siswa.
     */
    public function complete(Request $request)
    {
        $user = Auth::guard('siswa')->user() ?? Auth::user();

        if ($user) {
            $user->has_seen_onboarding = true;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Panduan onboarding telah diselesaikan.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pengguna tidak ditemukan.'
        ], 401);
    }

    /**
     * Reset onboarding state so user can re-watch the tour.
     */
    public function reset(Request $request)
    {
        $user = Auth::guard('siswa')->user() ?? Auth::user();

        if ($user) {
            $user->has_seen_onboarding = false;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Panduan onboarding telah di-reset.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pengguna tidak ditemukan.'
        ], 401);
    }
}
