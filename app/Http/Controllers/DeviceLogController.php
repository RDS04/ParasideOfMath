<?php

namespace App\Http\Controllers;

use App\Models\DeviceLog;
use Illuminate\Http\Request;

class DeviceLogController extends Controller
{
    /**
     * Store a new visitor device log into database (Public Endpoint).
     */
    public function store(Request $request)
    {
        try {
            $clientIp = $request->header('X-Forwarded-For') ?? $request->ip();

            $log = DeviceLog::create([
                'log_code' => 'DEV-' . time() . '-' . rand(100, 999),
                'device_type' => $request->input('deviceType', 'Mobile (HP)'),
                'brand_model' => $request->input('brandModel', 'Perangkat HP / Komputer'),
                'browser' => $request->input('browser', 'Web Browser'),
                'platform' => $request->input('platform', 'N/A'),
                'user_agent' => $request->input('userAgent') ?? $request->header('User-Agent'),
                'screen' => $request->input('screen', 'N/A'),
                'viewport' => $request->input('viewport', 'N/A'),
                'dpr' => $request->input('dpr', 1),
                'language' => $request->input('language', 'id-ID'),
                'online_status' => $request->input('onlineStatus', 'Online'),
                'page' => $request->input('page', 'Landing Page (/informasi)'),
                'ip' => $request->input('ip') ?: $clientIp,
                'city' => $request->input('city'),
                'region' => $request->input('region'),
                'country' => $request->input('country', 'Indonesia'),
                'org' => $request->input('org'),
                'lat' => $request->input('lat'),
                'lng' => $request->input('lng'),
                'maps_url' => $request->input('mapsUrl'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log perangkat berhasil disimpan ke server database.',
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get JSON list of device logs for Master Dashboard.
     */
    public function getLogs()
    {
        $logs = DeviceLog::latest()->take(100)->get();
        return response()->json($logs);
    }

    /**
     * Delete a single device log.
     */
    public function destroy($id)
    {
        $log = DeviceLog::find($id);
        if ($log) {
            $log->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Clear all device logs.
     */
    public function clearAll()
    {
        DeviceLog::truncate();
        return response()->json(['success' => true]);
    }
}
