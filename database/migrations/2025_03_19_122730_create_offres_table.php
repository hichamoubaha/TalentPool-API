<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruteur_id')->constrained('users')->onDelete('cascade'); // L'offre appartient à un recruteur
            $table->string('titre');
            $table->text('description');
            $table->string('lieu');
            $table->string('type_contrat'); // CDI, CDD, Stage...
            $table->decimal('salaire', 10, 2)->nullable();
            $table->date('date_expiration'); // Date limite pour postuler
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offres');
    }
};
