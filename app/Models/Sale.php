<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantite_vendue',
        'date_vente',
    ];

    protected $casts = [
        'date_vente' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
