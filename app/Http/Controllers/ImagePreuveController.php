<?php

namespace App\Http\Controllers;

use App\Models\ImagePreuve;
use App\Models\User;
use App\Notifications\MagepreuveCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagePreuveController extends Controller
{


    function __construct()                                 

    {
         $this->middleware('permission:image_preuve-list|image_preuve-create|image_preuve-edit|image_preuve-delete', ['only' => ['index','show']]);

         $this->middleware('permission:image_preuve-create', ['only' => ['create','store']]);

         $this->middleware('permission:image_preuve-edit', ['only' => ['edit','update']]);

         $this->middleware('permission:image_preuve-delete', ['only' => ['destroy']]);

         $this->middleware('permission:image_preuve-show', ['only' => ['download']]);

    }
    // Afficher la liste des images preuves
    public function index(Request $request)
{
    $user = auth()->user();
    $query = ImagePreuve::with('user');

    // Filtrer par mot-clé si une recherche est effectuée
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('titre', 'like', '%' . $search . '%') // Exemple : recherche par titre
              ->orWhere('description', 'like', '%' . $search . '%'); // Exemple : recherche par description
        });
    }

    // Gestion des rôles
    if ($user->hasRole('Sup_Admin') ||  $user->hasRole('Custom_Admin')) {
        $imagePreuves = $query->paginate(10);
    } else {
        $imagePreuves = $query->where('iduser', $user->id)->paginate(10);
    }

    return view('image_preuve.index', compact('imagePreuves'));
}

    // Afficher le formulaire pour créer une nouvelle image preuve
    public function create()
{
    $users = User::all(); // Récupérer tous les utilisateurs
    return view('image_preuve.create', compact('users'));
}


    // Enregistrer une nouvelle image preuve
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,mkv,avi,mov|max:51200',
            'date' => 'required|date',
            'iduser' => auth()->user()->hasRole('Sup_Admin') ? 'required|exists:users,id' : '',
        ]);
    
        $mediaPath = $request->file('media')->store('public/media');
    
        $imagePreuve = ImagePreuve::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'media' => $mediaPath,
            'date' => $request->date,
            'iduser' => auth()->user()->id,
        ]);
    
        // إرسال إشعار للمسؤولين
        $admins = User::role(['Sup_Admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new MagepreuveCreatedNotification($imagePreuve));
        }
    
        return redirect()->route('image_preuve.index')->with('success', 'Image preuve créée avec succès.');
    }
    
    


    public function download($id)
{
    $imagePreuve = ImagePreuve::findOrFail($id);
    $mediaPath = $imagePreuve->media;

    if (Storage::exists($mediaPath)) {
        return Storage::download($mediaPath);
    }

    return redirect()->back()->with('error', 'Le fichier n\'existe pas.');
}


    // Afficher une image preuve spécifique
    public function show($id)
{
    $user = auth()->user();
    $imagePreuve = ImagePreuve::findOrFail($id);

    // Check if the user is an admin or if the image belongs to the user
    if ($user->hasRole('Sup_Admin') || $user->hasRole('ADMIN2') || $user->hasRole('Custom_Admin') || $imagePreuve->iduser == $user->id) {
        return view('image_preuve.show', compact('imagePreuve'));
    } else {
        return redirect()->route('image_preuve.index')->with('error', 'Vous n\'êtes pas autorisé à voir cette image.');
    }
}


    // Afficher le formulaire pour modifier une image preuve
    public function edit($id)
{
    $imagePreuve = ImagePreuve::findOrFail($id);
    $users = User::all(); // Récupérer tous les utilisateurs
    return view('image_preuve.edit', compact('imagePreuve', 'users'));
}


    // Mettre à jour une image preuve
    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mkv,avi,mov|max:51200', // التحقق من أنواع الملفات
            'date' => 'required|date_format:Y-m-d',
        ]);
    
        $imagePreuve = ImagePreuve::findOrFail($id);
    
        // 
        if ($request->hasFile('media')) {
            // 
            Storage::delete($imagePreuve->media);
    
            // 
            $mediaPath = $request->file('media')->store('public/media');
            $imagePreuve->media = $mediaPath;
        }
    
        //
        $imagePreuve->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'date' => $request->date,
        ]);
    
        return redirect()->route('image_preuve.index')->with('success', 'Image preuve mise à jour avec succès.');
    }
    

    // Supprimer une image preuve
    public function destroy($id)
    {
        $imagePreuve = ImagePreuve::findOrFail($id);
        Storage::delete($imagePreuve->media);
        $imagePreuve->delete();

        return redirect()->route('image_preuve.index')->with('success', 'Image preuve supprimée avec succès.');
    }
}
