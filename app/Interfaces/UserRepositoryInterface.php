<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{

    public function getUsersByGroup($group_id);

}
