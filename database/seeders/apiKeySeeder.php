<?php

namespace Database\Seeders;
use DB;
use File;
use Exception;
use Illuminate\Database\Seeder;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class apiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $seeder = DB::table('seeders')->where('name', "apiKeySeeder")->first();
            if (!isset($seeder)) {
                $json = File::get("database/data/apikeys.json");
                $keys = json_decode($json);
                DB::beginTransaction();

                foreach ($keys as $value) {
                    ApiKey::create([
                        "name"       => $value->name,
                        "key"        => $value->key,
                        "active"     => $value->active,
                   
                    ]);

                }
                DB::table("seeders")->insert(["name" => "apiKeySeeder"]);
                DB::commit();

            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->info($e->getMessage());
        }
    }
}
