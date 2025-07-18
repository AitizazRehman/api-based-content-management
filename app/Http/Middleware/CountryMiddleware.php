<?php

namespace App\Http\Middleware;

use App\Services\GeoIPService;
use Closure;

class CountryMiddleware
{

    public function handle($request, Closure $next)
    {
        $geoip = app(GeoIPService::class);
        $countryCode = $request->input('country') ?? $geoip->getCountryCode($request);

        $request->attributes->add([
            'country_code' => strtoupper($countryCode)
        ]);

        return $next($request);
    }
}
