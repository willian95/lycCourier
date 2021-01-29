<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AccountImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        
        try{

            $index = 0;
            foreach ($rows as $row){
                if($row[0] != null && $index > 0 && $row[12] != ""){

                    $user = new ExampleUser;
                    $user->name = $row[0];
                    $user->email = $row[12];
                    $user->phone = $row[8];
                    $user->address = $row[3];
                    $user->role_id = 4;
                    $user->password = bcrypt($row[12]);
                    $user->save();

                }
                $index++;
            }

        }catch(\Exception $e){
            dd($e->getMessage(), $e->getLine());
        }

    }
}
