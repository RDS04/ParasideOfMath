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
            $rawIp = $request->header('CF-Connecting-IP')
                ?? $request->header('X-Forwarded-For')
                ?? $request->header('X-Real-IP')
                ?? $request->ip();

            if (str_contains($rawIp, ',')) {
                $rawIp = trim(explode(',', $rawIp)[0]);
            }

            $ip = $request->input('ip') ?: $rawIp;
            $city = $request->input('city');
            $region = $request->input('region');
            $country = $request->input('country', 'Indonesia');
            $org = $request->input('org');
            $lat = $request->input('lat');
            $lng = $request->input('lng');
            $mapsUrl = $request->input('mapsUrl');

            // Automatic Server-Side Geolocation jika frontend di hosting belum selesai mengambil IP
            if (empty($city) && $rawIp && $rawIp !== '127.0.0.1' && $rawIp !== '::1') {
                try {
                    $res = @file_get_contents("https://ipwhois.app/json/{$rawIp}");
                    if ($res) {
                        $ipData = json_decode($res, true);
                        if (is_array($ipData) && !empty($ipData['latitude'])) {
                            $city = $ipData['city'] ?? $city;
                            $region = $ipData['region'] ?? $region;
                            $country = $ipData['country'] ?? $country;
                            $org = $ipData['org'] ?? $org;
                            $lat = $ipData['latitude'] ?? $lat;
                            $lng = $ipData['longitude'] ?? $lng;
                            if ($lat && $lng) {
                                $mapsUrl = "https://www.google.com/maps?q={$lat},{$lng}";
                            }
                        }
                    }
                } catch (\Throwable $e) {}
            }
            }

            $logCode = $request->input('logCode') ?: ($request->input('id') ?: ('DEV-' . time() . '-' . rand(100, 999)));

            $log = DeviceLog::updateOrCreate(
                ['log_code' => $logCode],
                [
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
                    'ip' => $ip,
                    'city' => $city,
                    'region' => $region,
                    'country' => $country,
                    'org' => $org,
                    'lat' => $lat,
                    'lng' => $lng,
                    'maps_url' => $mapsUrl,
                ]
            );

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
