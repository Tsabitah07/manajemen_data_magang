<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\CampusUnit;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function getAll()
    {
        $users = User::all();

        return response()->json([
            'status' => 'Success get all users',
            'data' => $users,
        ]);
    }

    public function get($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => 'User not found',
            ], 404);
        }

        $student = Student::where('user_id', $id)->first();
        $campusUnit = CampusUnit::where('user_id', $id)->first();

        if ($student) {
            $additionalData = [
                'gender' => $student->gender,
                'nim' => $student->nim,
                'major' => $student->major,
                'status' => $student->status,
                'enrollment_year' => $student->enrollment_year,
            ];
        } elseif ($campusUnit) {
            $additionalData = [
                'unit_name' => $campusUnit->unit_name,
                'position' => $campusUnit->position,
            ];
        }

        $user->additional_data = $additionalData ?? null;

        return response()->json([
            'status' => 'Success get user',
            'data' => $user,
        ]);
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
}
