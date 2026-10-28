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
        $query = Internship::query()->with('campusUnit');

        if ($request->query('id') !== null) {
            $query->whereKey($request->query('id'));
        }

        if ($request->query('campus_unit_id') !== null) {
            $query->where('campus_unit_id', $request->query('campus_unit_id'));
        }

        return response()->json([
            'message' => 'Internships retrieved successfully',
            'data' => $query->latest()->get(),
        ]);
    }

    public function store(storeRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('documentation')) {
            $path = $request->file('documentation')->store('internships', 'public');
            $validatedData['documentation'] = Storage::url($path);
        }

        $validatedData['campus_unit_id'] = $request->user()->id;
        $validatedData['is_open'] = $validatedData['is_open'] ?? true;
        $internship = Internship::create($validatedData);

        return response()->json([
            'message' => 'Internship created successfully',
            'data' => $internship,
        ], 201);
    }

    public function update(UpdateRequest $request)
    {
        $validatedData = $request->validated();
        $internship = Internship::query()
            ->whereKey($request->query('id'))
            ->where('campus_unit_id', $request->user()->id)
            ->first();

        if (! $internship) {
            return response()->json(['message' => 'Internship not found'], 404);
        }

        if (! $internship->is_open && array_diff(array_keys($validatedData), ['is_open']) !== []) {
            return response()->json(['message' => 'Internship not found'], 404);
        }

        if ($request->hasFile('documentation')) {
            $path = $request->file('documentation')->store('internships', 'public');
            $validatedData['documentation'] = Storage::url($path);
        }

        $internship->update($validatedData);

        return response()->json([
            'message' => 'Internship updated successfully',
            'data' => $internship,
        ], 200);
    }

    public function destroy(Request $request)
    {
        $internship = Internship::query()
            ->whereKey($request->query('id'))
            ->where('campus_unit_id', $request->user()->id)
            ->first();

        if (! $internship) {
            return response()->json(['message' => 'Internship not found'], 404);
        }

        $internship->delete();

        return response()->json([
            'message' => 'Internship deleted successfully',
        ], 200);
    }
}
