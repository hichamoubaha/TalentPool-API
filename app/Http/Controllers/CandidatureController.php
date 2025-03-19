<?php
namespace App\Http\Controllers;

use App\Services\CandidatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller
{
    protected $candidatureService;

    public function __construct(CandidatureService $candidatureService)
    {
        $this->candidatureService = $candidatureService;
    }

    // POST : Postuler à une offre
    public function postuler(Request $request)
    {
        $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'cv' => 'required|file|mimes:pdf|max:2048',
            'lettre_motivation' => 'required|string'
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public');

        $data = [
            'user_id' => Auth::id(),
            'offre_id' => $request->offre_id,
            'cv' => $cvPath,
            'lettre_motivation' => $request->lettre_motivation,
        ];

        return response()->json([
            'message' => 'Candidature envoyée avec succès.',
            'candidature' => $this->candidatureService->postuler($data)
        ], 201);
    }

    // DELETE : Retirer une candidature
    public function retirer($id)
    {
        $userId = Auth::id();
        $deleted = $this->candidatureService->retirerCandidature($id, $userId);

        if ($deleted) {
            return response()->json(['message' => 'Candidature retirée avec succès.']);
        }

        return response()->json(['message' => 'Candidature non trouvée.'], 404);
    }

    // GET : Voir les candidatures reçues pour les offres d'un recruteur
    public function getCandidaturesRecruteur()
    {
        $recruteurId = Auth::id();
        return response()->json($this->candidatureService->getCandidaturesRecruteur($recruteurId));
    }
}
