<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::create(["name" => "super admin"]);
        $admin = Role::create(["name" => "admin"]);
        $member = Role::create(["name" => "member"]);
        $pastor = Role::create(["name" => "pastor"]);
        $songLeader = Role::create(["name" => "song leader"]);
    }
}
