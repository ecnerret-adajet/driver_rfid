<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Image;
use App\Driver;

class ImagesController extends Controller
{
    public function driverImage(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'required'
        ]);

        $data = $request->input('avatar');
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);
        $imageName = time() . '.png';
        $path = public_path('storage/drivers/');

        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($path . $imageName, $data);

        $imageUrl = 'drivers/' . $imageName;
        $driver_image = new Image;
        $driver_image->avatar = $imageUrl;
        $driver_image->save();

         return response(['data' => $imageUrl], 201);

    }

    public function getImage()
    {
        $image = Image::doesntHave('driver')->orderBy('id','DESC')->first()->id;
        return $image;
    }
}
