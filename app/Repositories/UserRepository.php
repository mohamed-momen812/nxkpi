<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements \App\Interfaces\UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUsersByGroup($group_id)
    {
        // TODO: Implement getUsersByGroup() method.
        return $this->model->where('group_id' , $group_id)->get();
    }

    public function getUsersByParent($parent_user)
    {
        // TODO: Implement getUsersByGroup() method.
        return $this->model->where('parent_user' , $parent_user)->get();
    }
}
