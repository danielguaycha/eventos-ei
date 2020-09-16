<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:root');
    }

    public function index() {
        return view('rols.index', ['roles' => Role::where('name', '<>', 'root')->get()]);
    }

    public function store(RoleRequest $request) {

        if (Role::where("name", Str::lower($request->get('name')))->exists()) {
            return back()->with('err', 'Ya existe un rol con este nombre')->withInput($request->all());
        }

        $r = Role::create([
            'name' => Str::lower($request->get('name')),
            'description'=> $request->get('name')
        ]);
        $r->syncPermissions($request->get('perms'));
        return back()->with('ok', 'Rol creado con éxito');
    }

    public function create() {
        return view('rols.create', [
            'perms' => Permission::orderBy('module')->orderBy('id', 'asc')->get()
        ]);
    }

    public function edit($id) {
        $role = Role::with('permissions')->findOrFail($id);

        return view('rols.edit', [
            'role' => $role,
            'perms' => Permission::orderBy('module')->orderBy('id', 'asc')->get()
        ]);
    }

    public function update(RoleRequest $request, $id) {

        $role = Role::findOrFail($id);

        if (Role::where("name", Str::lower($request->get('name')))
                ->where('id', '<>', $id)
                ->exists()) {
            return back()->with('err', 'Ya existe un rol con este nombre')->withInput($request->all());
        }

        $role->update([
            'name' => Str::lower($request->get('name')),
            'description' => $request->get('name')
        ]);

        $role->syncPermissions($request->get('perms'));
        return back()->with('ok', 'Rol actualizado con éxito');
    }
}
