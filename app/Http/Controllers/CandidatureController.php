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
    

    
}
