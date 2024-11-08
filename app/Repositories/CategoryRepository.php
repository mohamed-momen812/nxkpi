<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository implements \App\Interfaces\CategoryRepositoryInterface
{

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function orderedAll()
    {
        return $this->model->all()->sortBy('-sort_order');
    }

    public function getCategoriesByUserId($user_id)
    {
        return $this->model->where('user_id' , $user_id )->with('kpis')->get() ;
    }
}
