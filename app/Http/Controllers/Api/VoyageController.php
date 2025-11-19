<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voyage;

class VoyageController extends Controller
{
    /**
     * Affiche un voyage via son code voyage.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $voyage = Voyage::where('code_voyage', $code)->firstOrFail();

        return response()->json([
            'voyage' => $voyage,
            'passager' => $voyage->passager,
            'document' => $voyage->document,
            'qr_url' => $voyage->qr_path ? asset('storage/' . $voyage->qr_path) : null,
        ]);
    }
}
