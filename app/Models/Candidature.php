<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model {
    use HasFactory;

    protected $fillable = ['user_id', 'offre_id', 'lettre_motivation', 'cv', 'statut'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function offre() {
        return $this->belongsTo(Offre::class);
    }
}
