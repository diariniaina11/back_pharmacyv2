<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseurs';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'telephone',
        'email',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'fournisseur_id');
    }
}
