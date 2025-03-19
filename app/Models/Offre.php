<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $table = 'offres'; // Nom de la table dans la base de données

    protected $fillable = [
        'recruteur_id',
        'titre',
        'description',
        'lieu',
        'type_contrat',
        'salaire',
        'date_expiration',
    ];

    // Relation : Une offre appartient à un recruteur (Utilisateur)
    public function recruteur()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }
}
