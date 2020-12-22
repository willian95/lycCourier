<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Carbon\Carbon;

class UserController extends Controller
{
    function index(){
        return view("users.index", ["roles" => Role::all()]);
    }

    function store(UserStoreRequest $request){

        try{
            
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_id = $request->roleId;
            $user->email_verified_at = Carbon::now();
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(["success" => true, "msg" => "Usuario creado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function update(UserUpdateRequest $request){

        try{
            
            if(User::where("email", $request->email)->where("id", "<>", $request->id)->count() > 0){

                return response()->json(["success" => false, "msg" => "Ya existe un usuario con este correo"]);

            }

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_id = $request->roleId;
            if($request->has("password")){
                $user->password = bcrypt($request->password);
            }
            
            $user->update();

            return response()->json(["success" => true, "msg" => "Usuario actualizado"]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function delete(Request $request){

        try{
            
            $user = User::find($request->id);
            $user->email = uniqid();
            $user->update();
            $user->delete();
            return response()->json(["success" => true, "msg" => "Usuario eliminado"]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function fetch($page = 1){
        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $users = User::skip($skip)->with("role")->where("role")->take($dataAmount)->get();
            $usersCount = User::with("role")->count();

            return response()->json(["success" => true, "users" => $users, "usersCount" => $usersCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor"]);

        }
    }

    

}
