<?php

namespace Database\Seeders;

use DB;
use File;
use Exception;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $seeder = DB::table('seeders')->where('name', "PermissionSeeder")->first();
            if (!isset($seeder)) {
                $json = File::get("database/data/permissions.json");
                $permissions = json_decode($json);
                DB::beginTransaction();

                foreach ($permissions as $value) {
                    $permission = Permission::create([
                        "name" => $value->name,
                    ]);

                    foreach ($value->roles as $roleId) {
                        $role = Role::find($roleId);
                        $permission->assignRole($role);
                    }

                }
                DB::table("seeders")->insert(["name" => "PermissionSeeder"]);
                DB::commit();

            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->info($e->getMessage());
        }
    }
}
