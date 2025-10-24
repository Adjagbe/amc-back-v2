<?php

namespace App\Http\Controllers;

use App\Models\Fonctionnalite;
use Illuminate\Http\Request;

class FonctionnaliteController extends Controller
{
    /**
     * Affiche toutes les fonctionnalités
     */
    public function index()
    {
        $fonctionnalites = Fonctionnalite::orderBy('libelle', 'asc')->get();
        return response()->json(['fonctionnalites' => $fonctionnalites]);
    }

    /**
     * Pagination des fonctionnalités avec recherche
     */
    public function paginate(Request $request)
    {
        $take = $request->input('take', 10);
        $search = $request->input('search', '');

        $query = Fonctionnalite::query();

        // Recherche sur libelle et code
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('libelle', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        $fonctionnalites = $query->orderBy('libelle', 'asc')->paginate($take);

        return response()->json($fonctionnalites);
    }

    /**
     * Enregistre une nouvelle fonctionnalité
     */
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:fonctionnalites,code'
        ]);

        $fonctionnalite = Fonctionnalite::create($request->all());
        
        return response()->json([
            'message' => 'Fonctionnalité créée avec succès',
            'fonctionnalite' => $fonctionnalite
        ], 201);
    }

    /**
     * Affiche une fonctionnalité spécifique
     */
    public function show($id)
    {
        $fonctionnalite = Fonctionnalite::find($id);
        
        if (!$fonctionnalite) {
            return response()->json(['message' => 'Fonctionnalité non trouvée'], 404);
        }
        
        return response()->json($fonctionnalite);
    }

    /**
     * Met à jour une fonctionnalité
     */
    public function update(Request $request, $id)
    {
        $fonctionnalite = Fonctionnalite::find($id);
        
        if (!$fonctionnalite) {
            return response()->json(['message' => 'Fonctionnalité non trouvée'], 404);
        }

        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:fonctionnalites,code,' . $id
        ]);

        $fonctionnalite->update($request->all());
        
        return response()->json([
            'message' => 'Fonctionnalité mise à jour avec succès',
            'fonctionnalite' => $fonctionnalite
        ]);
    }

    /**
     * Supprime une fonctionnalité
     */
    public function destroy($id)
    {
        $fonctionnalite = Fonctionnalite::find($id);
        
        if (!$fonctionnalite) {
            return response()->json(['message' => 'Fonctionnalité non trouvée'], 404);
        }

        // Vérifier si la fonctionnalité est utilisée dans des rôles
        $rolesUtilisant = \App\Models\Roles::whereJsonContains('fonctionnalites', $id)->count();
        
        if ($rolesUtilisant > 0) {
            return response()->json([
                'message' => 'Cette fonctionnalité est utilisée par ' . $rolesUtilisant . ' rôle(s). Veuillez d\'abord la retirer de ces rôles.'
            ], 400);
        }

        $fonctionnalite->delete();
        
        return response()->json(['message' => 'Fonctionnalité supprimée avec succès']);
    }
}
