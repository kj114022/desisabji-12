<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\API\APIController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthController extends APIController
{
    public function authToken(Request $request)
    {
         Log::debug('Api Auth token function :: ' . json_encode($request->all()));
         $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        Log::debug('Api Auth token user :: ' . json_encode($user));
        // Check if user exists and password matches
         Log::debug('Api Auth token password :: ' . $request->password);
         Log::debug('Api Auth token user password :: ' . ($user ? $user->password : 'No user found'));        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->respondWithError('Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        Log::debug('Api Auth token :: ' . $token);
        return $this->respondWithSuccess(['token' => $token],'Authentication successful', 200);
    }
}
