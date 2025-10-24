<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membres extends Model
{
    use HasFactory;
    protected $fillable= [
        'nom',
        'prenom',
        'email',
        'portable',
        'adresse',
        'birthday',
        'portable2',
        'departements' // Nouveau champ pour les départements multiples
    ];
    protected $primaryKey = 'id';

    /**
     * Cast des attributs vers leurs types natifs
     * 'departements' sera automatiquement converti de JSON vers Array et vice versa
     */
    protected $casts = [
        'departements' => 'array', // Laravel convertit automatiquement JSON <-> Array
        'birthday' => 'date'
    ];

    /**
     * Récupère les détails complets des départements du membre
     * Retourne une collection d'objets Departement basée sur les IDs stockés en JSON
     * 
     * @return \Illuminate\Support\Collection Collection des départements
     */
    public function getDepartementsDetails()
    {
        // Vérifie si des départements sont assignés
        if (empty($this->departements) || !is_array($this->departements)) {
            return collect(); // Retourne une collection vide
        }

        // Récupère les objets Departement dont les IDs sont dans le tableau JSON
        return \App\Models\Departement::whereIn('id', $this->departements)->get();
    }

    /**
     * Mutateur pour le champ departements
     * Permet d'assigner facilement : $membre->departements = [1, 2, 3]
     * 
     * @param mixed $value Valeur à assigner (array d'IDs ou d'objets)
     */
    public function setDepartementsAttribute($value)
    {
        // Si on reçoit un array d'objets avec des IDs, extraire seulement les IDs
        if (is_array($value) && !empty($value) && isset($value[0]) && is_object($value[0])) {
            $value = collect($value)->pluck('id')->toArray();
        }
        
        // Laravel gère automatiquement la conversion Array -> JSON grâce au cast
        $this->attributes['departements'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Accesseur pour obtenir les IDs des départements
     * 
     * @return array Array des IDs des départements
     */
    public function getDepartementsIdsAttribute()
    {
        return $this->departements ?? [];
    }
}
