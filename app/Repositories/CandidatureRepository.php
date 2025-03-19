<?php

namespace App\Repositories;

use App\Models\Candidature;

class CandidatureRepository
{
    // Ajouter une candidature
    public function postuler($data)
    {
        return Candidature::create($data);
    }

    // Supprimer une candidature
    public function retirerCandidature($id, $userId)
    {
        return Candidature::where('id', $id)->where('user_id', $userId)->delete();
    }

    // Récupérer les candidatures associées aux offres d'un recruteur
    public function getCandidaturesRecruteur($recruteurId)
    {
        return Candidature::whereHas('offre', function ($query) use ($recruteurId) {
            $query->where('user_id', $recruteurId);
        })->get();
    }
}
