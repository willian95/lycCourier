<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        if(User::where("role_id", 1)->count() <= 0){
            $user = new User;
            $user->name  = "Admin";
            $user->email = "admin@gmail.com";
            $user->password = bcrypt("12345678");
            $user->role_id = 1;
            $user->save();
        }

    }
}
