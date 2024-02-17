<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CategoryRepository
{
    public function __construct(
        protected Category $categoryModel
    ) {
    }

    public function findAll()
    {
        return $this->categoryModel::all();
    }
    public function findPaginate(int $qty): mixed
    {
        return $this->categoryModel::where('active', 1)->paginate($qty);
    }

    public function find(int $id): ?Model
    {
        return $this->categoryModel::find($id);
    }

    public function create(array $data)
    {
        $this->categoryModel::create($data);
    }

    public function update($id, array $data)
    {
        $this->categoryModel::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->categoryModel::where('id', $id)->delete();
    }

    public function search(string $term)
    {
        if (!$term) {
            return $this->findPaginate(10);
        }

        return $this->categoryModel::search($term);
    }
}
