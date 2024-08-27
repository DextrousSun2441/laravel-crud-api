<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Definicion de rutas: Route define rutas, cada ruta tiene un verbo HTTP Asociado */


Route::get('/students', function (){
    return 'Obteniendo lista de estudiantes';
});
/*1° GET /Students:Esta ruta responde a las solicitudes 'GET' a la URL '/students. La funcion de devolución('callback')
devuelve de la cadena obteniendo lista de estudiantes */

Route::get('/students/{id}', function(){
    return 'obteniendo un estudiante';
});
/*2° GET /students/{id}:Esta ruta responde a solicitudes 'GET' a la URL '/students/{id}', donde {id} es un parametro de la ruta por
por ejemplo '/students/1 La funcion devuelve solo un estudiante */

Route::post('/students', function (){
    return 'Creando Estudiante';
});
/*3°POST/student: Esta ruta responde a solicitudes POST a la URL /students tipicamente utilizada para crear nuevos recursos en este caso 
un estudiante. La funcion devuelve Creando estudiantes */

Route::put('/students/{id}', function (){
    return 'Actualizando estudiante';
});
/*4° */
Route::delete('/students', function(){
    return 'Eliminando estudiante';
});