<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller {
    // 1 POST : Postuler à une offre
    public function postuler(Request $request) {
        $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'lettre_motivation' => 'required|string',
            'cv' => 'required|file|mimes:pdf|max:2048' // Max 2MB, PDF uniquement
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public'); // Stockage du fichier

        $candidature = Candidature::create([
            'user_id' => Auth::id(),
            'offre_id' => $request->offre_id,
            'lettre_motivation' => $request->lettre_motivation,
            'cv' => $cvPath,
            'statut' => 'en attente',
        ]);

        return response()->json(['message' => 'Candidature envoyée avec succès.', 'candidature' => $candidature], 201);
    }

    // 2 DELETE : Supprimer une candidature
    public function retirer($id) {
        $candidature = Candidature::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        Storage::delete('public/' . $candidature->cv); // Supprimer le fichier
        $candidature->delete();

        return response()->json(['message' => 'Candidature retirée avec succès.']);
    }

    // 3 GET : Liste des candidatures pour un recruteur
    public function listeRecruteur() {
        try {
            // Vérifier que l'utilisateur est bien authentifié
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }
    
            // Vérifier que l'utilisateur est un recruteur
            $candidatures = Candidature::whereHas('offre', function ($query) use ($user) {
                $query->where('user_id', $user->id); // Vérifie si l'offre appartient au recruteur
            })->with('user:id,name,email', 'offre:id,titre')->get();
    
            if ($candidatures->isEmpty()) {
                return response()->json(['message' => 'Aucune candidature trouvée pour vos offres.'], 404);
            }
    
            return response()->json($candidatures, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur.', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function mettreAJourStatut($id, Request $request)
{
    $request->validate([
        'statut' => 'required|in:en attente,acceptée,rejetée,en entretien',
    ]);

    // Find the candidature by ID
    $candidature = Candidature::findOrFail($id);

    // Check if the user is the recruiter for the offer related to the candidature
    if ($candidature->offre->recruteur_id !== Auth::id()) {
        return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cette candidature.'], 403);
    }

    // Update the status of the candidature
    $candidature->statut = $request->statut;
    $candidature->save();

    // Notify the candidate about the status change
    \Mail::to($candidature->user->email)->send(new CandidatureStatusUpdated($candidature));

    return response()->json(['message' => 'Statut de la candidature mis à jour.', 'candidature' => $candidature]);
}

public function statistiquesRecruteur() {
    try {
        // Vérifier que l'utilisateur est bien authentifié
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }

        // Vérifier que l'utilisateur est un recruteur
        $offres = $user->offres()->withCount('candidatures')->get(); // Get all offers with the count of applications for each offer

        return response()->json($offres, 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Erreur interne du serveur.', 'error' => $e->getMessage()], 500);
    }
}

    
}
