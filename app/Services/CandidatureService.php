<?php

namespace App\Services;

use App\Repositories\CandidatureRepository;

class CandidatureService
{
    protected $candidatureRepository;

    public function __construct(CandidatureRepository $candidatureRepository)
    {
        $this->candidatureRepository = $candidatureRepository;
    }

    public function postuler($data)
    {
        return $this->candidatureRepository->postuler($data);
    }

    public function retirerCandidature($id, $userId)
    {
        return $this->candidatureRepository->retirerCandidature($id, $userId);
    }

    public function getCandidaturesRecruteur($recruteurId)
    {
        return $this->candidatureRepository->getCandidaturesRecruteur($recruteurId);
    }
}
