<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jarduera extends Model
{
    use HasFactory;

    protected $table = 'jarduerak';

    protected $fillable = [
        'izenburua',
        'deskribapena',
        'data',
        'edukiera',
        'prezioa',
        'kategoria_id',
        'begiralea_id',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'datetime',
            'prezioa' => 'decimal:2',
        ];
    }

    public function kategoria()
    {
        return $this->belongsTo(Kategoria::class);
    }

    public function begiralea()
    {
        // FK ez da "begiralea_id"-tik asmatzen modeloa User delako -> esplizitu
        return $this->belongsTo(User::class, 'begiralea_id');
    }

    public function bazkideak()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('ordainduta')
            ->withTimestamps();
    }
}
