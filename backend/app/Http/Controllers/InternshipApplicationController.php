<?php

namespace App\Http\Controllers;

use App\Http\Requests\internApplication\storeRequest;
use App\Models\Internship;
use App\Models\InternshipApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InternshipApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = InternshipApplication::query()->with(['internship', 'student.studentProfile']);

        if ($user->role === 'student') {
            $query->where('student_id', $user->id);
        } elseif ($user->role === 'campus-unit') {
            $query->whereHas('internship', function ($internshipQuery) use ($user) {
                $internshipQuery->where('campus_unit_id', $user->id);
            });
        }

        if ($request->query('id') !== null) {
            $query->whereKey($request->query('id'));
        }

        if ($request->query('internship_id') !== null) {
            $query->where('internship_id', $request->query('internship_id'));
        }

        $applications = $query->latest()->get();
        if ($request->query('id') !== null && $applications->isEmpty()) {
            return response()->json(['message' => 'Internship application not found'], 404);
        }

        return response()->json([
            'message' => 'Internship applications retrieved successfully',
            'data' => $applications,
        ]);
    }

    public function store(storeRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $internship = Internship::findOrFail($validatedData['internship_id']);
        $request->validate([
            'cv' => [
                $internship->cv_required ? 'required' : 'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('cvs', 'public');
            $validatedData['cv'] = Storage::url($path);
        }

        if (! $internship->is_open) {
            return response()->json(['message' => 'Pendaftaran untuk lowongan ini sudah ditutup.'], 422);
        }

        $student = $request->user();
        $alreadyApplied = InternshipApplication::query()
            ->where('internship_id', $internship->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json(['message' => 'Kamu sudah mengajukan lamaran untuk lowongan ini.'], 422);
        }

        $application = InternshipApplication::create([
            'internship_id' => $internship->id,
            'student_id' => $student->id,
            'cv' => $validatedData['cv'] ?? null,
            'status' => 'waiting',
        ])->load(['internship', 'student']);

        return response()->json([
            'message' => 'Intern application successfully submitted',
            'data' => $application,
        ], 201);
    }

    public function update(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'id' => ['required', 'integer'],
            'status' => [
                'required',
                Rule::in($request->user()->role === 'campus-unit'
                    ? ['accepted', 'rejected', 'cancelled']
                    : ['cancelled']),
            ],
        ]);

        $query = InternshipApplication::query()->whereKey($validatedData['id']);
        if ($request->user()->role === 'student') {
            $query->where('student_id', $request->user()->id)->where('status', 'waiting');
        } else {
            $query->whereHas('internship', function ($internshipQuery) use ($request) {
                $internshipQuery->where('campus_unit_id', $request->user()->id);
            });
        }

        $application = $query->first();
        if (! $application) {
            return response()->json(['message' => 'Internship application not found'], 404);
        }

        $application->update(['status' => $validatedData['status']]);

        return response()->json([
            'message' => 'Intern application successfully updated',
            'data' => $application->load(['internship', 'student']),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validatedData = $request->validate(['id' => ['required', 'integer']]);
        $application = InternshipApplication::query()
            ->whereKey($validatedData['id'])
            ->where('student_id', $request->user()->id)
            ->where('status', 'waiting')
            ->first();

        if (! $application) {
            return response()->json(['message' => 'Internship application not found'], 404);
        }

        $application->delete();

        return response()->json(['message' => 'Application deleted successfully']);
    }
}
