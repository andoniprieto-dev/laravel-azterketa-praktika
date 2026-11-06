<?php

use Illuminate\Support\Facades\Route;

// Ruta guztiek /api aurrizkia dute automatikoki.
// Proba: GET http://127.0.0.1:8000/api/ping

Route::get('/ping', function () {
    return ['mezua' => 'API martxan dago'];
});

// Hemen idatzi zure rutak (ikusi ENUNTZIATUA.md)
