<?php

namespace App\Http\Controllers;

use App\Http\Requests\internApplication\storeRequest;
use App\Models\Internship;
use App\Models\InternshipApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternshipApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $authUser = auth()->user();

        if ($request->query('id') !== null) {
            $application = InternshipApplication::find($request->query('id'));

            if ($application === null) {
                return response()->json(['message' => 'Internship application not found'], 404);
            }

            return response()->json([
                'message' => 'Internship application retrieved successfully',
                'data' => $application,
            ], 200);
        }

        if ($request->query('student_id', $authUser->id)) {
            $application = InternshipApplication::where('student_id', $request->query('student_id'))->get();

            if ($application === null) {
                return response()->json(['message' => 'No Internship Application']);
            }

            return response()->json([
                'message' => 'Student internship application retrieved successfully',
                'data' => $application
            ], 200);
        }

        $internships = Internship::query()
            ->when($request->query('internship_id') !== null, function ($query) use ($request) {
                $query->where('id', $request->query('internship_id'));
            })
            ->get();

        $applicationsByInternship = InternshipApplication::query()
            ->whereIn('internship_id', $internships->pluck('id'))
            ->when($request->query('student_id') !== null, function ($query) use ($request) {
                $query->where('student_id', $request->query('student_id'));
            })
            ->get()
            ->groupBy('internship_id');

        $data = $internships->map(function (Internship $internship) use ($applicationsByInternship) {
            return array_merge($internship->toArray(), [
                'intern_application' => $applicationsByInternship
                    ->get($internship->id, collect())
                    ->values(),
            ]);
        });

        return response()->json([
            'message' => 'Internships and applications retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function store(storeRequest $request)
    {
        $request->validated();

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $path = $file->storeAs('images', $file->hashName(), 'public');
            $imageUrl = Storage::url($path);
        } else {
            $imageUrl = null;
        }

        $validatedData['cv'] = $imageUrl;
        $internship = Internship::create($validatedData);

        return response()->json([
            'message' => 'Intern application successfully submitted',
        ]);
    }

    public function update(Request $request)
    {
        $request->validated();
        $intern = InternshipApplication::find($request->query('id'));

        if (!$intern) {
            return response()->json(['message' => 'Internship application not found'], 404);
        }

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $path = $file->storeAs('images', $file->hashName(), 'public');
            $imageUrl = Storage::url($path);
        } else {
            $imageUrl = $intern->cv;
        }

        $intern->update([
            'internship_id' => $intern->internship_id,
            'student_id' => $intern->student_id,
            'cv' => $imageUrl,
            'cv_required' => $request->cv_required ?? $intern->cv_required,
            'status' => $request->status ?? $intern->status,
        ]);

        return response()->json([
            'message' => 'Intern application successfully updated'
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validated();

        $intern = InternshipApplication::find($request->query('id'));

        return response()->json(['message' => 'Application deleted successfully']);
    }
}
