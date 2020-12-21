<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Intervention\Image\Facades\Image;
use App\User;
use Carbon\Carbon;

class ProfileController extends Controller
{
    
    function index(){
        return view("clients.profile.index");
    }

    function update(ProfileUpdateRequest $request){

        try{

            if($request->get('image') != null){
                try{
        
                    $imageData = $request->get('image');

                    if(strpos($imageData, "svg+xml") > 0){

                        $data = explode( ',', $imageData);
                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                        $ifp = fopen($fileName, 'wb' );
                        fwrite($ifp, base64_decode( $data[1] ) );
                        rename($fileName, 'img/clients/'.$fileName);
        
                    }else{

                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                        Image::make($request->get('image'))->save(public_path('img/clients/').$fileName);
                    }
        
                }catch(\Exception $e){
        
                    return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        
                }
            }

            $user = User::find(\Auth::user()->id);
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->dni = $request->dni;
            $user->address = $request->address;
            if($request->get('image') != null){
                $user->dni_picture = url('/img/clients')."/".$fileName;
            }
            if($request->has("password")){
                $user->password = bcrypt($request->password);
            }
            $user->update();

            return response()->json(["success" => true, "msg" => "Perfil actualizado"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

}
