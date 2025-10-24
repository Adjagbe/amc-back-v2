<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departement = Departement::with([
            'id_responsable:id,nom,prenom', 
            'id_responsable2:id,nom,prenom'
        ])
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json($departement);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $departement = Departement::create([
            'libelle'=>request('libelle'),
            'id_responsable' => request('id_responsable'),
            'id_responsable2' => request('id_responsable2')
        ]);
        return response()->json($departement);
    }

    /**
     * Display the specified resource.
     */
   public function show($id)
    {
        $departement = Departement::with([
            'id_responsable:id,nom,prenom',
            'id_responsable2:id,nom,prenom'
        ])->findOrFail($id);
    
        return response()->json($departement);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $departement = Departement::find($id);

        $departement->update([
            'libelle' => $request->libelle,
            'id_responsable' => $request->id_responsable,
            'id_responsable2' => $request->id_responsable2,
        ]);
        return response()->json(['message' => 'departement modifié']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $departement = Departement::find($id);

            if(!$departement){
                return response()->json(['message' => 'Département non trouvé'], 404);
            }

            $departementInfo = $departement->libelle;
            $departement->delete();

            return response()->json(['message' => 'Le département ' . $departementInfo . ' a été supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du département',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
