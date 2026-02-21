<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'badge_id',
        'updated_at',
    ];

    protected $hidden = [
        'password',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'user_id');
    }

    public function productRequests()
    {
        return $this->hasMany(ProductRequest::class, 'user_id');
    }
}
