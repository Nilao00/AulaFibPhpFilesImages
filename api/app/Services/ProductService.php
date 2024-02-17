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
        $image = Arr::get($data, 'image', []);
        $image = $data['image'];
        $fileName = FileService::move($image, self::IMAGE_PATH);
        $this->productRepository->create([
            'name' => Arr::get($data, 'name'),
            'image' => $fileName
        ]);
    }

    public function update($id, array $data)
    {
        $product = $this->productRepository->find($id);
        if (!empty($product)) {
            $update = [
                'name' => Arr::get($data, 'name')
            ];

            $newimage = Arr::get($data, 'image');
            $deletedImage = Arr::get($data, 'deleted_image');
            if (!empty($newimage)) {
                $fileName = FileService::move($newimage, self::IMAGE_PATH);
                $update['image'] = $fileName;
                FileService::delete($deletedImage, self::IMAGE_PATH);
            }
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
