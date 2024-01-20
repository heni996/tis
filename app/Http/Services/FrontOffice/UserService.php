<?php

namespace App\Http\Services\FrontOffice;

use App\Http\Resources\BackOffice\UserResource;
use App\ModelFilters\UserFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class UserService
{
    public function createUser(array $data, User $userModel): User
    {
        return DB::transaction(function () use ($data, $userModel) {
            $user = $userModel::create(
                $this->userData($data)
            );
            $user->refresh();
            $user->assignRole($data['role']);
            $this->setPermissionsByRoles($user, $data['role']);
            return $user;
        });
    }

    public function editUser(User $user, array $data)
    {

        if (!$user->hasRole($data['role'])) {
            $this->changeRole($user, $data);
        }

        if (array_key_exists('password', $data)) {
            if ($data['password'] !== null) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
        }
        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user): void
    {

        $userId = auth()->user()->id;
        $admin = User::findOrFail($userId);
        if ($admin->hasRole('superadmin') || $admin->hasRole('admin')) {
            $user->delete();
        }
    }

    public function getUserById(int $id, User $userModel)
    {
        $user = $userModel::where('id', $id)->with($this->withModels())->first();
        if ($user) {
            return new UserResource($user);
        }
    }

    public function getUsers(User $userModel, $array)
    {
        try {
            if (auth()->check()) {
                return $userModel::all();
                // $userId = auth()->user()->id;
                // $user = User::findOrFail($userId);
                // $roles = $this->usersRolesHierarchy($user);
                // if ($user->hasRole('client')) {
                //     $usersResource = getRecords($userModel->role($roles), $array, UserResource::class, $this->withModels(), [], false, UserFilter::class);
                //     return $usersResource;
                // } elseif ($user->hasRole('operateur') || $user->hasRole('dispatcheur') || $user->hasRole('commerciale') || $user->hasRole('prestataire')) {
                //     $users = getRecords($userModel->role($roles), $array, UserResource::class, $this->withModels(), [], false, UserFilter::class);
                //     return $users;
                // } elseif ($user->hasRole('superadmin')) {
                //     $userIds = User::pluck('id');
                //     $users = getRecords($userModel->role($roles), $array, UserResource::class, $this->withModels(),  $userIds, true, UserFilter::class);
                //     return $users;
                // } elseif ($user->hasRole('admin')) {
                //     $users = getRecords($userModel->role($roles), $array, UserResource::class, $this->withModels(),  [], false, UserFilter::class);
                //     return $users;
                // }
            } else {
                throw new AuthenticationException();
            }
        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthorized. No user is connected.',
            ], 401);
        }
    }

    public function assignRegion(User $user, array $areas)
    {
        if ($user->isAdmin()) {
            $user->areas()->attach($areas);
        }
    }

    public function removeRegion(User $user, array $areas)
    {
        if ($user->isAdmin()) {
            $user->areas()->detach($areas);
        }
    }

    public function userData($data): array
    {
        return [
            'email'=>$data['email'],
            'password'=>$data['password'],
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'hotel_id'=>$data['hotel_id'],
            'password' => Hash::make($data['password']),
            'role' => isset($data["role"])??null
        ];
    }


    public function setPermissionsByRoles($user, $roles)
    {
        $permissions = [];
        foreach ($roles as $role) {
            $resources = $this->resourcesOfEachUser();
            if (array_key_exists($role, $resources)) {
                $permissionsAllowed = $resources[$role];
                $permissions = array_merge($permissions, getPermissionMethodsByResources($permissionsAllowed));
            }
        }
        $permissions = array_unique($permissions);
        $user->syncPermissions($permissions);
    }



    public function resourcesOfEachUser()
    {
        return [
            'dispatcheur' => [
                'demandes' => ['view', 'follow', 'delete'],
                'specialities' => ['view'],
                'announces' => ['view'],
                'domaines' => ['view'],
                'promos' => ['view'],
                'adresses' => ['view'],
            ],
            'commerciale' => [
                'demandes' => ['view'],
                'domaines' => ['view'],
                'specialities' => ['view'],
                'announces' => ['create', 'view',  'edit', 'delete'],
                'promos' => ['create', 'view',  'edit', 'delete'],
                'adresses' => ['view'],
            ],
            'prestataire' => [
                'demandes' => ['view'],
            ],
            'client' => [
                'demandes' => ['create', 'view', 'follow', 'delete'],
            ],
        ];
    }

    public function withModels()
    {
        return ['roles'];
    }

    public function setUserAuth(int $active_status, User $User): User
    {
        $User->update([
            'auth' => $active_status,
        ]);

        return $User;
    }

    public function ChangeLoginCredentials(User $user, array $data): User
    {
        $password = $data['password'];
        $user->update([...$data, "password" => Hash::make($password)]);
        // $user->notify(new UserCredentials($data + ['password_not_encrypted' => $password]));
        return $user;
    }
    public function usersRolesHierarchy($user)
    {
        if ($user->hasRole('superadmin')) {
            return [
                'superadmin',
                'admin',
                'operateur',
                'dispatcheur',
                'prestataire',
                'commerciale',
                'client',
            ];
        }
        if ($user->hasRole('admin')) {
            return [
                'admin',
                'operateur',
                'dispatcheur',
                'prestataire',
                'commerciale',
                'client'
            ];
        }

        if ($user->hasRole('operateur') || $user->hasRole('dispatcheur') || $user->hasRole('commerciale') || $user->hasRole('prestataire')) {
            return [
                'prestataire',
                'client'
            ];
        }
        if ($user->hasRole('client')) {
            return [
                'prestataire',
            ];
        }
        return [
            'client'
        ];
    }

    public function userLimitedView($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name
        ];
    }


    public function changeRole($user, $data)
    {
        $user->permissions()->detach();
        $user->roles()->detach();
        $user->assignRole($data['role']);
        $this->setPermissionsByRoles($user, $data['role']);
    }
}
