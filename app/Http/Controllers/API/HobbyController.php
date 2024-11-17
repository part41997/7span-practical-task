<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\HobbyRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Repositories\Contracts\HobbyRepositoryInterface;

class HobbyController extends Controller
{
    protected $hobbyRepository;

    public function __construct(HobbyRepositoryInterface $hobbyRepository)
    {
        $this->hobbyRepository = $hobbyRepository;
    }

    /**Save hobbies */
    public function save(HobbyRequest $request)
    {
        if(isset($request->user_id) && auth()->user()->role->name == 'user'){
            throw new HttpResponseException(error(__('messages.user_id_is_not_required_once_user_role_is_user'), [], 'validation'));
        }

        $userId = $request->user_id ?? auth()->user()->id;//If logged in user role is `User` then self user id is assign
        $hobbies = $request->hobbies ?? [];

        $user = User::withoutTrashed()->findOrFail($userId);

        if($user->is_admin) throw new HttpResponseException(error(__('messages.admin_cannot_assign_admin_hobbies'), [], 'validation'));
        
        $this->hobbyRepository->save($userId, $hobbies);

        //Return success response
        return ok(__('messages.saved', ['name' => 'User hobbies']));
    }
}
