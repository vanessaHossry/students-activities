<?php

namespace Database\Seeders;
use DB;
use File;
use Exception;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
                $seeder=DB::table('seeders')->where('name','UserSeeder')->first();
                if(!isset($seeder)){
                    $json = File::get("database/data/users.json");
                    $users=json_decode($json);

                    DB::beginTransaction();
                    foreach($users as $value){
                        $user=User::create([
                            "first_name"=>$value->first_name,
                            "last_name"=>$value->last_name,
                            "email"=>$value->email,
                            "password"=> $value->password,
                            "date_of_birth"=>$value->date_of_birth,
                            "gender"=>$value->gender,
                    
                        ]);
                       

                        $role=Role::where("slug", $value->role_slug)->first(); 
                        
                        $user->assignRole($role);
                           
                       
                    }
                    DB::insert('insert into seeders (name )values(?)',['UserSeeder']);
                    DB::commit();

                }
        }
        catch(Exception $e){
            DB::rollBack();
            $this->command->error($e->getMessage());

        }
    }
}
