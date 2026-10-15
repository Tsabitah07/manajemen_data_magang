<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Requests\auth\UpdateRequest;
use App\Models\CampusUnit;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $users = User::all();
        } elseif ($user->role === 'campus-unit') {
            $users = User::where('role', 'student')->get();
        } elseif ($request->query('id')) {
            $user = User::find($request->query('id'));
            if ($user) {
                return response()->json([
                    'status' => 'Success get user by id',
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => 'User not found',
                ], 404);
            }
        }
        else {
            return response()->json([
                'status' => 'Unauthorized',
                'message' => 'You are not authorized to access this resource.',
            ], 403);
        }

        return response()->json([
            'status' => 'Success get all users',
            'data' => $users,
        ]);
    }

    public function store(RegisterRequest $request)
    {
        $request->validated();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->storeAs('images', $file->hashName(), 'public');
            $imageUrl = Storage::url($path);
        } else {
            $imageUrl = null;
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => 'Email already exists',
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'profile_picture' => $imageUrl,
            'role' => $request->role ?? 'student',
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function register(RegisterRequest $request)
    {
        $request->validated();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->storeAs('images', $file->hashName(), 'public');
            $imageUrl = Storage::url($path);
        } else {
            $imageUrl = null;
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => 'Email already exists',
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'profile_picture' => $imageUrl,
            'role' => $request->role ?? 'student',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'Success register user',
            'token' => $token,
            'data' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! \Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'Invalid email or password',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'Success login user',
            'token' => $token,
            'data' => $user,
        ]);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function update(UpdateRequest $request)
    {
        $request->validated();
        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->storeAs('images', $file->hashName(), 'public');
            $imageUrl = Storage::url($path);
        }

        if ($request->query('id') && $user->role == 'admin'){
            $detail = User::find($request->query('id'));

            $detail->update([
                'name' => $request->name ?? $detail->name,
                'phone' => $request->phone ?? $detail->phone,
                'email' => $request->email ?? $detail->email,
                'password' => $request->password ?? $detail->password,
                'profile_picture' => $imageUrl ?? $detail->profile_picture,
                'role' => $request->role ?? $detail->role,
            ]);

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $detail
            ]);
        } else {
            $user->update([
                'name' => $request->name ?? $user->name,
                'phone' => $request->phone ?? $user->phone,
                'email' => $request->email ?? $user->email,
                'password' => $request->password ?? $user->password,
                'profile_picture' => $imageUrl ?? $user->profile_picture,
                'role' => $request->role ?? $user->role,
            ]);

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'Success logout user',
        ]);
    }
}
