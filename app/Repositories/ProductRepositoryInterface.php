<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function all();
    public function paginate($perPage = 10);
	public function paginateByColumn($column, $value, $perPage = 10);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
