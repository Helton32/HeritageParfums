<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
        ];

        $categories = Product::distinct('category')->pluck('category', 'category');

        return view('admin.products.index', compact('products', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'femme' => 'Parfums Femme',
            'homme' => 'Parfums Homme',
            'exclusifs' => 'Collections Exclusives',
            'nouveautes' => 'Nouveautés',
        ];

        $types = [
            'eau_de_parfum' => 'Eau de Parfum',
            'eau_de_toilette' => 'Eau de Toilette',
            'parfum' => 'Parfum',
            'eau_fraiche' => 'Eau Fraîche',
        ];

        return view('admin.products.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'type' => 'required|string',
            'size' => 'required|string',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'notes' => 'nullable|array',
            'notes.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'url',
        ]);

        // Générer le slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // S'assurer que le slug est unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Gérer les valeurs par défaut
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['notes'] = $request->input('notes', []);
        $validated['images'] = $request->input('images', []);

        $product = Product::create($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produit créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = [
            'femme' => 'Parfums Femme',
            'homme' => 'Parfums Homme',
            'exclusifs' => 'Collections Exclusives',
            'nouveautes' => 'Nouveautés',
        ];

        $types = [
            'eau_de_parfum' => 'Eau de Parfum',
            'eau_de_toilette' => 'Eau de Toilette',
            'parfum' => 'Parfum',
            'eau_fraiche' => 'Eau Fraîche',
        ];

        return view('admin.products.edit', compact('product', 'categories', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'type' => 'required|string',
            'size' => 'required|string',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'notes' => 'nullable|array',
            'notes.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'url',
        ]);

        // Regénérer le slug si le nom a changé
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // S'assurer que le slug est unique (sauf pour le produit actuel)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Gérer les valeurs par défaut
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['notes'] = $request->input('notes', []);
        $validated['images'] = $request->input('images', []);

        $product->update($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Vérifier s'il y a des commandes liées
        if ($product->orderItems()->exists()) {
            return redirect()->route('admin.products.index')
                            ->with('error', 'Impossible de supprimer ce produit car il a des commandes associées.');
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produit supprimé avec succès !');
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activé' : 'désactivé';
        
        return redirect()->back()
                        ->with('success', "Produit {$status} avec succès !");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        $status = $product->is_featured ? 'mis en avant' : 'retiré de la mise en avant';
        
        return redirect()->back()
                        ->with('success', "Produit {$status} avec succès !");
    }

    /**
     * Duplicate a product
     */
    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copie)';
        $newProduct->slug = Str::slug($newProduct->name);
        
        // S'assurer que le slug est unique
        $originalSlug = $newProduct->slug;
        $counter = 1;
        while (Product::where('slug', $newProduct->slug)->exists()) {
            $newProduct->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $newProduct->is_active = false;
        $newProduct->save();

        return redirect()->route('admin.products.edit', $newProduct)
                        ->with('success', 'Produit dupliqué avec succès ! Vous pouvez maintenant le modifier.');
    }
}
