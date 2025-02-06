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
            $products = Product::with(['categories:id,title,slug'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return ApiResponse::success($products, 'Products fetched');
        } catch (Exception $e) {
            return ApiResponse::error('Error fetching products', 500, $e->getMessage());
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

            $product = Product::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'price' => $request->price,
                'user_id' => auth()->id()
            ]);

            if ($request->hasFile('image')) {
                $product->addMedia($request->file('image'))
                    ->toMediaCollection('product_images');
            }

            $product->categories()->attach($request->categories);
            $product->load(['categories:id,title']);

            return ApiResponse::success($product, 'Product created', 201);
        } catch (Exception $e) {
            return ApiResponse::error('Error creating product', 500, $e->getMessage());
        }
    }

    public function show(Product $product): JsonResponse
    {
        try {
            if ($product->user_id !== auth()->id()) {
                return ApiResponse::error('Unauthorized', 403);
            }

            $product->load(['categories:id,title']);
            return ApiResponse::success($product, 'Product fetched');
        } catch (Exception $e) {
            return ApiResponse::error('Error fetching product', 500, $e->getMessage());
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product || $product->user_id !== auth()->id()) {
                return ApiResponse::error('Product not found', 404);
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

            return ApiResponse::success($product, 'Product updated');
        } catch (Exception $e) {
            return ApiResponse::error('Error updating product', 500, $e->getMessage());
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            if ($product->user_id !== auth()->id()) {
                return ApiResponse::error('Unauthorized', 403);
            }

            $product->categories()->detach();
            $product->clearMediaCollection('product_images');
            $product->delete();

            return ApiResponse::success(null, 'Product deleted');
        } catch (Exception $e) {
            return ApiResponse::error('Error deleting product', 500, $e->getMessage());
        }
    }
}
