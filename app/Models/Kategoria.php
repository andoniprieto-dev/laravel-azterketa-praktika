<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategoria extends Model
{
    protected $table = 'kategoriak';

    protected $fillable = ['izena', 'deskribapena'];

    public function jarduerak()
    {
        return $this->hasMany(Jarduera::class);
    }
}
