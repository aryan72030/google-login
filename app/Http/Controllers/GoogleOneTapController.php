<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use App\Models\User;

class GoogleOneTapController extends Controller
{
    public function handleGoogleSignIn(Request $request)
    {
        $idToken = $request->input('id_token');

        if (!$idToken) {
            return response()->json(['error' => 'No ID token provided'], 400);
        }

        
        $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);

        try {
            $payload = $client->verifyIdToken($idToken);

            if ($payload && $payload['aud'] === env('GOOGLE_CLIENT_ID')) {
                // Insert only google_id, name, and email
                $user = User::updateOrCreate(


                    ['google_id' => $payload['sub']],
                    [
                        'name' => $payload['name'],
                        'email' => $payload['email'],
                    ]
                );

                // Store in session
                session([
                    'user' => [
                        'id' => $user->id,
                        'google_id' => $user->google_id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                ]);

                return response()->json(['message' => 'Login successful']);
            } else {
                return response()->json(['error' => 'Invalid ID token'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token verification failed: ' . $e->getMessage()], 500);
        }
    }
}
