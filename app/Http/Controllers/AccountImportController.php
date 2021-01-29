<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AccountImport;
use Maatwebsite\Excel\Facades\Excel;

class AccountImportController extends Controller
{
    
    function import(){
        ini_set('max_execution_time', 0);
        
        try{

            Excel::import(new AccountImport, 'excel/Accounts.xls');

        }catch(\Exception $e){
            dd($e->getMessage(), $e->getLine());
        }
    }

}
