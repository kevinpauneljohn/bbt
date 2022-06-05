<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
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

        //        user
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'view user']);

        //        permission
        Permission::create(['name' => 'add permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'view permission']);

        //        role
        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'view role']);

//        church
        Permission::create(['name' => 'add church'])->syncRoles(['admin','member','pastor','song leader']);
        Permission::create(['name' => 'delete church'])->syncRoles(['admin','member','pastor','song leader']);
        Permission::create(['name' => 'edit church'])->syncRoles(['admin','member','pastor','song leader']);
        Permission::create(['name' => 'view church'])->syncRoles(['admin','member','pastor','song leader']);

//        prayer request
        Permission::create(['name' => 'add prayer request'])->syncRoles(['admin','member','pastor','song leader']);
        Permission::create(['name' => 'delete prayer request'])->syncRoles(['admin','member','pastor','song leader']);
        Permission::create(['name' => 'edit prayer request'])->syncRoles(['admin','member','pastor','song leader']);
        Permission::create(['name' => 'view prayer request'])->syncRoles(['admin','member','pastor','song leader']);
    }
}
