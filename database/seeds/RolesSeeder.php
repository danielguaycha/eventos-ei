<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
class RolesSeeder extends Seeder
{
    public function run()
    {
        // permisos
        $modules = [
            'users' => 'Usuarios',
            'sponsors' => 'Organizadores',
            'signatures' => 'Firmas',
            'students' => 'Estudiantes',
            'events' => 'Eventos',
        ];

        foreach ($modules as $key => $value) {
            Permission::create(['name' => $key.'.store', 'description' => "Crear $value", 'module' => $value]);
            Permission::create(['name' => $key.'.update', 'description' => "Actualizar $value", 'module' => $value]);
            Permission::create(['name' => $key.'.destroy', 'description' => "Dar de baja $value", 'module' => $value]);
            Permission::create(['name' => $key.'.index', 'description' => "Listar, Buscar $value", 'module' => $value]);
            //Permission::create(['name' => $key.'.show', 'description' => "Mostrar detalle de $value", 'module' => $value]);
        }

        //* permisos especiales
        // Estudiantes
        $this->addPerm("Estudiantes", "events.add.students", "Agregar estudiante a un evento");

        $this->addPerm("Eventos", "events.design.edit", "Diseñar certificados para eventos");
        $this->addPerm("Eventos", "events.design.view", "Pre-visualizar certificados de eventos");

        // roles

        Role::create(['name' => User::rolRoot, 'description' => '']);
        $admin = Role::create(['name' => User::rolAdmin, 'description' => 'Administrador']);
        $student = Role::create(['name' => User::rolStudent, 'description' => 'Estudiante']);


        $admin->givePermissionTo(
            'students.index',
            'events.store',
            'events.index'
        );

    }

    private function addPerm($modulo, $nombre, $desc){
        Permission::create([
            'name' => $nombre,
            'description' => $desc,
            'module' => $modulo
        ]);
    }
}
