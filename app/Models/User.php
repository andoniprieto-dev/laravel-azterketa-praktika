<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefonoa',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function gidatutakoJarduerak()
    {
        return $this->hasMany(Jarduera::class, 'begiralea_id');
    }

    public function jarduerak()
    {
        return $this->belongsToMany(Jarduera::class)
            ->withPivot('ordainduta')
            ->withTimestamps();
    }
}
