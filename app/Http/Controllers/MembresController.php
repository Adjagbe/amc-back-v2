<?php

namespace App\Http\Controllers;

use App\Models\Membres;
use Illuminate\Http\Request;

class MembresController extends Controller
{
    /**
     * Display a listing of the resource.
     * Liste tous les membres avec leurs départements
     */
    public function index()
    {
        $membres = Membres::orderBy('created_at', 'desc')->get();
        
        // Pour chaque membre, ajouter les détails des départements
        $membres->each(function ($membre) {
            $membre->departementsDetails = $membre->getDepartementsDetails();
        });
        
        return response()->json($membres);
    }


    public function paginateMembres(Request $request){

        $take = $request->input('per_page', 10);
        $data = Membres::select('id', 'nom', 'prenom', 'portable', 'adresse')

        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                ->orWhere('prenom', 'like', "%$search%")
                ->orWhere('portable', 'like', "%$search%")
                ->orWhere('adresse', 'like', "%$search%");
            });
        })
        ->orderBy('nom', 'asc');
        $pagination = $data->paginate($take);

        return response()->json($pagination);
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
     * Création d'un nouveau membre avec validation et support des départements multiples
     */
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255', 
            'email' => 'nullable|email|max:255',
            'portable' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'birthday' => 'nullable|date',
            'portable2' => 'nullable|string|max:20',
            'departements' => 'nullable|array', // Array d'IDs de départements
            'departements.*' => 'integer|exists:departements,id' // Chaque ID doit exister dans la table departements
        ]);

        // Création du membre avec toutes les données validées
        $membres = Membres::create($validatedData);
        
        // Récupération des détails des départements pour la réponse
        $membres->departementsDetails = $membres->getDepartementsDetails();

        $membreInfo = $membres->nom . ' ' . $membres->prenom;
        
        return response()->json([
            'message' => 'Le membre ' . $membreInfo . ' a été créé avec succès',
            'data' => $membres
        ], 201);
    }
    

    /**
     * Display the specified resource.
     * Affichage d'un membre avec ses départements
     */
    public function show($membres)
    {
        $data = Membres::find($membres);
        
        if (!$data) {
            return response()->json(['message' => 'Membre non trouvé'], 404);
        }
        
        // Ajout des détails des départements
        $data->departementsDetails = $data->getDepartementsDetails();
        
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membres $membres)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Mise à jour d'un membre avec support des départements multiples
     */
    public function update(Request $request, $id)
    {
        $membres = Membres::find($id);
        
        if (!$membres) {
            return response()->json(['message' => 'Membre non trouvé'], 404);
        }

        // Validation des données de mise à jour
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'portable' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'birthday' => 'nullable|date',
            'portable2' => 'nullable|string|max:20',
            'departements' => 'nullable|array', // Array d'IDs de départements
            'departements.*' => 'integer|exists:departements,id'
        ]);

        // Mise à jour avec toutes les données validées
        $membres->update($validatedData);
        
        // Récupération des détails des départements pour la réponse
        $membres->departementsDetails = $membres->getDepartementsDetails();

        $membreInfo = $membres->nom . ' ' . $membres->prenom;

        return response()->json([
            'message' => 'Le membre ' . $membreInfo . ' a été modifié avec succès',
            'data' => $membres
        ]);
    }

    /**
     * Compte le nombre de membres pour un département spécifique
     */
    public function countByDepartement($departementId)
    {
        $count = Membres::whereJsonContains('departements', (int)$departementId)->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Récupère le comptage de membres pour tous les départements
     */
    public function countAllDepartements()
    {
        // Récupérer tous les départements existants
        $departements = \App\Models\Departement::all();
        $counts = [];

        foreach ($departements as $dept) {
            $counts[$dept->id] = Membres::whereJsonContains('departements', (int)$dept->id)->count();
        }

        return response()->json($counts);
    }

    /**
     * Récupère les membres d'un département avec pagination
     */
    public function paginateMembresByDepartement(Request $request, $departementId)
    {
        $take = $request->input('per_page', 8);
        
        $query = Membres::whereJsonContains('departements', (int)$departementId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%$search%")
                      ->orWhere('prenom', 'like', "%$search%")
                      ->orWhere('portable', 'like', "%$search%")
                      ->orWhere('adresse', 'like', "%$search%");
                });
            })
            ->orderBy('nom', 'asc');

        $pagination = $query->paginate($take);

        // Ajouter les détails des départements pour chaque membre
        $pagination->getCollection()->each(function ($membre) {
            $membre->departementsDetails = $membre->getDepartementsDetails();
        });

        return response()->json($pagination);
    }

    /**
     * Récupère tous les membres d'un département spécifique
     */
    public function getMembresByDepartement($departementId)
    {
        $membres = Membres::whereJsonContains('departements', (int)$departementId)
            ->orderBy('nom', 'asc')
            ->get();

        // Ajouter les détails des départements pour chaque membre
        $membres->each(function ($membre) {
            $membre->departementsDetails = $membre->getDepartementsDetails();
        });

        return response()->json([
            'membres' => $membres,
            'count' => $membres->count()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $membre = Membres::find($id);
            
            if (!$membre) {
                return response()->json([
                    'message' => 'Membre non trouvé'
                ], 404);
            }

            // Sauvegarder les informations pour le log
            $membreInfo = $membre->nom . ' ' . $membre->prenom;
            
            // Supprimer le membre
            $membre->delete();

            return response()->json([
                'message' => "Le membre {$membreInfo} a été supprimé avec succès"
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du membre',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
