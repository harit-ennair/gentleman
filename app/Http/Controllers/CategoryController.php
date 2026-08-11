<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);
        Category::create($validated);

        return back()->with('success', 'Catégorie créée.');
    }

    public function show(Category $category): View
    {
        $category->load(['products' => fn ($query) => $query->where('is_active', true)]);

        return view('categories.show', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(Category::class)->ignore($category)],
            'description' => ['nullable', 'string'],
        ]);
        $category->update($validated);

        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_if($category->products()->exists(), 422, 'Une catégorie contenant des produits ne peut pas être supprimée.');
        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }

    public function products(Request $request, Category $category): View
    {
        $products = $category->products()
            ->where('is_active', true)
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->integer('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->integer('max_price')))
            ->paginate(12)
            ->withQueryString();

        return view('categories.products', compact('category', 'products'));
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
