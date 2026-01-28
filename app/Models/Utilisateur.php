<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    use HasFactory;

    protected $table = 'utilisateurs';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'email',
    ];

    public function ventes()
    {
        return $this->hasMany(Vente::class, 'utilisateur_id');
    }

    public function demandesProduits()
    {
        return $this->hasMany(DemandeProduit::class, 'utilisateur_id');
    }
}
