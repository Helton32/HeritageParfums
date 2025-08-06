<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\ContactController;
use App\Models\Product;

// Page d'accueil - Style Guerlain avec carousel
Route::get('/', function () {
    // NOUVELLE LOGIQUE: Afficher TOUS les produits actifs
    // 1. D'abord les produits en vedette ET actifs (priorité)
    $featuredProducts = Product::where('is_featured', true)
                              ->where('is_active', true)
                              ->orderBy('created_at', 'desc')
                              ->get();
    
    // 2. Puis les produits actifs mais NON en vedette (pour compléter)
    $activeProducts = Product::where('is_active', true)
                            ->where('is_featured', false)
                            ->orderBy('created_at', 'desc')
                            ->get();
    
    // 3. Combiner : vedettes en premier, puis actifs, limité à 6 produits
    $allActiveProducts = $featuredProducts->concat($activeProducts)->take(6);
    
    // 4. Si aucun produit actif, afficher un produit par défaut pour éviter une page vide
    if ($allActiveProducts->isEmpty()) {
        $allActiveProducts = Product::orderBy('created_at', 'desc')->take(1)->get();
    }
    
    return view('welcome', compact('allActiveProducts'));
})->name('home');

// Route pour afficher un produit unique
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Route pour le catalogue complet
Route::get('/catalogue', [ProductController::class, 'catalogue'])->name('catalogue');

// Pages statiques
Route::get('/heritage', function () {
    return view('pages.heritage');
})->name('heritage');


Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Panier (simplifié pour un seul produit)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Raccourci pour le panier
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// Paiement
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/create-session', [PaymentController::class, 'createSession'])->name('create-session');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');
});

// API Routes pour AJAX
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');
    Route::post('/shipping/options', [App\Http\Controllers\ShippingApiController::class, 'getShippingOptions'])->name('shipping.options');
    Route::post('/shipping/relay-points', [App\Http\Controllers\ShippingApiController::class, 'searchRelayPoints'])->name('shipping.relay-points');
    Route::post('/shipping/calculate', [App\Http\Controllers\ShippingApiController::class, 'calculateShippingPrice'])->name('shipping.calculate');
});

// Démonstration du système d'expédition
Route::get('/demo/shipping', function () {
    return view('shipping.demo');
})->name('demo.shipping');

// Démonstration de la transformation Guerlain
Route::get('/demo/guerlain', function () {
    return view('guerlain-demo');
})->name('demo.guerlain');

// Authentification Admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Routes publiques (non protégées)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    
    // Routes protégées par authentification
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Gestion des produits
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('index');
            Route::get('/create', [AdminProductController::class, 'create'])->name('create');
            Route::post('/', [AdminProductController::class, 'store'])->name('store');
            Route::get('/{product}', [AdminProductController::class, 'show'])->name('show');
            Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
            Route::patch('/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');
            Route::patch('/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{product}/duplicate', [AdminProductController::class, 'duplicate'])->name('duplicate');
        });
        
        // Gestion des messages de contact
        Route::prefix('contacts')->name('contacts.')->group(function () {
            Route::get('/', [AdminContactController::class, 'index'])->name('index');
            Route::get('/{contact}', [AdminContactController::class, 'show'])->name('show');
            Route::patch('/{contact}/mark-as-read', [AdminContactController::class, 'markAsRead'])->name('mark-as-read');
            Route::patch('/{contact}/mark-as-replied', [AdminContactController::class, 'markAsReplied'])->name('mark-as-replied');
            Route::delete('/{contact}', [AdminContactController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [AdminContactController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/export/csv', [AdminContactController::class, 'export'])->name('export');
        });
        
        // Gestion des expéditions
        Route::prefix('shipping')->name('shipping.')->group(function () {
            Route::get('/', [App\Http\Controllers\ShippingController::class, 'index'])->name('index');
            Route::get('/statistics', [App\Http\Controllers\ShippingController::class, 'statistics'])->name('statistics');
            Route::get('/{order}', [App\Http\Controllers\ShippingController::class, 'show'])->name('show');
            Route::post('/{order}/assign-carrier', [App\Http\Controllers\ShippingController::class, 'assignCarrier'])->name('assign-carrier');
            Route::post('/{order}/generate-label', [App\Http\Controllers\ShippingController::class, 'generateLabel'])->name('generate-label');
            Route::get('/{order}/download-label', [App\Http\Controllers\ShippingController::class, 'downloadLabel'])->name('download-label');
            Route::post('/{order}/mark-shipped', [App\Http\Controllers\ShippingController::class, 'markAsShipped'])->name('mark-shipped');
        });
    });
});

// Redirection de /admin vers le dashboard
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

// Redirections pour les anciennes URLs (SEO)
Route::get('/collections/{any}', function () {
    return redirect('/')->with('info', 'Découvrez Éternelle Rose, notre parfum signature unique.');
})->where('any', '.*');

Route::get('/products/{any}', function () {
    return redirect('/')->with('info', 'Découvrez Éternelle Rose, notre parfum signature unique.');
})->where('any', '.*');

Route::get('/search', function () {
    return redirect('/')->with('info', 'Découvrez Éternelle Rose, notre parfum signature unique.');
});


