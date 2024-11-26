<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\studentController;


Route::get('/students', [studentController::class, 'index']);    
/*1° GET /Students:Esta ruta responde a las solicitudes 'GET' a la URL '/students. La funcion de devolución('callback')
devuelve de la cadena obteniendo lista de estudiantes */
Route::get('/students/{id}',[studentController::class,'show'])
    ->where('id', '[1-9][0-9]*');

Route::get('/students/{name}',[studentController::class,'showname'])
    ->where('name', '[a-zA-Z]+');

Route::get('/students/{email}', [studentController::class, 'showemail'])
    ->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}');

Route::post('/students', [studentController::class, 'store']);
/*3°POST/student: Esta ruta responde a solicitudes POST a la URL /students tipicamente utilizada para crear nuevos recursos en este caso 
un estudiante. La funcion devuelve Creando estudiantes */

Route::put('/students/{id}', [studentController::class, 'actualizar']);
/*4° PUT/students/: Esta ruta responde a solicitudes PUT a la URL /students/{id} y se utiliza para actualizar un recurso existente
estudiante. La funcion devuelve Actualizando estudiante */

Route::patch('/students/{id}', [studentController::class, 'actualizacionParcial']);

Route::delete('/students/{id}', [studentController::class, 'borrar']);
/*5° DELETE/students/{id}: Esta ruta responde a solicitudes DELETE a la URL /students/{id}, utilizada para eliminar un recurso existente 
(Estudiante).La funcion Devuelve Eliminando estudiante */

Route::get('students/find/total', [StudentController::class, 'total']);

Route::get('students/find/filter', [StudentController::class, 'filterByLanguageAndDate']);

?>