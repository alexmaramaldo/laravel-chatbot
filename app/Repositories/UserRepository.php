<?php

namespace App\Repositories;

use App\User;

class UserRepository extends BaseRepository
{
    public $model;
    /**
     * Create a new UserRepository instance
     *
     * @param User $user Dependency injection from model layer
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->model = $this->obj;
    }

    public function findByEmail(string $email)
    {
        $user = $this->obj->where("email", $email)->first();

        return $user;
    }
}
