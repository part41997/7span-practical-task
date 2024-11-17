<?php

namespace App\Repositories\Contracts;

interface HobbyRepositoryInterface
{
    /**Add/ Edit/ Delete user base hobbies */
    public function save(int $userId, array $hobbies): bool;
}
