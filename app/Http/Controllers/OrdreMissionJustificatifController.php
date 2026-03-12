<?php

namespace App\Http\Controllers;

use App\Models\OrdreMission;
use App\Models\OrdreMissionJustificatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrdreMissionJustificatifController extends Controller
{
    /**
     * Upload un ou plusieurs justificatifs pour une mission
     */
    public function store(Request $request, OrdreMission $ordreMission)
    {
        $user = Auth::user();

        // Vérifier accès : l'employé ou un admin
        $isAdmin = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);
        if (!$isAdmin && $ordreMission->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'fichiers'          => 'required|array|min:1|max:5',
            'fichiers.*'        => [
                'required',
                'file',
                'max:5120',    // 5 Mo max par fichier
                'mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx',
            ],
            'type_doc'          => 'required|in:bon_transport,facture_hotel,facture_repas,ticket,autre',
            'description'       => 'nullable|string|max:255',
        ], [
            'fichiers.*.max'   => 'Chaque fichier ne doit pas dépasser 5 Mo.',
            'fichiers.*.mimes' => 'Formats autorisés : JPG, PNG, GIF, WEBP, PDF, DOC, DOCX.',
        ]);

        $uploaded = [];

        foreach ($request->file('fichiers') as $file) {
            // Générer un nom unique
            $extension  = $file->getClientOriginalExtension();
            $nomUnique  = 'justif_' . $ordreMission->id . '_' . Str::random(10) . '.' . $extension;
            $chemin     = $file->storeAs(
                'ordre_missions/justificatifs/' . $ordreMission->id,
                $nomUnique,
                'public'
            );

            $justif = OrdreMissionJustificatif::create([
                'ordre_mission_id' => $ordreMission->id,
                'user_id'          => $user->id,
                'nom_fichier'      => $file->getClientOriginalName(),
                'chemin'           => $chemin,
                'type_mime'        => $file->getMimeType(),
                'taille'           => $file->getSize(),
                'type_doc'         => $request->type_doc,
                'description'      => $request->description,
            ]);

            $uploaded[] = $justif;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($uploaded) . ' justificatif(s) ajouté(s).',
                'fichiers' => collect($uploaded)->map(fn($j) => [
                    'id'          => $j->id,
                    'nom_fichier' => $j->nom_fichier,
                    'url'         => $j->url,
                    'is_image'    => $j->is_image,
                    'taille'      => $j->taille_format,
                    'type_doc'    => $j->label_type,
                ]),
            ]);
        }

        return back()->with('success', count($uploaded) . ' justificatif(s) ajouté(s) avec succès.');
    }

    /**
     * Supprimer un justificatif
     */
    public function destroy(OrdreMissionJustificatif $justificatif)
    {
        $user    = Auth::user();
        $isAdmin = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);

        // Seul l'uploader ou un admin peut supprimer
        if (!$isAdmin && $justificatif->user_id !== $user->id) {
            abort(403);
        }

        // Supprimer le fichier physique
        Storage::disk('public')->delete($justificatif->chemin);
        $justificatif->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Justificatif supprimé.']);
        }

        return back()->with('success', 'Justificatif supprimé.');
    }

    /**
     * Télécharger / visualiser un justificatif
     */
    public function show(OrdreMissionJustificatif $justificatif)
    {
        $user    = Auth::user();
        $isAdmin = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);

        if (!$isAdmin && $justificatif->ordreMission->user_id !== $user->id) {
            abort(403);
        }

        $path = Storage::disk('public')->path($justificatif->chemin);

        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="' . $justificatif->nom_fichier . '"',
        ]);
    }
}