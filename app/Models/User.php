<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'badge_id',
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
