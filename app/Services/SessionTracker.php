<?php

namespace App\Services;

use App\Models\UserSession;

class SessionTracker
{
    public function startSession($userId = null, $countryCode)
    {

        UserSession::firstOrCreate(
            ['user_id' => $userId],
            [
                'session_id' => session()->getId(),
                'user_id' => $userId,
                'country_code' => $countryCode,
                'login_time' => now()
            ]
        );
    }

    public function endSession($id)
    {
        $session = UserSession::where('user_id', $id)->first();
        $session->update([
            'logout_time' => now()
        ]);
        return $session;
    }
}
