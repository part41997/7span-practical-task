<?php 

namespace App\Repositories\Contracts;

interface AuthRepositoryInterface
{
    /**Login method declaration */
    public function login(array $credentials);
}
