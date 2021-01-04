<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;

class DepartmentController extends Controller
{
    
    function fetch(){

        $departments = Department::all();
        return response()->json(["departments" => $departments]);

    }

}
