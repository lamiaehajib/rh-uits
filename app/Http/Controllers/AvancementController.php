<?php

namespace App\Http\Controllers;

use App\Models\Avancement;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AvancementCreatedNotification;
class AvancementController extends Controller
{
 public function index(Projet $projet)
{
    $avancements = $projet->avancements()
        ->orderBy('created_at', 'desc')
        ->get();
    
    
    // Nouveau code (somme) :
    $pourcentageGlobal = $avancements->sum('pourcentage');
    
    // On s'assure que le pourcentage ne dépasse pas 100%
    if ($pourcentageGlobal > 100) {
        $pourcentageGlobal = 100;
    }

    return view('admin.avancements.index', compact('projet', 'avancements', 'pourcentageGlobal'));
}
    public function create(Projet $projet)
    {
        return view('admin.avancements.create', compact('projet'));
    }

    public function store(Request $request, Projet $projet)
    {
        $validated = $request->validate([
            // ... (validation rules here)
            'etape' => 'required|string|max:255',
            'description' => 'required|string',
            'pourcentage' => 'required|integer|min:0|max:100',
            'statut' => 'required|in:en cours,terminé,bloqué',
            'date_prevue' => 'nullable|date',
            'date_realisee' => 'nullable|date',
            'fichiers' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:10240'
        ]);

        $validated['projet_id'] = $projet->id;

        // Handle file upload
        if ($request->hasFile('fichiers')) {
            $validated['fichiers'] = $request->file('fichiers')->store('avancements', 'public');
        }

        // 1. Création de l'avancement
        $avancement = Avancement::create($validated);
        
        // 2. Notification des clients
        // Charger la relation des utilisateurs (clients) associés au projet.
        // Assurez-vous que la relation 'users' dans le modèle Projet récupère les clients.
        $clients = $projet->users; 

        if ($clients->isNotEmpty()) {
            foreach ($clients as $client) {
                // Envoyer la notification à chaque client
                $client->notify(new AvancementCreatedNotification($avancement));
            }
        }

        return redirect()->route('admin.avancements.index', $projet)
            ->with('success', 'Étape d\'avancement créée avec succès! Les clients ont été notifiés.');
    }

    public function show(Projet $projet, Avancement $avancement)
    {
        return view('admin.avancements.show', compact('projet', 'avancement'));
    }

    public function edit(Projet $projet, Avancement $avancement)
    {
        return view('admin.avancements.edit', compact('projet', 'avancement'));
    }

    public function update(Request $request, Projet $projet, Avancement $avancement)
    {
        $validated = $request->validate([
            'etape' => 'required|string|max:255',
            'description' => 'required|string',
            'pourcentage' => 'required|integer|min:0|max:100',
            'statut' => 'required|in:en cours,terminé,bloqué',
            'date_prevue' => 'nullable|date',
            'date_realisee' => 'nullable|date',
            // 'commentaires' => 'nullable|string',
            'fichiers' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:10240'
        ]);

        // Handle file upload
        if ($request->hasFile('fichiers')) {
            // Delete old file if exists
            if ($avancement->fichiers) {
                Storage::disk('public')->delete($avancement->fichiers);
            }
            $validated['fichiers'] = $request->file('fichiers')->store('avancements', 'public');
        }

        $avancement->update($validated);

        return redirect()->route('admin.avancements.show', [$projet, $avancement])
            ->with('success', 'Étape d\'avancement mise à jour avec succès!');
    }

    public function destroy(Projet $projet, Avancement $avancement)
    {
        // Delete associated file
        if ($avancement->fichiers) {
            Storage::disk('public')->delete($avancement->fichiers);
        }

        $avancement->delete();

        return redirect()->route('admin.avancements.index', $projet)
            ->with('success', 'Étape d\'avancement supprimée avec succès!');
    }

    // Mettre à jour rapidement le pourcentage
    public function updatePourcentage(Request $request, Projet $projet, Avancement $avancement)
    {
        $validated = $request->validate([
            'pourcentage' => 'required|integer|min:0|max:100'
        ]);

        $avancement->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pourcentage mis à jour'
        ]);
    }

    // F-l-akhir dyal l-class AvancementController
public function downloadFile(Avancement $avancement)
{
    // Awal 7aja, kan-verifiw si le chemin du fichier existe
    if (empty($avancement->fichiers)) {
        abort(404, 'Fichier non trouvé.');
    }

    // Ensuite, kan-verifiw si le fichier existe vraiment sur le disque 'public'
    if (!Storage::disk('public')->exists($avancement->fichiers)) {
        abort(404, 'Fichier non trouvé.');
    }

    // Ila kano kolchi s7i7, kan-telechargiw l-fichier
    return Storage::disk('public')->download($avancement->fichiers);
}
 public function addCommentByClient(Request $request, Avancement $avancement)
    {
        // 1. Validate the input
        $validated = $request->validate([
            'commentaires' => 'required|string|max:1000',
        ]);

        // 2. Authorization: Ensure the client owns the project
     

        // 3. Update the comments field
        // We'll append the new comment to the existing ones to keep a history
        $currentComment = $avancement->commentaires;
        $newComment = $validated['commentaires'];
        $commentWithTimestamp = "[" . now()->format('Y-m-d H:i') . "]: " . $newComment;
        
        $avancement->commentaires = $currentComment ? $currentComment . "\n\n" . $commentWithTimestamp : $commentWithTimestamp;
        $avancement->save();

        return redirect()->back()->with('success', 'Votre commentaire a été ajouté avec succès!');
    }

    public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les Avancements li mamsou7in
    // KanLoadéw relation 'projet'
    $avancements = Avancement::onlyTrashed()
                  ->with('projet') 
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('admin.avancements.corbeille', compact('avancements'));
}

// N°2. Restauration d'un Avancement (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo l-Avancement b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $avancement = Avancement::withTrashed()->findOrFail($id);
    $avancement->restore();

    return redirect()->route('admin.avancements.corbeille_globale')->with('success', 'Avancement restauré avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo l-Avancement b ID men l'Corbeille (withTrashed) w kan3ayto 3la forceDelete()
    $avancement = Avancement::withTrashed()->findOrFail($id);
    
    // ⚠️ Mola7aḍa: Ila 3endek des fichiers flouked (b7al `fichiers`), khass tmass7hom hna.
    
    $avancement->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('admin.avancements.corbeille_globale')->with('success', 'Avancement supprimé définitivement!');
}
}