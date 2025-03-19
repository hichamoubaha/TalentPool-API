<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    //  1. Créer une offre (Réservé aux recruteurs)
    public function creerOffre(Request $request)
    {
        // Vérifier si l'utilisateur est un recruteur
        if (Auth::user()->role !== 'recruteur') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        // Validation des données
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'lieu' => 'required|string',
            'type_contrat' => 'required|string',
            'salaire' => 'nullable|numeric',
            'date_expiration' => 'required|date',
        ]);

        // Création de l'offre
        $offre = Offre::create([
            'recruteur_id' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'lieu' => $request->lieu,
            'type_contrat' => $request->type_contrat,
            'salaire' => $request->salaire,
            'date_expiration' => $request->date_expiration,
        ]);

        return response()->json($offre, 201);
    }

    //  2. Récupérer la liste des offres
    public function listeOffres()
    {
        return response()->json(Offre::with('recruteur:id,name')->get(), 200);
    }

    //  3. Voir les détails d'une offre
    public function voirOffre($id)
    {
        $offre = Offre::with('recruteur:id,name')->findOrFail($id);
        return response()->json($offre, 200);
    }
}
