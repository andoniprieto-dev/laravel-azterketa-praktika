<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategoria extends Model
{
    protected $table = 'kategoriak';

    protected $fillable = [
        'izena',
        'deskribapena'
    ];

    protected function jarduerak(): HasMany{
        
        return $this->hasMany(Jarduera::class, 'kategoria_id');
    }
}
