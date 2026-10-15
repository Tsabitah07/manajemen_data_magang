<?php

namespace App\Http\Controllers;

use App\Http\Requests\internship\storeRequest;
use App\Http\Requests\internship\UpdateRequest;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternshipController extends Controller
{
    public function index(Request $request)
    {
        $request->validated();

        if ($request->query('id') !== null) {
            $internship = Internship::find($request->query('id'));
            if ($internship) {
                return response()->json([
                    'message' => 'Internship retrieved successfully',
                    'data' => $internship
                ], 200);
            } else {
                return response()->json(['message' => 'Internship not found'], 404);
            }
        } elseif ($request->query('campus_unit_id') !== null) {
            $internships = Internship::where('campus_unit_id', $request->query('campus_unit_id'))->get();
            if ($internships->isEmpty()) {
                return response()->json(['message' => 'No internships found for the specified campus unit'], 404);
            } else {
                return response()->json([
                    'message' => 'Internships retrieved successfully',
                    'data' => $internships
                ], 200);
            }
        } else {
            $internships = Internship::all();
            return response()->json([
                'message' => 'Internships retrieved successfully',
                'data' => $internships
            ], 200);
        }
    }

    public function store(storeRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->storeAs('images', $file->hashName(), 'public');
            $imageUrl = Storage::url($path);
        } else {
            $imageUrl = null;
        }

        $validatedData['documentation'] = $imageUrl;
        $internship = Internship::create($validatedData);

        return response()->json([
            'message' => 'Internship created successfully',
            'data' => $internship
        ], 201);
    }

    public function update(UpdateRequest $request)
    {
        $validatedData = $request->validated();
        $internship = Internship::find($request->query('id'));

        if (!$internship) {
            return response()->json(['message' => 'Internship not found'], 404);
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->storeAs('images', $file->hashName(), 'public');
            $imageUrl = Storage::url($path);
            $validatedData['documentation'] = $imageUrl;
        } else {
            $imageUrl = $internship->documentation;
            $validatedData['documentation'] = $imageUrl;
        }

        $internship->update([
            'title' => $validatedData['title'] ?? $internship->title,
            'description' => $validatedData['description'] ?? $internship->description,
            'start_date' => $validatedData['start_date'] ?? $internship->start_date,
            'end_date' => $validatedData['end_date'] ?? $internship->end_date,
            'quota' => $validatedData['quota'] ?? $internship->quota,
            'documentation' => $validatedData['documentation'] ?? $internship->documentation,
            'campus_unit_id' => $validatedData['campus_unit_id'] ?? $internship->campus_unit_id,
            'status' => $validatedData['status'] ?? $internship->status
        ]);

        return response()->json([
            'message' => 'Internship updated successfully',
            'data' => $internship
        ], 200);
    }

    public function destroy(Request $request)
    {
        $internship = Internship::find($request->query('id'));

        if (!$internship) {
            return response()->json(['message' => 'Internship not found'], 404);
        }

        $internship->delete();

        return response()->json([
            'message' => 'Internship deleted successfully'
        ], 200);
    }
}
