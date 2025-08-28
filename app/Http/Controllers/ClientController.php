<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Utilisez Str pour générer un mot de passe temporaire
use Illuminate\Validation\Rules\Password;
use App\Notifications\UserCreatedNotification; // Importez la notification

class ClientController extends Controller
{
    /**
     * Store a newly created client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Règle de validation
        $rules = [
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tele' => 'required|string|max:20',
            'adresse' => 'required|string|max:500',
            'type_client' => 'required|in:particulier,entreprise',
            // Si le type est 'entreprise', le champ 'societe_name' est obligatoire
            'societe_name' => 'required_if:type_client,entreprise|nullable|string|max:255',
        ];

        // Validation des données
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Erreur de validation. Veuillez vérifier les champs.');
        }

        // Création d'un mot de passe temporaire
        $temporaryPassword = Str::random(10); 
        
        // Création du nouvel utilisateur/client
        try {
            $client = User::create([
                'name' => $request->input('nom_complet'),
                'email' => $request->input('email'),
                'tele' => $request->input('tele'),
                'adresse' => $request->input('adresse'),
                'type_client' => $request->input('type_client'),
                'societe_name' => $request->input('societe_name'),
                'password' => Hash::make($temporaryPassword), // Utilisation du mot de passe temporaire
                'poste' => 'Client', // Valeur par défaut pour un client
                'is_active' => true,
                'repos' => 'Non applicable', // Valeur par défaut
            ]);
            
            // On attribue le rôle 'Client' au nouvel utilisateur
            $client->assignRole('Client');

            // Envoi de la notification par e-mail
            // On suppose que la notification UserCreatedNotification existe et est configurée
            $client->notify(new UserCreatedNotification($client->email, $temporaryPassword, url('/')));

            Log::info("Nouveau client créé", ['client_id' => $client->id]);

            return redirect()->back()->with('success', 'Le client a été ajouté avec succès et un email de bienvenue lui a été envoyé.');
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'ajout d'un client: " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du client.');
        }
    }
}
