<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $products = $this->service->getAllProducts($request->input('search'));

        return view('index', ['products' => $products]);
    }

    public function create()
    {
        $categories = $this->service->getAllCategories();
        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->all());
        $this->service->clearCache();

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(string $id)
    {
        $product = $this->service->getProductForEdit($id);
        $categories = $this->service->getAllCategories();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        $this->service->clearCache();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        $this->service->clearCache();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
