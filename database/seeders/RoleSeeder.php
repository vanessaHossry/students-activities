<?php

namespace Database\Seeders;
use DB;
use File;
use Exception;
use App\Models\Role;
use App\Models\Portal;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
            $seeder = DB::table('seeders')->where('name','RoleSeeder')->first();
            if(!isset($seeder))
            {
                $json = File::get('database/data/roles.json');
                $roles = json_decode($json);

                DB::beginTransaction();
                foreach($roles as $role)
                {
                    $portal = Portal::where('slug',$role->portal_slug)->value('id'); 
                    Role::create(
                        [
                            "name"=> $role->name,
                            "portal_id"=>$portal,
                        ]
                        );
                }
                DB::insert('insert into seeders (name) values(?)',['RoleSeeder']);
                DB::commit();
            }
        }
        catch(Exception $e)
        {
            DB::rollback();
            $this->command->error($e->getMessage());
        }
    }
}
