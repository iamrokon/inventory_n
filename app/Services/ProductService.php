<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ProductService
{
    public function getAllProducts(?string $search = null)
    {
        $cacheKey = 'products_' . md5($search ?? 'all');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search) {
            $query = Product::with('category');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            }

            return $query->latest()->get();
        });
    }

    public function getAllCategories()
    {
        return Cache::remember('all_categories', now()->addMinutes(30), function () {
            return Category::all();
        });
    }

    public function getProductForEdit(string $id)
    {
        return Cache::remember("product_edit_{$id}", now()->addMinutes(10), function () use ($id) {
            return Product::findOrFail($id);
        });
    }

    public function clearCache()
    {
        Cache::flush();
    }
}
