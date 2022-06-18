<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin_role = Role::where('name', 'superadmin')->first();

        $permissions = ['role', 'permission', 'user', 'surat-masuk', 'surat-keluar', 'division'];
        $permission_ids = [];
        foreach ($permissions as $permission) {
            $actions = ['list', 'create', 'edit', 'delete'];
            foreach ($actions as $action) {
                $new_permission = Permission::create(['name' => $permission . '-' . $action]);
                $permission_ids[] = $new_permission->id;
            }
        }

        $superadmin_role->syncPermissions($permission_ids);
    }
}
