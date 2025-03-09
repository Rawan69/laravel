<?php
namespace App\Http\Controllers;

use App\Models\SoilMoisture;
use Illuminate\Http\Request;

class SoilMoistureController extends Controller
{
    // Get all soil moisture records
    public function index()
    {
        return response()->json(SoilMoisture::all());
    }

    // Store new soil moisture data
    public function store(Request $request)
    {
        $request->validate([
            'is_moist' => 'required|boolean'
        ]);

        $moisture = SoilMoisture::create([
            'is_moist' => $request->is_moist
        ]);

        return response()->json([
            'message' => 'Soil moisture status recorded successfully',
            'data' => $moisture
        ], 201);
    }

    // Get single soil moisture record
    public function show($id)
    {
        $moisture = SoilMoisture::find($id);
        if (!$moisture) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        return response()->json($moisture);
    }

    // Delete a soil moisture record
    public function destroy($id)
    {
        $moisture = SoilMoisture::find($id);
        if (!$moisture) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $moisture->delete();
        return response()->json(['message' => 'Record deleted successfully']);
    }
}

