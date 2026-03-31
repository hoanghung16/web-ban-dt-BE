<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());
        return response()->json(['data' => new CategoryResource($category)], 201);
    }

    public function show($id)
    {
        $category = $this->categoryService->getById($id);
        return response()->json(['data' => new CategoryResource($category)]);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryService->update($id, $request->validated());
        return response()->json(['data' => new CategoryResource($category)]);
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->delete($id);
            return response()->json(['message' => 'Danh mục đã xóa thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
