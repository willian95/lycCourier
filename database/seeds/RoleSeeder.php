<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        if(Role::where("id", 1)->count() <= 0){
            $role = new Role;
            $role->name  = "admin";
            $role->save();
        }

        if(Role::where("id", 2)->count() <= 0){
            $role = new Role;
            $role->name  = "funcionario";
            $role->save();
        }

        if(Role::where("id", 3)->count() <= 0){
            $role = new Role;
            $role->name  = "socio";
            $role->save();
        }

        if(Role::where("id", 4)->count() <= 0){
            $role = new Role;
            $role->name  = "cliente";
            $role->save();
        }

        if(Role::where("id", 5)->count() <= 0){
            $role = new Role;
            $role->name  = "aduana";
            $role->save();
        }

    }
}
