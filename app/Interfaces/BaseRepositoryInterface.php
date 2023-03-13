<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


interface BaseRepositoryInterface
{
    public function create(array $attributes): Model;
    public function update(array $attributes, $id);
    public function find($id): ?Model;
    public function all(): Collection;
    public function allWithPaginate($number = 10);
    public function allWithPaginateExcept($id, $number = 10);
    public function groupBy($key);
    public function destroy($id);
    public function findBy($request , $field_name, $field);
}