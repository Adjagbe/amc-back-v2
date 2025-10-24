<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roles extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'libelle',
        'fonctionnalites'
    ];
    
    protected $casts = [
        'fonctionnalites' => 'array'
    ];
    
    protected $primaryKey = 'id';
}
