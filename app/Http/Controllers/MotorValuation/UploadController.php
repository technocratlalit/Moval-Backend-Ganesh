<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\BranchContactPerson;
use App\Models\ClientBranch;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UploadController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function upload(Request $request)
    {
        $fields = $request->validate([
            'file' => 'required',
            'is_document' => 'required|in:0,1', // Assuming is_document is part of the request
            'upload_type' => 'required'
        ]);
        $isDocument = $fields['is_document'];
        $compression_level = 30;
        if ($isDocument == 0) {
            // Process and save image logic
            $img = $fields['file'];
            // Remove the data URL prefix
            $img = preg_replace('#^data:image/[^;]+;base64,#', '', $img);
            $png_url = "temp_" . time() . ".jpg";
            $path = public_path() . "/temp/" . $png_url;
            $data = base64_decode($img);
            $success = false;
            $image_create = imagecreatefromstring($data);
            if($image_create !== false) {
                if (imagejpeg($image_create, $path, $compression_level)) {
                    $success = true;
                }
            }
//            $success = file_put_contents($path, $data);
            if ($success && $fields['upload_type'] == 'motor_survey_link') {
                // Open the image using Intervention Image
                $image = Image::make($path)->encode('jpg');
                $width = 500; // Set your desired width
                $height = null; // Set to null to maintain aspect ratio
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio(); // Maintain aspect ratio
                });

                // Calculate the y-coordinate for text placement
                $yCoordinate = $image->height() - 20;
                // Add the date watermark to the right side bottom of the image
                $image->text(now()->format('d-m-Y h:i A'), $image->width() - 10, $yCoordinate, function ($font) {
                    $font->file(public_path('/Mukta/Mukta-Regular.ttf')); // Specify the font file
                    $font->size(15); // Specify the font size
                    $font->color('#F47206'); // Specify the font color
                    $font->align('right'); // Specify the text alignment
                    $font->valign('bottom'); // Specify the vertical alignment
                });

                if (!empty($request->location)) {
                    $latitude = $request->location["lat"]; // Replace with your actual latitude
                    $longitude = $request->location["long"]; // Replace with your actual longitude

                    // Calculate the y-coordinate for latitude and longitude text placement
                    $yCoordinateLatLong = $yCoordinate - 40; // Adjust as needed

                    // Add the latitude watermark above the date and time watermark
                    $image->text("Lat : $latitude", $image->width() - 10, $yCoordinateLatLong, function ($font) {
                        $font->file(public_path('/Mukta/Mukta-Regular.ttf'));
                        $font->size(15);
                        $font->color('#F47206');
                        $font->align('right');
                        $font->valign('bottom');
                    });

                    // Add the longitude watermark above the date and time watermark
                    $image->text("Long : $longitude", $image->width() - 10, $yCoordinateLatLong - 20, function ($font) {
                        $font->file(public_path('/Mukta/Mukta-Regular.ttf'));
                        $font->size(15);
                        $font->color('#F47206');
                        $font->align('right');
                        $font->valign('bottom');
                    });
                }
                // Save the modified image
                $image->save();
                // Free up memory
                $image->destroy();
            }
            // Construct the URL to access the image
            $file_pathtemp = asset("public/temp/".$png_url);
            // Return the URL to the modified image or an error code
            $result = $success ? $file_pathtemp : '0';
            $file_name = $success ? $png_url : null;
        } elseif ($isDocument == 1) {
            // Handle document upload logic here
            $img = $fields['file'];
            $img = preg_replace('#^data:image/[^;]+;base64,#', '', $img);
            $png_url = "temp_" . time() . ".jpg";
            $path = public_path() . "/temp/" . $png_url;
            $data = base64_decode($img);
            $success = false;
            $image_create = imagecreatefromstring($data);
            if($image_create !== false) {
                if (imagejpeg($image_create, $path, $compression_level)) {
                    $success = true;
                }
            }
//            $success = file_put_contents($path, $data);
            $result = $success ? $png_url : '0';
            $file_pathtemp = url("public/temp/" . $png_url);
            $file_name = $success ? $png_url : null;
        } else {
            // Invalid value for is_document, handle the error accordingly
            $result = 'Invalid value for is_document';
            $file_name = null;
            $file_pathtemp = null;
        }

        if (!isset($result)) {
            return $this->sendError('Failed to Upload.', ['error' => 'Failed to Upload.'], 404);
        } else {
            return $this->sendResponse('Uploaded.', ['file_name' => $file_name, 'path' => $file_pathtemp], 200);
        }

    }


    public function mobileAppUpload(Request $request)
    {
        $compression_level = 30;
        $fields = $request->validate([
            'file' => 'required',
        ]);
        $img = $fields['file'];
        $img = preg_replace('#^data:image/[^;]+;base64,#', '', $img);
        //$data = Input::all();
        $png_url = "temp_" . time() . ".jpg";
        $path = public_path() . "/temp/" . $png_url;
        //  $img = substr($img, strpos($img, ",") + 1);
        $data = base64_decode($img);
        $success = false;
        $image_create = imagecreatefromstring($data);
        if($image_create !== false) {
            if (imagejpeg($image_create, $path, $compression_level)) {
                $success = true;
            }
        }
//        $success = file_put_contents($path, $data);
        $result = $success ? $png_url : '0';

        $file_pathtemp = url("public/temp/" . $png_url);

        //print_r($result); die;

        if (!isset($result)) {
            return $this->sendError('Failed to Upload.', ['error' => 'Failed to Upload.'], 401);
        } else {
            return $this->sendResponse('Uploaded.', ['file_name' => $result, 'path' => $file_pathtemp], 200);
        }
    }
}
