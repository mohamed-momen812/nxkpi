<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GroupRepository extends BaseRepository implements \App\Interfaces\GroupRepositoryInterface
{

    public function __construct(Group $model)
    {
        parent::__construct($model);
    }

    public function getGroupByUser(User $user)
    {
        return $user->group()->first();
    }
}
