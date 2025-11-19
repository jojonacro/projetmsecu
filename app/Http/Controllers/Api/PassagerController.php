<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Passager;
use App\Models\Document;
use App\Models\Voyage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class PassagerController extends Controller
{
    public function store(Request $request)
    {
        // 1️ Validation complète des données
        $validated = $request->validate([
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

            'type_document' => 'required|string',
            'numero_document' => 'required|string',
            'lieu_delivrance' => 'required|string',
            'date_delivrance' => 'required|date',
            'document_scanner' => 'nullable|file|mimes:jpg,png,pdf|max:2048',

            'pays_provenance' => 'required|string',
            'ville_provenance' => 'required|string',
            'adresse_provenance' => 'required|string',
            'contact_provenance' => 'required|string',

            'pays_destination' => 'required|string',
            'ville_destination' => 'required|string',
            'adresse_destination' => 'required|string',
            'contact_destination' => 'required|string',

            'date_voyage' => 'required|date',
            'motif_voyage' => 'required|string',
            'duree_sejour' => 'required|string',
            'type_voyage' => 'required|string',
            'type_hebergement' => 'required|string',

            'compagnie' => 'required|string',
            'numero_vol' => 'required|string',
        ]);

        // 2️ Création du passager
        $passager = Passager::create($validated);

        // 3️ Gestion du document scanné
        $filePath = $request->hasFile('document_scanner') 
            ? $request->file('document_scanner')->store('documents', 'public') 
            : null;

        // 4️ Création du document associé
        $document = Document::create([
            'passager_id' => $passager->id,
            'type_document' => $validated['type_document'],
            'numero_document' => $validated['numero_document'],
            'lieu_delivrance' => $validated['lieu_delivrance'],
            'date_delivrance' => $validated['date_delivrance'],
            'document_scanner' => $filePath,
            'pays_provenance' => $validated['pays_provenance'],
            'ville_provenance' => $validated['ville_provenance'],
            'adresse_provenance' => $validated['adresse_provenance'],
            'contact_provenance' => $validated['contact_provenance'],
            'pays_destination' => $validated['pays_destination'],
            'ville_destination' => $validated['ville_destination'],
            'adresse_destination' => $validated['adresse_destination'],
            'contact_destination' => $validated['contact_destination'],
            'date_voyage' => $validated['date_voyage'],
            'motif_voyage' => $validated['motif_voyage'],
            'duree_sejour' => $validated['duree_sejour'],
            'type_voyage' => $validated['type_voyage'],
            'type_hebergement' => $validated['type_hebergement'],
            'compagnie' => $validated['compagnie'],
            'numero_vol' => $validated['numero_vol'],
        ]);

        // 5️ Création du voyage
        $voyage = Voyage::create([
            'passager_id' => $passager->id,
            'document_id' => $document->id,
        ]);

        

        // 6️ Génération du QR code 
    
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        $url = url('/api/voyages/' . $voyage->code_voyage);
        $filename = 'qrcodes/voyage_' . $voyage->id . '_' . time() . '.png';
        $png = QrCode::format('png')->size(400)->generate($url);
        Storage::disk('public')->put($filename, $png);

        $voyage->qr_path = $filename;
        $voyage->save();
        

        // 7️ Retour JSON
        return response()->json([
            'message' => 'Enregistrement effectué avec succès',
            'passager' => $passager,
            'document' => $document,
            'voyage' => $voyage,
            'qr_url' => asset('storage/' . $filename),
        ], 201);
        
    }

    //  Recherche avancée de passagers
    public function search(Request $request)
    {
        $query = $request->input('query');

        $passagers = Passager::whereHas('voyages', function($q) use ($query) {
            $q->where('code_voyage', 'LIKE', "%{$query}%")
              ->orWhereHas('document', function($doc) use ($query) {
                  $doc->where('numero_document', 'LIKE', "%{$query}%")
                      ->orWhere('date_delivrance', 'LIKE', "%{$query}%")
                      ->orWhere('date_voyage', 'LIKE', "%{$query}%");
              });
        })->get();

        return response()->json([
            'count' => $passagers->count(),
            'passagers' => $passagers,
        ]);
    }
// 3️⃣ Mise à jour complète d’un passager et son document
    public function update(Request $request, $id)
    {
        $passager = Passager::findOrFail($id);

        $validated = $request->validate([
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

            'type_document' => 'required|string',
            'numero_document' => 'required|string',
            'lieu_delivrance' => 'required|string',
            'date_delivrance' => 'required|date',
            'document_scanner' => 'nullable|file|mimes:jpg,png,pdf|max:2048',

            'pays_provenance' => 'required|string',
            'ville_provenance' => 'required|string',
            'adresse_provenance' => 'required|string',
            'contact_provenance' => 'required|string',

            'pays_destination' => 'required|string',
            'ville_destination' => 'required|string',
            'adresse_destination' => 'required|string',
            'contact_destination' => 'required|string',

            'date_voyage' => 'required|date',
            'motif_voyage' => 'required|string',
            'duree_sejour' => 'required|string',
            'type_voyage' => 'required|string',
            'type_hebergement' => 'required|string',

            'compagnie' => 'required|string',
            'numero_vol' => 'required|string',
        ]);

        $passager->update($validated);

        $document = $passager->document;
        $filePath = $request->hasFile('document_scanner') 
            ? $request->file('document_scanner')->store('documents', 'public') 
            : $document->document_scanner;

        $document->update(array_merge($validated, [
            'document_scanner' => $filePath,
        ]));

        return response()->json([
            'message' => 'Mise à jour effectuée avec succès',
            'passager' => $passager,
            'document' => $document,
        ]);
    }
}
