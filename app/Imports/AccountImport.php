<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
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

                dump($row[12]);
                if($row[0] != null && $index > 0 && $row[12] != ""){

                    $user = new User;
                    $user->name = $row[0];
                    $user->email = $row[12];
                    $user->phone = $row[8];
                    $user->address = $row[3];
                    $user->role_id = 4;
                    $user->email_verified_at = Carbon::now();
                    $user->password = bcrypt($row[12]);
                    $user->save();
                    
                    $this->sendEmail($user);

                }
                $index++;
            }

        }catch(\Exception $e){
            dd($e->getMessage(), $e->getLine());
        }

    }

    function sendEmail($user){
        $to_name = $user->name;
        $to_email = $user->email;
    
        $data = ["user" => $user];

        \Mail::send("emails.welcomeEmail", $data, function($message) use ($to_name, $to_email) {

            $message->to($to_email, $to_name)->subject("¡Nueva plataforma!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });
    }

}
