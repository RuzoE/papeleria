<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'location', 'supplier']);

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($locationId = $request->input('location_id')) {
            $query->where('location_id', $locationId);
        }

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($stockStatus = $request->input('stock_status')) {
            if ($stockStatus === 'low') {
                $query->lowStock();
            } elseif ($stockStatus === 'out') {
                $query->outOfStock();
            } elseif ($stockStatus === 'normal') {
                $query->whereColumn('stock', '>', 'minimum_stock');
            }
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $locations = Location::orderBy('zone')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'locations', 'suppliers'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('zone')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('products.create', compact('categories', 'locations', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $nextId = (Product::max('id') ?? 0) + 1;
        $data['internal_code'] = 'PROD-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'location', 'supplier']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('zone')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'locations', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function toggleStatus(Product $product)
    {
        $newStatus = $product->status === ProductStatus::Active ? ProductStatus::Inactive : ProductStatus::Active;
        $product->update(['status' => $newStatus]);

        $msg = $newStatus === ProductStatus::Active ? 'Producto activado.' : 'Producto desactivado.';

        return back()->with('success', $msg);
    }
}
