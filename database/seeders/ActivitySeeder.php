<?php

namespace Database\Seeders;

use File;
use DB;
use Exception;
use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $seeder = DB::table('seeders')->where('name', "ActivitySeeder")->first();
            if (!isset($seeder)) {
                $json = File::get("database/data/activities.json");
                $keys = json_decode($json);
                DB::beginTransaction();

                foreach ($keys as $value) {
                    Activity::create([
                        "name"       => $value->name,
                        "price"      => $value->price,
                   
                    ]);

                }
                DB::table("seeders")->insert(["name" => "ActivitySeeder"]);
                DB::commit();

            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->info($e->getMessage());
        }
    }
    }

