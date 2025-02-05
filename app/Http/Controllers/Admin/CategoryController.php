<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $categories = Category::query()
                ->select('id', 'title', 'slug')
                ->latest()
                ->get();

            return ApiResponse::success($categories, 'Categories successfully fetched.');
        } catch (Exception $e) {
            return ApiResponse::error('Categories not be fetched', 500, $e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|unique:categories,title',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $category = Category::query()->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title)
            ]);

            if ($request->hasFile('image')) {
                $category->addMedia($request->file('image'))
                    ->toMediaCollection('category_images');
            }

            return ApiResponse::success($category, 'Category created successfully', 201);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to create category', 500, $e->getMessage());
        }
    }

    public function show(Category $category): JsonResponse
    {
        try {
            return ApiResponse::success([
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug
            ], 'Category successfully fetched.');
        } catch (Exception $e) {
            return ApiResponse::error('Category not be fetched.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|unique:categories,title,' . $category->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $category->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title)
            ]);

            if ($request->hasFile('image')) {
                $category->clearMediaCollection('category_images');
                $category->addMedia($request->file('image'))
                    ->toMediaCollection('category_images');
            }

            return ApiResponse::success($category, 'Category updated successfully');

        } catch (Exception $e) {
            return ApiResponse::error('Category update failed.', 500, $e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::query()->find($id);

            if (!$category) {
                return ApiResponse::error('Category not found with ID', 404);
            }

            $hasProducts = $category->products()->exists();

            if ($hasProducts) {
                return ApiResponse::error('Cannot delete, category has products', 422);
            }
            $category->clearMediaCollection('category_images');
            $category->delete();

            return ApiResponse::success(null, 'Category deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to delete category', 500, $e->getMessage());
        }
    }
}
