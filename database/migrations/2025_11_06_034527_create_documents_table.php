<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
{
    Schema::create('documents', function (Blueprint $table) {
        $table->id();

        // Relation avec le passager
        $table->foreignId('passager_id')->constrained()->onDelete('cascade');

        // Informations sur le document
        $table->string('type_document');
        $table->string('numero_document');
        $table->string('lieu_delivrance');
        $table->date('date_delivrance');
        $table->string('document_scanner')->nullable();

        // --- Informations sur le voyage ---

        // Provenance
        $table->string('pays_provenance');
        $table->string('ville_provenance');
        $table->string('adresse_provenance');
        $table->string('contact_provenance');

        // Destination
        $table->string('pays_destination');
        $table->string('ville_destination');
        $table->string('adresse_destination');
        $table->string('contact_destination');

        // Séjour
        $table->date('date_voyage');
        $table->string('motif_voyage');
        $table->string('duree_sejour');
        $table->string('type_voyage');
        $table->string('type_hebergement');

        // Vol
        $table->string('compagnie');
        $table->string('numero_vol');

        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
