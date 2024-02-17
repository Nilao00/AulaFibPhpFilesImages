<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class CategoryController
{

    public function __construct(
        protected CategoryService $categoryService
    ) {
    }

    public function index()
    {
        $categories = $this->categoryService->findPaginate(10);

        return view('category.index', compact('categories'));
    }

    public function add(): View
    {
        return view('category.add');
    }

    public function save(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:10|max:255',
            'image' => 'required|file'
        ]);
        $this->categoryService->create($request->all());

        return redirect()->route('category.index');
    }

    public function edit($id)
    {
        $category = $this->categoryService->find($id);

        if (!empty($category)) {
            return view('category.edit', compact('category'));
        }

        return redirect()->route('category.index');
    }
    public function search(Request $request)
    {
        $term = Arr::get($request->all(), 'term', '');
        $category = $this->categoryService->search($term);
        return view('category.search', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|min:1|max:255',
            'image' => 'sometimes|file',
            'deleted_image' => 'sometimes|string'
        ]);

        $this->categoryService->update($id, $request->all());

        return redirect()->route('category.index');
    }

    public function delete($id)
    {
        $this->categoryService->delete($id);
        return redirect()->route('category.index');
    }
}
