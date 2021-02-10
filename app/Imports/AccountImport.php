<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Jobs\SendWelcomeEmail;
use Carbon\Carbon;

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

                    if(User::where("email", trim($row[12]))->count() == 0){
                        $user = new User;
                        $user->name = $row[0];
                        $user->email = trim($row[12]);
                        $user->phone = $row[8];
                        $user->address = $row[3];
                        $user->role_id = 4;
                        $user->email_verified_at = Carbon::now();
                        $user->password = bcrypt($row[12]);
                        $user->save();
                        
                        SendWelcomeEmail::dispatch($user->id);
                    }

                }
                $index++;
            }

        }catch(\Exception $e){
            dd($e->getMessage(), $e->getLine());
        }

    }

}
