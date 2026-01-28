<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeProduit extends Model
{
    use HasFactory;

    protected $table = 'demandes_produits';
    public $timestamps = false; // The table has date_creation but not standard timestamps

    protected $fillable = [
        'produit_id',
        'utilisateur_id',
        'quantite_demandee',
        'commentaire',
        'status',
        'date_creation',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
}
