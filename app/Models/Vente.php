<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $table = 'ventes';
    public $timestamps = false; // The table has date_vente but not standard timestamps

    protected $fillable = [
        'produit_id',
        'utilisateur_id',
        'quantite_vendue',
        'date_vente',
    ];

    protected $casts = [
        'date_vente' => 'datetime',
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
