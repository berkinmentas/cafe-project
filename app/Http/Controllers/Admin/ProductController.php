<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $products = Product::query()
                ->with(['categories:id,title,slug'])
                ->latest()
                ->get();

            return ApiResponse::success($products, 'Products successfully fetched');
        } catch (Exception $e) {
            return ApiResponse::error('Products not be fetched', 500, $e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|integer|min:0',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $product = Product::query()->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'price' => $request->price
            ]);

            if ($request->hasFile('image')) {
                $product->addMedia($request->file('image'))
                    ->toMediaCollection('product_images');
            }

            $product->categories()->attach($request->categories);

            $product->load(['categories:id,title']);

            return ApiResponse::success($product, 'Product created successfully', 201);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to create product', 500, $e->getMessage());
        }
    }

    public function show(Product $product): JsonResponse
    {
        try {
            $product->load(['categories:id,title']);

            return ApiResponse::success($product, 'Product successfully fetched');
        } catch (Exception $e) {
            return ApiResponse::error('Product not be fetched.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $product = Product::query()->find($id);

            if (!$product) {
                return ApiResponse::error('Product not found with ID', 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|integer|min:0',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $product->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'price' => $request->price
            ]);

            if ($request->hasFile('image')) {
                $product->clearMediaCollection('product_images');
                $product->addMedia($request->file('image'))
                    ->toMediaCollection('product_images');
            }

            $product->categories()->sync($request->categories);

            $product->load('categories:id,title');

            return ApiResponse::success($product, 'Product updated successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to update product', 500, $e->getMessage());
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->categories()->detach();
            $product->delete();

            if (!$product) {
                return ApiResponse::error('Product not found with ID', 404);
            }

            $product->clearMediaCollection('product_images');
            $product->categories()->detach();
            $product->delete();

            return ApiResponse::success(null, 'Product deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to delete product', 500, $e->getMessage());
        }
    }
}
