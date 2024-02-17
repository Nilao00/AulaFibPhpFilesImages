<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\Repositories\CategoryRepository;
use App\Services\FileService;

class CategoryService
{
    const IMAGE_PATH = 'images/category';

    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
    }

    public function findAll()
    {
        return $this->categoryRepository->findAll();
    }
    public function findPaginate(int $qty): mixed
    {
        return $this->categoryRepository->findPaginate($qty);
    }
    public function create(array $data)
    {
        $image = Arr::get($data, 'image', []);
        $image = $data['image'];
        $fileName = FileService::move($image, self::IMAGE_PATH);
        $this->categoryRepository->create([
            'name' => Arr::get($data, 'name'),
            'image' => $fileName
        ]);
    }

    public function update($id, array $data)
    {
        $category = $this->categoryRepository->find($id);
        if (!empty($category)) {
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
            return $category->update($update);
        }
        return false;
    }
    public function delete($id)
    {
        $category = $this->categoryRepository->find($id);

        if (!empty($category)) {
            FileService::delete($category->image, self::IMAGE_PATH);
            $category->products()->delete();
            return $category->delete();
        }
        return false;
    }
    public function find($id)
    {
        $idAux = $id;
        if (!is_numeric($idAux)) {
            $idAux = 0;
        }

        return $this->categoryRepository->find($idAux);
    }

    public function search(string $term)
    {
        return $this->categoryRepository->search($term);
    }
}
