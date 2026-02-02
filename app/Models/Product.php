<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

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

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id');
    }

    public function productRequests()
    {
        return $this->hasMany(ProductRequest::class, 'product_id');
    }
}
