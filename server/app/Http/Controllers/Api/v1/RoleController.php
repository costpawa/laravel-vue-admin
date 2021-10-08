<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleCollection;
use App\Http\Requests\StoreRoleRequest;

class RoleController extends Controller
{
    public function index ():RoleCollection
    {
        return new RoleCollection(Role::all());
    }

    public function store (StoreRoleRequest $request):RoleResource
    {
        $role = Role::create([
            'name'          => slugify($request->name),
            'display_name'  => $request->display_name,
            'color'         => $request->color,
            'guard'         => 'web',
        ]);
        $role->givePermissionTo($request->permissions);
        return new RoleResource($role);
    }

    public function show (Role $role):RoleResource
    {
        return new RoleResource($role);
    }

    public function update (StoreRoleRequest $request, Role $role):RoleResource
    {
        $role->name         = slugify($request->name);
        $role->display_name = $request->display_name;
        $role->color        = $request->color;
        $role->save();
        $role->syncPermissions($request->permissions);
        return new RoleResource($role);
    }

    public function destroy (Role $role):bool
    {
        $role->syncPermissions();
        $role->delete();
        return true;
    }
}
