<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**List with filters and search */
    public function index(Request $request)
    {
        /** Validations 
         * NOTE : If we have large sets for request validation then we will prefer to create Validation Request file
        */
        $validator = Validator::make($request->all(), [
            'page'      => 'nullable|min:1',
            'perPage'   => 'nullable|min:1',
            'status'    => 'nullable|in:0,1',// Status should be in 0: Inactive, 1 : Active
            'role'      => 'nullable|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return error(__('messages.validation_failed'), $validator->errors(), 'validation');
        }

        $filters    = $request->only(['search', 'status', 'role']);
        $page       = $request->page ?? 1;
        $perPage    = $request->perPage ?? 10;

        $res = $this->userRepository->all($filters, $page, $perPage);

        $users = $res['users'];
        $count = $res['count'];

        //Return success response
        return ok(__('messages.list', ['name' => 'Users']), compact('users', 'count'));
    }

    /**User details */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        //Return success response
        return ok(__('messages.details', ['name' => 'User']), compact('user'));
    }

    /**Create user */
    public function store(Request $request)
    {
        /** Validations 
         * NOTE : If we have large sets for request validation then we will prefer to create Validation Request file
        */
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required',
            'last_name'         => 'required',
            // 'email' => [
            //     'required',
            //     'email',
            //     Rule::unique('users')->whereNull('deleted_at')], // Only consider non-deleted records
            'email'             => 'required|email|unique:users,email',
            'mobile_number'     => 'required',
            'password'          => 'required|min:8|confirmed',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return error(__('messages.validation_failed'), $validator->errors(), 'validation');
        }

        /** Check if the user role exists
         * NOTE : If we have multiple roles and permissions then we will preferred spatie/laravel-permission 
        */
        $role = Role::whereName('user')->firstOrFail();
        
        $data = $request->only(['first_name', 'last_name', 'email', 'mobile_number', 'password', 'photo']) + ['role_id' => $role->id];

        $user = $this->userRepository->create($data);

        //Return success response
        return ok(__('messages.created', ['name' => 'User']), compact('user'));
    }

    /**Update user */
    public function update(Request $request, $id)
    {
        /** Validations 
         * NOTE : If we have large sets for request validation then we will prefer to create Validation Request file
        */
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email|unique:users,email,' . $id,
            'mobile_number'     => 'required',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return error(__('messages.validation_failed'), $validator->errors(), 'validation');
        }
        
        $data = $request->only(['first_name', 'last_name', 'email', 'mobile_number', 'photo']);

        $user = $this->userRepository->update($id, $data);
        
        //Return success response
        return ok(__('messages.updated', ['name' => 'User']), compact('user'));
    }

    /**Delete user */
    public function destroy($id)
    {
        $this->userRepository->delete($id);

        //Return success response
        return ok(__('messages.deleted', ['name' => 'User']));
    }

    /**Change user status */
    public function changeStatus(Request $request, $id)
    {
        // Change the status using the repository
        $this->userRepository->changeStatus($id);

        //Return success response
        return ok(__('messages.updated', ['name' => 'User status']));
    }
}
