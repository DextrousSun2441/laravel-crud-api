<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/students', function (){
    return 'Students List';
});

Route::post('/students', function (){
    return 'Creadno Estudiantes';
});
/*El id es el numero de indice que se tendra que actualizar */
Route::put('/students/{id}', function (){
    return 'Actualizando estudiante';
});