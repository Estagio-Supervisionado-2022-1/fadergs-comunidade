<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            // DEPARTAMENTS
            'create_departament', 'edit_departament', 'delete_departament', 'view_departament', 'list_departaments',

            // OPERATOR
            'create_operator', 'edit_operator', 'delete_operator', 'view_operator', 'list_operators', 'accept_operator', 'request_operator',

            //APPOINTMENT
            'create_appointment', 'edit_appointment', 'delete_appointment','view_appointment', 'list_appointments',

            // USER
            'edit_user', 'delete_user', 'view_user', 'list_users',

            // SERVICE
            'create_service', 'edit_service', 'delete_service', 'view_service', 'list_services',

            // ADDRESS
            'create_address', 'edit_address', 'delete_address', 'view_address', 'list_addressess',
            
            // SECONDARY ADDRESS
            'create_secondary_address', 'edit_secondary_address', 'delete_secondary_address', 'view_secondary_address','list_secondary_addressess',

            // PERMISSION
            'create_permission', 'edit_permission', 'delete_permission', 'view_permission', 'list_permissions',

            // ROLE
            'create_role', 'edit_role', 'delete_role', 'view_role', 'list_roles',
        ];

        foreach ($permissions as $permission){
            Permission::create([
                'name'          => $permission,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }

        Role::create(['name' => 'admin'
        ])->givePermissionTo(Permission::all());

        Role::create ([
            'name' => 'manager'
        ])->givePermissionTo('create_address', 'edit_address', 'delete_address', 'view_address', 'list_addressess','create_secondary_address', 'edit_secondary_address', 'delete_secondary_address', 'view_secondary_address','list_secondary_addressess');

        Role::create([
            'name' => 'student'
        ]);



    }
}
