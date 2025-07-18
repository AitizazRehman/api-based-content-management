<?php

namespace App\Http\Controllers;

use App\Models\FcmMessage;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmController extends Controller
{
    public function webhook(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'device_token' => 'nullable|string', // For single device
            'topic' => 'nullable|string',       // For topics
            'data' => 'nullable|array'
        ]);

        $messaging = app('firebase.messaging');

        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create(
                    $request->input('title', 'New Notification'),
                    $validated['message']
                ))
                ->withData($validated['data'] ?? []);

            // Send to specific device or topic
            if (!empty($validated['device_token'])) {
                $messaging->send($message, $validated['device_token']);
            } elseif (!empty($validated['topic'])) {
                $messaging->sendToTopic($validated['topic'], $message);
            } else {
                // Handle broadcast logic here
            }
            FcmMessage::create([
                'message' => $validated['message'],
                'device_token' => $validated['device_token'] ?? null,
                'topic' => $validated['topic'] ?? null,
                'data' => $validated['data'] ?? null,
                'sent_at' => now()
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
