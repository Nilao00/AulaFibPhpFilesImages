<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\Repositories\ProductRepository;
use App\Services\FileService;

class ProductService
{
    const IMAGE_PATH = 'images/product';

    public function __construct(
        protected ProductRepository $productRepository
    ) {
    }

    public function findPaginate(int $qty): mixed
    {
        return $this->productRepository->findPaginate($qty);
    }
    public function create(array $data)
    {
        $name = Arr::get($data, 'name');
        $description = Arr::get($data, 'description');
        $this->productRepository->create([
            'name' => $name,
            'description' => $description
        ]);
    }

    public function update($id, array $data)
    {
        $product = $this->productRepository->find($id);
        if (!empty($product)) {
            $update = [
                'name' => Arr::get($data, 'name'),
                'description' => Arr::get($data, 'description')
            ];

            return $product->update($update);
        }
        return false;
    }
    public function delete($id)
    {
        $this->productRepository->delete($id);
    }
    public function find($id)
    {
        $idAux = $id;
        if (!is_numeric($idAux)) {
            $idAux = 0;
        }

        return $this->productRepository->find($idAux);
    }

    public function search(string $term)
    {
        return $this->productRepository->search($term);
    }
}
