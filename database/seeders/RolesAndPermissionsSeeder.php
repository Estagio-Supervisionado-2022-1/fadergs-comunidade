<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            'create_departament',   'edit_departament',     'delete_departament',   'view_departament',     'list_departaments',
            'create_operator',      'edit_operator',        'delete_operator',      'view_operator',        'list_operators',       'accept_operator', 'request_operator',
            'create_appointment',   'edit_appointment',     'delete_appointment',   'view_appointment',     'list_appointments',
                                    'edit_user',            'delete_user',          'view_user',            'list_users',
            'create_service',       'edit_service',         'delete_service',       'view_service',         'list_services',
            'create_address',       'edit_address',         'delete_address',       'view_address',         'list_addressess',
            'create_permission',    'edit_permission',      'delete_permission',    'view_permission',      'list_permissions',
            'create_role',          'edit_role',            'delete_role',          'view_role',            'list_roles',
        ];

        foreach ($permissions as $permission){
            // Permission::create();
        }


    }
}
