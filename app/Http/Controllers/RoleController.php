<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Roles::orderBy('libelle', 'asc')->get();
        return response()->json(['roles' => $roles], 200);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255|unique:roles,libelle',
            'fonctionnalites' => 'nullable|array',
            'fonctionnalites.*' => 'integer|exists:fonctionnalites,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Roles::create([
            'libelle' => $request->libelle,
            'fonctionnalites' => $request->fonctionnalites ?? []
        ]);

        return response()->json([
            'message' => 'Rôle créé avec succès',
            'role' => $role
        ], 201);
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle non trouvé'], 404);
        }

        return response()->json(['role' => $role], 200);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255|unique:roles,libelle,' . $id,
            'fonctionnalites' => 'nullable|array',
            'fonctionnalites.*' => 'integer|exists:fonctionnalites,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update([
            'libelle' => $request->libelle,
            'fonctionnalites' => $request->fonctionnalites ?? []
        ]);

        return response()->json([
            'message' => 'Rôle mis à jour avec succès',
            'role' => $role
        ], 200);
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle non trouvé'], 404);
        }

        // Vérifier si le rôle est utilisé par des utilisateurs
        $usersCount = User::where('role_id', $id)->count();
        
        if ($usersCount > 0) {
            return response()->json([
                'message' => "Ce rôle est utilisé par $usersCount utilisateur(s) et ne peut pas être supprimé."
            ], 400);
        }

        $role->delete();

        return response()->json([
            'message' => 'Rôle supprimé avec succès'
        ], 200);
    }

    /**
     * Assign fonctionnalites to a role.
     */
    public function assignFonctionnalites(Request $request, $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'fonctionnalites' => 'required|array',
            'fonctionnalites.*' => 'integer|exists:fonctionnalites,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update([
            'fonctionnalites' => $request->fonctionnalites
        ]);

        return response()->json([
            'message' => 'Permissions mises à jour avec succès',
            'role' => $role
        ], 200);
    }
}
