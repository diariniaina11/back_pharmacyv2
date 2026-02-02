<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;

    protected $table = 'product_requests';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantite_demandee',
        'commentaire',
        'status',
        'date_creation',
    ];

    protected $casts = [
        'date_creation' => 'date',
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
