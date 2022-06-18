<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        $names = ["superadmin", "admin surat", "admin bidang", "karyawan"];

        $divison_id = Division::first()->id;

        foreach ($roles as $index => $role) {
            $data = [
                'name'     => ucwords($names[$index]),
                'email'    => str_replace(' ', '', $names[$index]) . "@gmail.com",
                'password' => Hash::make(str_replace(' ', '', $names[$index]) . "123")
            ];

            if ($role->name == 'admin-bidang' || $role->name == 'karyawan') {
                $data['division_id'] = $divison_id;
            }

            $user = User::create($data);

            $user->assignRole($role->name);
        }
    }
}
