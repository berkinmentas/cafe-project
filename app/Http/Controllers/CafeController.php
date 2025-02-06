<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    public function show($id){
        $cafe = Admin::query()->where('id', $id)->first();
        $categories = Category::query()->where('user_id', $id)->get();

        return view('cafe.show', [
            'cafe' => $cafe,
            'categories' => $categories,
        ]);
    }

    public function menu($id, $category)
    {
        $category = Category::query()->where('id', $category)->first();

        $productCategories = ProductCategory::query()
            ->whereHas('product', function($q) use ($id) {
                $q->where('user_id', $id);
            })
            ->where('category_id', $category->id)
            ->whereHas('product', function($q) use ($id) {
                $q->where('user_id', $id);
            })
            ->get();

        $products = $productCategories->pluck('product');
        return view('cafe.menu', [
          'products' => $products,
            'category' => $category,
        ]);
    }
}
