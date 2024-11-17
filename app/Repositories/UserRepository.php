<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /** Get all uers with search, filters and paginate data  */
    public function all(array $filters = [], int $page, int $perPage)
    {
        /** Check if the admin role exists
         * NOTE : If we have multiple roles and permissions then we will preferred spatie/laravel-permission 
        */
        $role = Role::whereName('admin')->firstOrFail();
        
        /**Getting only users list */
        $query = User::query()->with('role', 'photo', 'hobbies')->where('role_id', '!=', $role->id);

        // Apply search filter
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        // Apply status filter using the scope
        if (isset($filters['status'])) {
            $query->status($filters['status']);
        }

        // Apply role filter using the scope
        if (isset($filters['role'])) {
            $query->role($filters['role']);
        }

        // Calculate skip value
        $skip = ($page - 1) * $perPage;

        $count = $query->count();

        $res = [
            'users' => $query->skip($skip)->take($perPage)->orderBy('id', 'DESC')->get(),// Apply skip and take for manual pagination
            'count' => $count // Result count variable for use pagination from front end side
        ];
        
        return $res;
    }

    /**User details */
    public function find(int $id)
    {
        return User::with('role', 'photo', 'hobbies')->findOrFail($id);
    }

    /**Create user */
    public function create(array $data)
    {
        $user = User::create($data);

        if (isset($data['photo'])) {
            $this->savePhoto($user, $data['photo']);
        }

        $user->load('photo');
        return $user;
    }

    /**Update user */
    public function update(int $id, array $data)
    {
        $user = $this->find($id);
        
        if (isset($data['photo'])) {
            // Delete the existing photo if any
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo->path);
                $user->photo()->delete();
            }
            $this->savePhoto($user, $data['photo']);
        }
        $user->update($data);
        $user->load('photo');

        return $user;
    }

    /**Delete user */
    public function delete(int $id)
    {
        $user = $this->find($id);
        return $user->delete();
    }

    /**Save user profile photo */
    public function savePhoto(User $user, $photo)
    {
        $path = $photo->store('profile-photos', 'public');

        $inputs['name']         = $photo->getClientOriginalName();
        $inputs['path']         = $path;
        $inputs['mime_type']    = $photo->getMimeType(); 
        $inputs['type']         = 'profile_photo'; 

        $user->photo()->create($inputs);
    }

    /**Change status */
    public function changeStatus(int $id)
    {
        // Retrieve the user
        $user = $this->find($id);

        // Update the user's status
        $user->status = !$user->status;
        return $user->save();
    }
}
