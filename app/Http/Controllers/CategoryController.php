<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent')->withCount('children');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->boolean('status'));
        }

        $categories = $query->orderBy('name')->paginate(15)->withQueryString();
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('categories.index', compact('categories', 'parentCategories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        Category::where('parent_id', $category->id)->update(['parent_id' => null]);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['status' => !$category->status]);
        $msg = $category->status ? 'Categoría activada.' : 'Categoría desactivada.';

        return back()->with('success', $msg);
    }
}
