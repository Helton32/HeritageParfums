<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\ContactController;
use App\Models\Product;

// Page d'accueil - Style Guerlain avec carousel
Route::get('/', function () {
    // Récupérer les produits en vedette pour le carousel
    $featuredProducts = Product::where('is_featured', true)
                              ->where('is_active', true)
                              ->take(6)
                              ->get();
    
    // Si pas de produits en vedette, prendre les premiers produits actifs
    if ($featuredProducts->isEmpty()) {
        $featuredProducts = Product::where('is_active', true)
                                  ->take(6)
                                  ->get();
    }
    
    return view('welcome', compact('featuredProducts'));
})->name('home');

// Route pour afficher un produit unique
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Pages statiques
Route::get('/heritage', function () {
    return view('pages.heritage');
})->name('heritage');

Route::get('/expedition', function () {
    return view('pages.expedition');
})->name('expedition');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Panier (simplifié pour un seul produit)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
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
    Route::get('/shipping/carrier-methods', [ShippingController::class, 'getCarrierMethods'])->name('shipping.carrier-methods');
    Route::get('/shipping/relay-points', [ShippingController::class, 'searchRelayPoints'])->name('shipping.relay-points');
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
            Route::get('/', [ShippingController::class, 'index'])->name('index');
            Route::get('/statistics', [ShippingController::class, 'statistics'])->name('statistics');
            Route::get('/{order}', [ShippingController::class, 'show'])->name('show');
            Route::post('/{order}/assign-carrier', [ShippingController::class, 'assignCarrier'])->name('assign-carrier');
            Route::post('/{order}/generate-label', [ShippingController::class, 'generateLabel'])->name('generate-label');
            Route::get('/{order}/download-label', [ShippingController::class, 'downloadLabel'])->name('download-label');
            Route::post('/{order}/mark-shipped', [ShippingController::class, 'markAsShipped'])->name('mark-shipped');
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


