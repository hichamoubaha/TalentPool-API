<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'offre_id', 
        'cv', 
        'lettre_motivation', 
        'statut'
    ];

    // Relation avec l'utilisateur (candidat)
    public function candidat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation avec l'offre d'emploi
    public function offre()
    {
        return $this->belongsTo(Offre::class, 'offre_id');
    }
}
