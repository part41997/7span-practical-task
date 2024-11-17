<?php

namespace App\Repositories;

use App\Models\Hobby;
use App\Models\User;
use App\Models\UserHobby;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\HobbyRepositoryInterface;

class HobbyRepository implements HobbyRepositoryInterface
{
    /**Add/ Edit/ Delete user base hobbies */
    public function save(int $userId, array $hobbies): bool
    {
        // Get the user
        $user = User::find($userId);

        //Variable declarations
        $hobbyIds = array();

        // Detach previous hobbies from the user
        $user->hobbies()->detach();

        // Manage the hobbies ids array so easy to attach with user
        foreach($hobbies as $hobby){
            $hobbyExist = Hobby::whereName($hobby)->first();
            // If hooby is already exist then direct get ID
            if($hobbyExist){
                $hobbyIds[] = $hobbyExist->id;
            }else{
                // If hobby not exist then create once and then get ID
                $hobbyNew = Hobby::create(['name' => $hobby]);
                $hobbyIds[] = $hobbyNew->id;
            }
        }

       // Attach hobbies to the user
       $user->hobbies()->attach($hobbyIds, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }
}
