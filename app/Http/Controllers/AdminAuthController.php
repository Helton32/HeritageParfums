<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminAuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.shipping.index');
        }

        return view('admin.auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Chercher l'utilisateur par nom ou email
        $user = User::where('name', $username)
                   ->orWhere('email', $username)
                   ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->has('remember'));
            
            return redirect()->intended(route('admin.shipping.index'))
                           ->with('success', 'Connexion réussie ! Bienvenue dans l\'administration.');
        }

        return back()->withErrors([
            'credentials' => 'Identifiants incorrects.',
        ])->withInput($request->only('username'));
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
                       ->with('success', 'Déconnexion réussie.');
    }

    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        $stats = [
            'pending_orders' => \App\Models\Order::where('payment_status', 'paid')
                                                ->where('status', 'processing')
                                                ->count(),
            'shipped_today' => \App\Models\Order::where('status', 'shipped')
                                               ->whereDate('shipped_at', today())
                                               ->count(),
            'total_shipped' => \App\Models\Order::where('status', 'shipped')->count(),
            'total_orders' => \App\Models\Order::where('payment_status', 'paid')->count(),
            'total_revenue' => \App\Models\Order::where('payment_status', 'paid')
                                               ->sum('total_amount'),
            'monthly_revenue' => \App\Models\Order::where('payment_status', 'paid')
                                                 ->whereMonth('created_at', now()->month)
                                                 ->whereYear('created_at', now()->year)
                                                 ->sum('total_amount'),
            'bottles_sold' => \App\Models\Order::where('payment_status', 'paid')
                                              ->sum('quantity'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
