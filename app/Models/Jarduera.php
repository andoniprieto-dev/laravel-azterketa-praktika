<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jarduera extends Model
{
    protected $table = 'jarduerak';

    protected $fillable = [
        'izena',
        'deskribapena',
        'date',
        'edukiera',
        'prezioa',
        'kategoria_id',
        'begiralea_id'
    ];

    protected function jarduerak(): BelongsTo{
        
        return $this->belongsTo(Kategoria::class, 'jarduera_id');
    }
    
    public function begiralea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'begiralea_id');
    }
    public function bazkideak(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'jarduera_user', 'jarduera_id', 'user_id')
            ->withPivot('ordainduta')
            ->withTimestamps();
    }
}
