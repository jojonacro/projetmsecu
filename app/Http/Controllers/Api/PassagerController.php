<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Passager;
use App\Models\Document;

class PassagerController extends Controller
{
    public function store(Request $request)
    {
        // ✅ Validation complète
        $validated = $request->validate([
    // Informations personnelles
    'nom' => 'required|string',
    'prenom' => 'required|string',
    'sexe' => 'required|string',
    'date_naissance' => 'required|date',
    'lieu_naissance' => 'required|string',
    'email' => 'nullable|email',
    'profession' => 'required|string',
    'fonction' => 'nullable|string',
    'nationalite' => 'required|string',
    'pays_residence' => 'required|string',

    // Informations du document
    'type_document' => 'required|string',
    'numero_document' => 'required|string',
    'lieu_delivrance' => 'required|string',
    'date_delivrance' => 'required|date',
    'document_scanner' => 'nullable|file|mimes:jpg,png,pdf|max:2048',

    // Informations de provenance
    'pays_provenance' => 'required|string',
    'ville_provenance' => 'required|string',
    'adresse_provenance' => 'required|string',
    'contact_provenance' => 'required|string',

    // Informations de destination
    'pays_destination' => 'required|string',
    'ville_destination' => 'required|string',
    'adresse_destination' => 'required|string',
    'contact_destination' => 'required|string',

    // Informations sur le voyage
    'date_voyage' => 'required|date',
    'motif_voyage' => 'required|string',
    'duree_sejour' => 'required|string',
    'type_voyage' => 'required|string',
    'type_hebergement' => 'required|string',

    // Informations sur le vol
    'compagnie' => 'required|string',
    'numero_vol' => 'required|string',
]);


        // Enregistrement du passager
        $passager = Passager::create($validated);

        // Gestion du fichier 
        $filePath = null;
        if ($request->hasFile('document_scanner')) {
            $filePath = $request->file('document_scanner')->store('documents', 'public');
        }

        // Création du document 
        $document = Document::create([
    'passager_id' => $passager->id,
    'type_document' => $validated['type_document'],
    'numero_document' => $validated['numero_document'],
    'lieu_delivrance' => $validated['lieu_delivrance'],
    'date_delivrance' => $validated['date_delivrance'],
    'document_scanner' => $filePath ?? null,

    // Informations de provenance
    'pays_provenance' => $validated['pays_provenance'],
    'ville_provenance' => $validated['ville_provenance'],
    'adresse_provenance' => $validated['adresse_provenance'],
    'contact_provenance' => $validated['contact_provenance'],

    // Informations de destination
    'pays_destination' => $validated['pays_destination'],
    'ville_destination' => $validated['ville_destination'],
    'adresse_destination' => $validated['adresse_destination'],
    'contact_destination' => $validated['contact_destination'],

    // Informations du voyage
    'date_voyage' => $validated['date_voyage'],
    'motif_voyage' => $validated['motif_voyage'],
    'duree_sejour' => $validated['duree_sejour'],
    'type_voyage' => $validated['type_voyage'],
    'type_hebergement' => $validated['type_hebergement'],

    // Informations du vol
    'compagnie' => $validated['compagnie'],
    'numero_vol' => $validated['numero_vol'],
]);


        return response()->json([
            'message' => 'Enregistrement effectué avec succès',
            'passager' => $passager,
            'document' => $document
        ], 201);
    }

    // Recherche 
    public function search(Request $request)
    {
        $nom = $request->input('nom');
        $pays = $request->input('pays_residence');

        $results = Passager::where('nom', 'like', "%$nom%")
            ->where('pays_residence', 'like', "%$pays%")
            ->get();

        return response()->json($results);
    }
}