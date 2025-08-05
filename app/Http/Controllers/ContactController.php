<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function index()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'subject.required' => 'Le sujet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
        ]);

        // Créer le contact
        $contact = Contact::create($validated);

        // Envoyer une notification email (optionnel)
        try {
            // Ici vous pouvez ajouter l'envoi d'email
            // Mail::to('admin@heritageParfums.com')->send(new NewContactMessage($contact));
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création
            \Log::error('Erreur envoi email contact: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 
            'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.'
        );
    }
}
