<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Candidat
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade'); // Offre d'emploi
            $table->string('cv'); // Lien vers le fichier CV
            $table->text('lettre_motivation'); // Lettre de motivation
            $table->enum('statut', ['en attente', 'acceptée', 'refusée'])->default('en attente'); // Statut de la candidature
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidatures');
    }
};
