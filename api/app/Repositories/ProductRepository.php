<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductRepository
{
    public function __construct(
        protected Product $productModel
    ) {
    }

    public function findPaginate(int $qty): mixed
    {
        return $this->productModel::where('active', 1)->paginate($qty);
    }

    public function find(int $id): ?Model
    {
        return $this->productModel::find($id);
    }

    public function create(array $data)
    {
        $this->productModel::create($data);
    }

    public function update($id, array $data)
    {
        $this->productModel::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->productModel::where('id', $id)->delete();
    }

    public function search(string $term)
    {
        if (!$term) {
            return $this->findPaginate(10);
        }

        return $this->productModel::search($term);
    }
}
