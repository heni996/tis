<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

function createPermissionByMethods(
    array $methods,
    string $nameWithId,
    string $guardName = 'api'
): int {
    try {
        foreach ($methods as $method) {

            $existingPermission = Permission::where([
                'name' => $method . ' ' . $nameWithId,
                'guard_name' => $guardName
            ])->first();
            if (!$existingPermission) {
                Permission::create([
                    'name' => $method . ' ' . $nameWithId,
                    'guard_name' => $guardName
                ]);
            }
        }
    } catch (\Throwable $th) {
        throw new Exception($th);
    }
    return 1;
}

function assignPermissionToUserByMethods(
    array $methods,
    User $user,
    string $permission
): int {
    try {
        foreach ($methods as $method) {
            $permissionObject = Permission::where('name', $method . ' ' . $permission)->first();
            if (!$user->can($permissionObject)) {
                $user->givePermissionTo($permissionObject);
            }
        }
    } catch (\Throwable $th) {
        throw new Exception($th);
    }

    return 1;
}

function revokePermissionToUserByMethods(
    array $methods,
    User $user,
    string $permission
): int {
    try {
        foreach ($methods as $method) {
            $permissionObject = Permission::where('name', $method . ' ' . $permission)->first();
            if (!$user->can($permissionObject)) {
                $user->revokePermissionTo($permissionObject);
            }
        }
    } catch (\Throwable $th) {
        throw new Exception($th);
    }
    return 1;
}


function getMethods($revokeMethods = [])
{
    $methods = ['view', 'delete', 'edit', 'create', 'validate'];
    return array_diff($methods, $revokeMethods);
}

function getPermissionMethodsByResources($resources)
{
    if (!$resources) {
        return [];
    }

    $permissionNames = Permission::where(function ($query) use ($resources) {
        foreach ($resources as $key => $resource) {
            $methodsAllowed = array_map(function ($value) use ($key) {
                return $value . ' ' . $key;
            }, $resources[$key]);
            $query->orWhere('name', 'LIKE', "%$key%")
                ->where('name', 'NOT LIKE', "%;%")
                ->whereIn('name', $methodsAllowed);
        }
    })->pluck('name')->toArray();
    return $permissionNames;
}

function getActions()
{
    return [
        'GET' => 'view',
        'POST' => 'create',
        'PUT' => 'edit',
        'DELETE' => 'delete',
    ];
}
