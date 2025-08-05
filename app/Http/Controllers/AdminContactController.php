<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true)->whereNull('replied_at');
            } elseif ($request->status === 'replied') {
                $query->whereNotNull('replied_at');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $contacts = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::where('is_read', false)->count(),
            'read' => Contact::where('is_read', true)->whereNull('replied_at')->count(),
            'replied' => Contact::whereNotNull('replied_at')->count(),
            'today' => Contact::whereDate('created_at', today())->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // Marquer comme lu automatiquement
        if (!$contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark contact as read
     */
    public function markAsRead(Contact $contact)
    {
        $contact->markAsRead();

        return redirect()->back()
                        ->with('success', 'Message marqué comme lu !');
    }

    /**
     * Mark contact as replied
     */
    public function markAsReplied(Request $request, Contact $contact)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $contact->markAsReplied($request->admin_notes);

        return redirect()->back()
                        ->with('success', 'Message marqué comme répondu !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
                        ->with('success', 'Message supprimé avec succès !');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_replied,delete',
            'contacts' => 'required|array',
            'contacts.*' => 'exists:contacts,id',
        ]);

        $contacts = Contact::whereIn('id', $request->contacts);

        switch ($request->action) {
            case 'mark_read':
                $contacts->update(['is_read' => true]);
                $message = 'Messages marqués comme lus !';
                break;

            case 'mark_replied':
                $contacts->update([
                    'is_read' => true,
                    'replied_at' => now(),
                ]);
                $message = 'Messages marqués comme répondus !';
                break;

            case 'delete':
                $count = $contacts->count();
                $contacts->delete();
                $message = "{$count} message(s) supprimé(s) !";
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export contacts to CSV
     */
    public function export(Request $request)
    {
        $query = Contact::query();

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true)->whereNull('replied_at');
            } elseif ($request->status === 'replied') {
                $query->whereNotNull('replied_at');
            }
        }

        $contacts = $query->orderBy('created_at', 'desc')->get();

        $filename = 'contacts_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Email',
                'Téléphone',
                'Sujet',
                'Message',
                'Statut',
                'Date de création',
                'Date de réponse',
                'Notes admin',
            ]);

            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->phone,
                    $contact->subject,
                    $contact->message,
                    $contact->status_label,
                    $contact->created_at->format('d/m/Y H:i'),
                    $contact->replied_at ? $contact->replied_at->format('d/m/Y H:i') : '',
                    $contact->admin_notes,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
