<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    
        /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['guard_name' => 'api', 'name' => 'CREATE_TASK']);
        Permission::create(['guard_name' => 'api', 'name' => 'DELETE_TASK']);

        // create roles and assign existing permissions
        $role1 = Role::create(['guard_name' => 'api', 'name' => 'USER']);
        $role1->givePermissionTo('CREATE_TASK');

        $role2 = Role::create(['guard_name' => 'api', 'name' => 'ADMIN']);
        $role2->givePermissionTo('CREATE_TASK');
        $role2->givePermissionTo('DELETE_TASK');

        
       
        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Juan Mateus',
            'email' => 'juan@gmail.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Pedro Pascal',
            'email' => 'admin@gmail.com',
        ]);
        $user->assignRole($role1);

        
    }
    
}
