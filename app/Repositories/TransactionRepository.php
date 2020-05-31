<?php

namespace App\Repositories;

use App\User;

class TransactionRepository extends BaseRepository
{
    public $model;

    /**
     * Create a new TransacionRepository instance
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
}
