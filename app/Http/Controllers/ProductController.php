<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    
    public function index(Request $request)
    {
        $query = Product::active();

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        // Get related products (same category, excluding current product)
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function byCategory($category)
    {
        $validCategories = ['femme', 'homme', 'exclusifs', 'nouveautes'];
        
        if (!in_array($category, $validCategories)) {
            abort(404);
        }

        $products = Product::where('category', $category)
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('collections.show', compact('products', 'category'));
    }

    public function featured()
    {
        $products = Product::featured()->active()->limit(8)->get();
        return $products;
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return view('search.index', ['products' => collect(), 'query' => '']);
        }

        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('search.index', compact('products', 'query'));
    }

    public function catalogue(Request $request)
    {
        $query = Product::active();

        // Filter by product type if provided
        if ($request->has('product_type') && $request->product_type) {
            $query->where('product_type', $request->product_type);
        }

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12);

        // Categories for filters
        $parfumCategories = [
            'niche' => 'Parfums de Niche',
            'exclusifs' => 'Collections Exclusives',
            'nouveautes' => 'Nouveautés',
        ];

        $cosmetiqueCategories = [
            'soins_visage' => 'Soins du Visage',
            'soins_corps' => 'Soins du Corps',
            'nouveautes_cosmetiques' => 'Nouveautés Cosmétiques',
        ];

        return view('products.catalogue', compact('products', 'parfumCategories', 'cosmetiqueCategories'));
    }

}