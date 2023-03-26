<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class GroupRepository extends BaseRepository implements \App\Interfaces\GroupRepositoryInterface
{

    public function __construct(Group $model)
    {
        parent::__construct($model);
    }


}
