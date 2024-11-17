<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    /**Get all users */
    public function all(array $filters, int $page, int $perPage);

    /**Get single user details */
    public function find(int $id);

    /**Create user */
    public function create(array $data);

    /**Update user */
    public function update(int $id, array $data);

    /**Delete user */
    public function delete(int $id);

    /**Change status */
    public function changeStatus(int $id);
}
