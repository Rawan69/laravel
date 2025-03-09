<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // Get all activities
    public function index()
    {
        return response()->json(Activity::with('user')->get());
    }

    // Store new activity
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'is_watered' => 'required|boolean'
        ]);

        $activity = Activity::create([
            'user_id' => $request->user_id,
            'is_watered' => $request->is_watered
        ]);

        return response()->json([
            'message' => 'Activity recorded successfully',
            'data' => $activity
        ], 201);
    }

    // Get single activity
    public function show($id)
    {
        $activity = Activity::with('user')->find($id);
        if (!$activity) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        return response()->json($activity);
    }

    // Delete an activity
    public function destroy($id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $activity->delete();
        return response()->json(['message' => 'Record deleted successfully']);
    }
}

