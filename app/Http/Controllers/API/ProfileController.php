<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Intervention\Image\Facades\Image;
use App\User;
use Auth;
use Carbon\Carbon;

class ProfileController extends Controller
{
    
    function update(ProfileUpdateRequest $request){

        try{

            $client = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();

            if($request->get('image') != null){
                $fileName = $this->storeImage($request->get("image"));
            }

            if($request->get('imageBack') != null){
                $fileNameBack = $this->storeImage($request->get("imageBack"));
            }

            $user = User::find($client->id);
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->dni = $request->dni;
            $user->address = $request->address;
            $user->department_id = $request->department;
            $user->district_id = $request->district;
            $user->province_id = $request->province;
            if($request->get('image') != null){
                $user->dni_picture = url('/img/clients')."/".$fileName;
            }
            if($request->get('imageBack') != null){
                $user->dni_picture_back = url('/img/clients')."/".$fileNameBack;
            }
            if($request->has("password") && $request->password != ""){
                $user->password = bcrypt($request->password);
            }
            $user->update();

            return response()->json(["success" => true, "msg" => "Perfil actualizado", "user" => $user]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function storeImage($image){

        $imageData = $image;

        if(strpos($imageData, "svg+xml") > 0){

            $data = explode( ',', $imageData);
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
            $ifp = fopen($fileName, 'wb' );
            fwrite($ifp, base64_decode( $data[1] ) );
            rename($fileName, 'img/clients/'.$fileName);

        }else{

            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($image)->save(public_path('img/clients/').$fileName);
        }

        return $fileName;
        
    }

}
