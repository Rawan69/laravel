<?php

namespace App\Http\Controllers;

use App\Models\Temp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class TempController extends Controller
{


    public function store(Request $request)
    {
        $request->validate
        ([
   'image'=>'required|image|mimes:jpg,png,jpeg|max:2048',
   
        ]);
        $path = $request->file('image')->store('temp', 'public');
        $temp = Temp::create(['image_path' => $path]);
        return response()->json([
            'message' => 'Image uploaded successfully, hiiiiiiiii*_*',
            'image_url' => asset("storage/{$path}"),
            'info'=> 'projectt',
          
        ], 201);
    }

    public function index()
{
    $images = Temp::all();

    return response()->json([
        'images' => $images->map(function ($image) {
            return [
                'id' => $image->id,
                'image_url' => asset("storage/{$image->image_path}"),
                'created_at' => $image->created_at,
            ];
        })
    ], 200);
}


// public function store(Request $request)
//     {
//         if (!Auth::check()) {
//             return response()->json(['error' => 'Login first'], 401);
//         }

//         $request->validate([
//             'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//             'image_name' => 'required|string|max:255'
//         ]);

//         $userId = Auth::id();
//         $storagePath = 'storage/temp_images';
//         $fullPath = public_path($storagePath);

      
//         if (!file_exists($fullPath)) {
//             mkdir($fullPath, 0777, true);
//         }

//         $imageName = $request->image_name . '.' . $request->image->extension();
//         $imagePath = $storagePath . '/' . $imageName;

    
//         $request->image->move($fullPath, $imageName);

       
//         Temp::updateOrCreate(
//             ['user_id' => $userId, 'image_path' => $imagePath],
//             ['image_path' => $imagePath]
//         );

//         return response()->json([
//             'message' => 'Upload successful',
//             'image_url' => asset($imagePath)
//         ]);
//     }

//     public function getUserImages()
//     {
//         $userId = Auth::id();
//         $images = Temp::where('user_id', $userId)->get(['id', 'image_path']);

//         $formattedImages = $images->map(function ($image) {
//             return [
//                 'id' => $image->id,
//                 'image_url' => asset($image->image_path),
//             ];
//         });

//         return response()->json(['images' => $formattedImages]);
//     }

//     public function getImage($filename)
//     {
//         $userId = Auth::id();
//         $image = Temp::where('image_path', "storage/temp_images/{$filename}")->first();

//         if (!$image) {
//             return response()->json(['error' => 'You cannot access this'], 403);
//         }

//         $filePath = public_path($image->image_path);

//         if (!file_exists($filePath)) {
//             return response()->json(['error' => 'File not found'], 404);
//         }

//         return response()->file($filePath);
//     }




//     public function deleteImage($filename)
//     {
//         $userId = Auth::id();
//         $image = Temp::where('user_id', $userId)
//                      ->where('image_path', 'like', "%{$filename}")
//                      ->first();

//         if (!$image) {
//             return response()->json(['error' => 'You cannot access this'], 403);
//         }

//         $filePath = public_path($image->image_path);

//         if (file_exists($filePath)) {
//             unlink($filePath); 
//         }

//         $image->delete(); 

//         return response()->json(['message' => 'Image deleted successfully']);
//     }

}
