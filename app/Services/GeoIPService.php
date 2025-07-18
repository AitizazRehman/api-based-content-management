<?php

namespace App\Services;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeoIPService
{
    protected $reader;

    public function __construct()
    {
        $databasePath = storage_path('app/geoip/GeoLite2-Country.mmdb');
        
        if (!file_exists($databasePath)) {
            throw new \RuntimeException('GeoIP database not found at: '.$databasePath);
        }

        $this->reader = new Reader($databasePath);
    }

    public function getCountryCode(Request $request): string
    {
        try {
            $ip = $request->ip();
            if (in_array($ip, ['127.0.0.1', '::1'])) {
                return config('app.default_country', 'PK');
            }
            $record = $this->reader->country($ip);
            return $record->country->isoCode ?? config('app.default_country', 'PK');
            
        } catch (\Exception $e) {
            Log::error('GeoIP Error for IP '.$ip.': '.$e->getMessage());
            return config('app.default_country', 'PK');
        }
    }
}