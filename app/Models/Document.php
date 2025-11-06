<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

   protected $fillable = [
    // Informations du document
    'passager_id',
    'type_document',
    'numero_document',
    'lieu_delivrance',
    'date_delivrance',
    'document_scanner',

    // Informations sur la provenance
    'pays_provenance',
    'ville_provenance',
    'adresse_provenance',
    'contact_provenance',

    // Informations sur la destination
    'pays_destination',
    'ville_destination',
    'adresse_destination',
    'contact_destination',

    // Informations sur le séjour
    'date_voyage',
    'motif_voyage',
    'duree_sejour',
    'type_voyage',
    'type_hebergement',

    // Informations sur le vol
    'compagnie',
    'numero_vol',
];

    public function passager()
    {
        return $this->belongsTo(Passager::class);
    }
}
