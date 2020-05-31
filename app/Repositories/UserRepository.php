<?php

namespace App\Repositories;

use App\User;
use Exception;

class UserRepository extends BaseRepository
{
    public $model;

    /**
     * Create a new UserRepository instance
     *
     * @param User $user Dependency injection from model layer
     *
     * @return void
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->model = $this->obj;
    }


    /**
     * Search user by email
     *
     * @param string $user
     *
     * @return object
     */
    public function findByEmail(string $email)
    {
        $user = $this->obj->where("email", $email)->first();
        return $user;
    }
}
