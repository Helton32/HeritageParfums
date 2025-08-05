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
        try {
            // Validation des données avec messages personnalisés
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'short_description' => 'nullable|string|max:500',
                'price' => 'required|numeric|min:0.01',
                'category' => 'required|string|in:femme,homme,exclusifs,nouveautes',
                'type' => 'required|string|in:eau_de_parfum,eau_de_toilette,parfum,eau_fraiche',
                'size' => 'required|string|max:50',
                'stock' => 'required|integer|min:0',
                'badge' => 'nullable|string|max:50',
                'notes' => 'nullable|array',
                'notes.*' => 'string|max:100',
                'images' => 'nullable|array',
                'images.*' => 'url|max:500',
            ], [
                'name.required' => 'Le nom du produit est obligatoire.',
                'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
                'description.required' => 'La description complète est obligatoire.',
                'description.min' => 'La description doit contenir au moins 10 caractères.',
                'price.required' => 'Le prix est obligatoire.',
                'price.numeric' => 'Le prix doit être un nombre.',
                'price.min' => 'Le prix doit être supérieur à 0.',
                'category.required' => 'La catégorie est obligatoire.',
                'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
                'type.required' => 'Le type est obligatoire.',
                'type.in' => 'Le type sélectionné n\'est pas valide.',
                'size.required' => 'La taille est obligatoire.',
                'stock.required' => 'Le stock est obligatoire.',
                'stock.integer' => 'Le stock doit être un nombre entier.',
                'stock.min' => 'Le stock ne peut pas être négatif.',
                'images.*.url' => 'Chaque image doit être une URL valide.',
                'notes.*.max' => 'Chaque note ne peut pas dépasser 100 caractères.',
            ]);

            // Générer le slug unique
            $validated['slug'] = Str::slug($validated['name']);
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Gérer les checkboxes - importantes pour l'affichage sur l'accueil !
            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['is_featured'] = $request->has('is_featured') ? true : false;
            
            // Nettoyer les tableaux notes et images
            $validated['notes'] = array_filter($request->input('notes', []), function($note) {
                return !empty(trim($note));
            });
            
            $validated['images'] = array_filter($request->input('images', []), function($image) {
                return !empty(trim($image)) && filter_var($image, FILTER_VALIDATE_URL);
            });

            // Créer le produit
            $product = Product::create($validated);

            // Log pour debug
            \Log::info('Produit créé avec succès', [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'is_active' => $product->is_active,
                'is_featured' => $product->is_featured,
                'stock' => $product->stock
            ]);

            // Message de succès détaillé avec instructions claires
            if ($product->is_active) {
                if ($product->is_featured) {
                    $statusMessage = "✅ VISIBLE sur la page d'accueil EN PRIORITÉ (produit en vedette)";
                } else {
                    $statusMessage = "✅ VISIBLE sur la page d'accueil";
                }
            } else {
                $statusMessage = "⚠️ NON VISIBLE sur la page d'accueil (produit inactif - cochez 'Produit actif' pour l'afficher)";
            }

            $successMessage = "🎉 Produit '{$product->name}' créé avec succès !\n\n" .
                             "📊 Statut d'affichage: {$statusMessage}\n" .
                             "💰 Prix: {$product->formatted_price}\n" .
                             "📦 Stock: {$product->stock} unité(s)\n" .
                             "🔗 Slug: {$product->slug}\n\n" .
                             "👀 Pour voir le produit sur la page d'accueil, cliquez sur le bouton ci-dessous.";

            return redirect()->route('admin.products.index')
                            ->with('success', $successMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Erreurs de validation
            \Log::warning('Erreurs de validation lors de la création du produit', [
                'errors' => $e->errors(),
                'input' => $request->except(['images', 'notes']) // Éviter de logger les URLs
            ]);
            
            return redirect()->back()
                            ->withErrors($e->errors())
                            ->withInput()
                            ->with('error', '❌ Erreur de validation. Veuillez corriger les champs indiqués.');

        } catch (\Exception $e) {
            // Autres erreurs
            \Log::error('Erreur lors de la création du produit', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->except(['images', 'notes'])
            ]);

            return redirect()->back()
                            ->withInput()
                            ->with('error', '❌ Erreur technique lors de la création du produit: ' . $e->getMessage());
        }
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
        try {
            // Validation des données avec messages personnalisés (SANS les checkboxes boolean)
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'short_description' => 'nullable|string|max:500',
                'price' => 'required|numeric|min:0.01',
                'category' => 'required|string|in:femme,homme,exclusifs,nouveautes',
                'type' => 'required|string|in:eau_de_parfum,eau_de_toilette,parfum,eau_fraiche',
                'size' => 'required|string|max:50',
                'stock' => 'required|integer|min:0',
                'badge' => 'nullable|string|max:50',
                'notes' => 'nullable|array',
                'notes.*' => 'string|max:100',
                'images' => 'nullable|array',
                'images.*' => 'url|max:500',
            ], [
                'name.required' => 'Le nom du produit est obligatoire.',
                'description.required' => 'La description complète est obligatoire.',
                'description.min' => 'La description doit contenir au moins 10 caractères.',
                'price.required' => 'Le prix est obligatoire.',
                'price.min' => 'Le prix doit être supérieur à 0.',
                'category.required' => 'La catégorie est obligatoire.',
                'type.required' => 'Le type est obligatoire.',
                'size.required' => 'La taille est obligatoire.',
                'stock.required' => 'Le stock est obligatoire.',
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

            // Gérer les checkboxes correctement
            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['is_featured'] = $request->has('is_featured') ? true : false;
            
            // Nettoyer les tableaux notes et images
            $validated['notes'] = array_filter($request->input('notes', []), function($note) {
                return !empty(trim($note));
            });
            
            $validated['images'] = array_filter($request->input('images', []), function($image) {
                return !empty(trim($image)) && filter_var($image, FILTER_VALIDATE_URL);
            });

            // Mettre à jour le produit
            $product->update($validated);

            // Log pour debug
            \Log::info('Produit mis à jour avec succès', [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'is_active' => $product->is_active,
                'is_featured' => $product->is_featured,
                'stock' => $product->stock
            ]);

            // Message de succès détaillé
            if ($product->is_active) {
                if ($product->is_featured) {
                    $statusMessage = "✅ VISIBLE sur la page d'accueil EN PRIORITÉ (produit en vedette)";
                } else {
                    $statusMessage = "✅ VISIBLE sur la page d'accueil";
                }
            } else {
                $statusMessage = "⚠️ NON VISIBLE sur la page d'accueil (produit inactif - cochez 'Produit actif' pour l'afficher)";
            }

            $successMessage = "✅ Produit '{$product->name}' mis à jour avec succès !\n\n" .
                             "📊 Statut d'affichage: {$statusMessage}\n" .
                             "💰 Prix: {$product->formatted_price}\n" .
                             "📦 Stock: {$product->stock} unité(s)\n\n" .
                             "🔄 Les modifications sont maintenant visibles sur la page d'accueil.";

            return redirect()->route('admin.products.index')
                            ->with('success', $successMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Erreurs de validation
            \Log::warning('Erreurs de validation lors de la modification du produit', [
                'product_id' => $product->id,
                'errors' => $e->errors(),
                'input' => $request->except(['images', 'notes'])
            ]);
            
            return redirect()->back()
                            ->withErrors($e->errors())
                            ->withInput()
                            ->with('error', '❌ Erreur de validation. Veuillez corriger les champs indiqués.');

        } catch (\Exception $e) {
            // Autres erreurs
            \Log::error('Erreur lors de la modification du produit', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->except(['images', 'notes'])
            ]);

            return redirect()->back()
                            ->withInput()
                            ->with('error', '❌ Erreur technique lors de la modification: ' . $e->getMessage());
        }
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
