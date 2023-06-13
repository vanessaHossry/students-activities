<?php

namespace Database\Seeders;
use DB;
use File;
use Exception;
use App\Models\Portal;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
            $seeder = DB::table('seeders')->where('name','PortalSeeder')->first();
            if(!isset($seeder)){
                //$this->command->info('Hello, world!');
                $json = File::get("database/data/portals.json");
                $portals = json_decode($json);

                DB::beginTransaction();
                foreach($portals as $portal){
                    Portal::create(
                        [
                            "name"=>$portal->name,
                        ]
                        );
                }
                DB::insert('insert into seeders (name) values (?)',['PortalSeeder']);
                DB::commit();
            }
        }
        catch(Exception $e){
            DB::rollBack();
            $this->command->info($e->getMessage());
            
        }
    }
}
