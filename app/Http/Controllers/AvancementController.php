<?php

namespace App\Http\Controllers;

use App\Models\Avancement;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvancementController extends Controller
{
    public function index(Projet $projet)
    {
        $avancements = $projet->avancements()
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pourcentageGlobal = $avancements->avg('pourcentage') ?? 0;
        
        return view('admin.avancements.index', compact('projet', 'avancements', 'pourcentageGlobal'));
    }

    public function create(Projet $projet)
    {
        return view('admin.avancements.create', compact('projet'));
    }

    public function store(Request $request, Projet $projet)
    {
        $validated = $request->validate([
            'etape' => 'required|string|max:255',
            'description' => 'required|string',
            'pourcentage' => 'required|integer|min:0|max:100',
            'statut' => 'required|in:en cours,terminé,bloqué',
            'date_prevue' => 'nullable|date',
            'date_realisee' => 'nullable|date',
            'commentaires' => 'nullable|string',
            'fichiers' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:10240'
        ]);

        $validated['projet_id'] = $projet->id;

        // Handle file upload
        if ($request->hasFile('fichiers')) {
            $validated['fichiers'] = $request->file('fichiers')->store('avancements', 'public');
        }

        Avancement::create($validated);

        return redirect()->route('admin.avancements.index', $projet)
            ->with('success', 'Étape d\'avancement créée avec succès!');
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
            'commentaires' => 'nullable|string',
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
}