<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';
    
    // The table has created_at and updated_at, so timestamps are true by default.

    protected $fillable = [
        'nom',
        'categorie_id',
        'fournisseur_id',
        'numero_lot',
        'date_peremption',
        'quantite_boites',
        'quantite_unites',
        'prix',
        'description',
    ];

    protected $casts = [
        'date_peremption' => 'date',
        'prix' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class, 'produit_id');
    }

    public function demandesProduits()
    {
        return $this->hasMany(DemandeProduit::class, 'produit_id');
    }
}
