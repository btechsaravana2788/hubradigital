<?php
namespace App\Repositories;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function all()
    {
        return $this->product->all();
    }

    public function paginate($perPage = 10)
    {
        return $this->product->paginate($perPage);
    }
	
	public function paginateByColumn($column, $value, $perPage = 10)
    {
        return $this->product->where($column, $value)->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->product->find($id);
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }

    public function delete($id)
    {
        $product = $this->findById($id);
        if ($product) {
            return $product->delete();
        }
        return false;
    }
}
