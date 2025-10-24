<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fonctionnalite extends Model
{
    protected $fillable = [
        'libelle',
        'code'
    ];
}
