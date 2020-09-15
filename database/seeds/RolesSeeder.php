<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
class RolesSeeder extends Seeder
{
    public function run()
    {

        $modules = [
            'admins' => 'Administradores',
            'students' => 'Estudiantes'
        ];

        foreach ($modules as $key => $value) {
            Permission::create(['name' => $key.'.store', 'description' => "Crear $value"]);
            Permission::create(['name' => $key.'.update', 'description' => "Actualizar $value"]);
            Permission::create(['name' => $key.'.destroy', 'description' => "Dar de baja $value"]);
            Permission::create(['name' => $key.'.index', 'description' => "Listar, Buscar $value"]);
            Permission::create(['name' => $key.'.show', 'description' => "Mostrar detalle de $value"]);
        }


        Role::create(['name' => User::rolRoot, 'description' => '']);
        $admin = Role::create(['name' => User::rolAdmin, 'description' => 'Administrador']);
        $student = Role::create(['name' => User::rolStudent, 'description' => 'Estudiante']);

        $admin->givePermissionTo('students.index', 'students.show');

    }
}
