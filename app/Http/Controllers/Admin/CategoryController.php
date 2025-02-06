<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
            $categories = Category::query()->where('user_id', auth()->id())
                ->select('id', 'title', 'slug')
                ->latest()
                ->get()
                ->map(fn($category) => [
                    'id' => $category->id,
                    'title' => $category->title,
                    'slug' => $category->slug,
                    'image_url' => $category->image_url
                ]);

            return ApiResponse::success($categories, 'Categories fetched successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Error fetching categories', 500, $e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $token = $request->header('Authorization');

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|unique:categories,title',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }
            $token = str_replace('Bearer ', '', $token);
            $admin = Admin::query()->where('access_token', $token)->first();

            if (!$admin) {
                return ApiResponse::error('Invalid token', 401);
            }

            $category = Category::query()->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'user_id' => $admin->id
            ]);

            if ($request->hasFile('image')) {
                $category->addMedia($request->file('image'))
                    ->toMediaCollection('category_images');
            }

            return ApiResponse::success([
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
                'image_url' => $category->image_url
            ], 'Category created successfully', 201);
        } catch (Exception $e) {
            return ApiResponse::error('Error creating category', 500, $e->getMessage());
        }
    }

    public function show(Category $category): JsonResponse
    {
        try {
            if ($category->user_id !== auth()->id()) {
                return ApiResponse::error('Unauthorized access', 403);
            }

            return ApiResponse::success([
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
                'image_url' => $category->image_url
            ], 'Category fetched successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Error fetching category', 500, $e->getMessage());
        }
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        try {
            if ($category->user_id !== auth()->id()) {
                return ApiResponse::error('Unauthorized access', 403);
            }

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

            return ApiResponse::success([
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
                'image_url' => $category->image_url
            ], 'Category updated successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Error updating category', 500, $e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return ApiResponse::error('Category not found', 404);
            }

            if ($category->user_id !== auth()->id()) {
                return ApiResponse::error('Unauthorized access', 403);
            }

            if ($category->products()->exists()) {
                return ApiResponse::error('Cannot delete category with products', 422);
            }

            $category->clearMediaCollection('category_images');
            $category->delete();

            return ApiResponse::success(null, 'Category deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Error deleting category', 500, $e->getMessage());
        }
    }
}
