<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function rotateImage(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'angle' => 'required|integer',
        ]);

        // Get the uploaded image
        $image = $request->file('image');

        // Rotate the image using Intervention Image
        $rotatedImage = Image::make($image->getRealPath())
            ->rotate($request->input('angle'))
            ->encode('jpg')
            ->getEncoded();

        // Store the rotated image
        $imageName = time() . '.' . 'jpg';
        Storage::disk('img')->put($imageName, $rotatedImage);

        // Return the rotated image URL
        return response()->json(['url' => asset('img/' . $imageName)]);
    }

    public function getImage($imageName)
    {
        // Check if the image exists
        if (!Storage::disk('img')->exists($imageName)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Return the image
        return response()->file(public_path('img/' . $imageName));
    }
}