<?php

namespace App\Repositories;

use App\Interfaces\EntryRepositoryInterface;
use App\Models\Entry;

class EntryRepository extends BaseRepository implements EntryRepositoryInterface
{
    public function __construct(Entry $model)
    {
        parent::__construct($model);
    }
    
}
