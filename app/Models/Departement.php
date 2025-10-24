<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'id_responsable',
        'id_responsable2'
    ];
    protected $primaryKey = 'id';
    
    public function id_responsable(): BelongsTo
    {
        return $this->belongsTo(Membres::class, 'id_responsable');
    }
    
    public function id_responsable2(): BelongsTo
    {
        return $this->belongsTo(Membres::class, 'id_responsable2');
    }
}
