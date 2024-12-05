<?php

namespace App\Traits;

trait AdminUploadTrait
{
    public function storeImage($file, $storagePath, $allowedExtensions = ['jpg', 'jpeg', 'png'])
    {
        // Check if the file is present in the request
        if (!$file) {
            return response()->json(["success" => false, "message" => "File not found."]);
        }

        // Check if the file has a valid extension
        $check = $file->getClientOriginalExtension();
        if (in_array(strtolower($check), $allowedExtensions) && $file->isValid()) {
//            $var = date_create();
//            $time = date_format($var, 'YmdHis');
//            $imageName = str_replace(" ", "-",($time . '-' . $file->getClientOriginalName()));
//            $path = $file->storeAs($storagePath, $imageName, 'public');
//            return $storagePath . '/' . $imageName;

            $destinationPath = public_path('storage/'.$storagePath);
            $imageName = time().str_replace(" ", "-", $file->getClientOriginalName());
            $file->move($destinationPath, $imageName);
            return $storagePath.'/'.$imageName;
        } else {
            return response()->json(["success" => false, "message" => "Only file types " . implode(', ', $allowedExtensions) . " are valid"]);
        }
    }
}
