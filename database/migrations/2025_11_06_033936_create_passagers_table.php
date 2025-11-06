<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('passagers', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('prenom');
        $table->string('sexe');
        $table->date('date_naissance');
        $table->string('lieu_naissance');
        $table->string('email')->nullable();
        $table->string('profession');
        $table->string('fonction')->nullable();
        $table->string('nationalite');
        $table->string('pays_residence');
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
        Schema::dropIfExists('passagers');
    }
}
