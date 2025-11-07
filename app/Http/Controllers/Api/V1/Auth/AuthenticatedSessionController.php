<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    use ApiResponses;

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error('Invalid credentials.', null, 401);
        }

        if (! $user->is_active) {
            return $this->error('Account is inactive.', null, 403);
        }

        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('API Token for '.$user->email)->plainTextToken;

        /** @var User $user */
        $user = User::firstWhere('email', $request->email);

        return $this->success([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful.',
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }
}
