<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:tbl_user,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'user_name' => $data['username'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => 'operation',
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $this->formatUser($user),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['អ៊ីមែល ឬ ពាក្យសម្ងាត់មិនត្រឹមត្រូវ'],
            ]);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $this->formatUser($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }

    public function me(Request $request)
    {
        return response()->json($this->formatUser($request->user()));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'username' => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:tbl_user,email,' . $user->user_id . ',user_id',
            'phone'    => 'sometimes|nullable|string|max:20',
        ]);

        $update = [];
        if (isset($data['username'])) $update['user_name'] = $data['username'];
        if (isset($data['email']))    $update['email']     = $data['email'];
        if (isset($data['phone']))    $update['phone']     = $data['phone'];

        $user->update($update);

        return response()->json($this->formatUser($user->fresh()));
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6',
        ]);

        $user = $request->user();

        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវ'],
            ]);
        }

        $user->update(['password' => Hash::make($data['new_password'])]);

        return response()->json(['message' => 'Password changed'], 200);
    }

    private function formatUser(User $user): array
    {
        return [
            'id'              => $user->user_id,
            'username'        => $user->user_name,
            'email'           => $user->email,
            'role'            => $user->role,
            'phone'           => $user->phone,
            'profile_picture' => $user->profile_picture,
        ];
    }
}