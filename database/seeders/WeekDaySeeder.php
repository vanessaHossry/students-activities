<?php

namespace Database\Seeders;
use DB;
use Exception;
use File;
use App\Models\WeekDay;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WeekDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $seeder = DB::table('seeders')->where('name', 'WeekDaySeeder')->first();
            if (!isset($seeder)) {
                
                $json = File::get("database/data/weekdays.json");
                $weekdays = json_decode($json);

                DB::beginTransaction();

                foreach ($weekdays as $day) {
                    WeekDay::create(
                        [
                            "name" => $day->name,
                        ]
                    );
                }
                DB::insert('insert into seeders (name) values(?)', ['WeekDaySeeder']);

                DB::commit();
            }

        } catch (Exception $e) {
            DB::rollBack();
            $this->command->info($e->getMessage());
        }
    }
}
