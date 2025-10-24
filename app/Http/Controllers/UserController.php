<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        $user = User::with('id_role:id,libelle')
        ->orderby('created_at', 'desc')
        ->get();
        return response()->json($user);
    }
    public function store(Request $request)
    {
        $user_data = User::where('pseudo', '=', $request->pseudo)->first();

        if($user_data){
            return response()->json(['message' => 'Cet adresse pseudo est dejà utilisé'], 501);
        }else{
            User::create([
                'name'=>request('name'),
                'email'=>request('email'),
                'password'=> Hash::make(request('password')),
                'last_name'=>request('last_name'),
                'pseudo'=>request('pseudo'),
                'departement'=>request('departement'),
                'poste'=>request('poste'),
                'id_role'=>request('id_role'),
                'portable'=>request('portable'),
            ]);

            return response()->json(['message'=>'Inscription reussi']);
        }
    }
        
    public function login(Request $request)
    {
        $user = User::select('id', 'name', 'email', 'pseudo', 'password', 'last_name', 'departement', 'poste', 'id_role', 'portable')
        ->with('id_role:id,libelle')
        ->where('pseudo', $request->pseudo)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Aucun accès pour ce compte '], 401);
        }

        // Génération d’un token JSON encodé
        // $tokenData = [
        //     'user_id' => $user->id,
        //     'last_activity' => now()->toDateTimeString()
        // ];
        // $token = Crypt::encryptString(json_encode($tokenData));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user_data = User::find($id);

            $user_data->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password ? Hash::make(request('password')) : $user_data->password,
                'last_name'=>$request->last_name,
                'pseudo'=>$request->pseudo,
                'departement'=>$request->departement,
                'poste'=>$request->poste,
                'id_role'=>$request->id_role,
                'portable'=>$request->portbale,
            ]);

            return response()->json(['message'=>'Utilisateur modifié']);
        
    }
}
