<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartialUpdateStudentRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\FilterStudentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
//use Symfony\Component\HttpFoundation\Response;





class studentController extends Controller
{
    //----------------------------Inicio de funcion Index

    public function index()
    {
        //Funcion Obtener todos los estudiantes
        try {
            // Intentar obtener todos los estudiantes
            $students = Student::all();

            // Verificar si no se encontraron estudiantes
            if ($students->isEmpty()) {
                return response()->json([
                    'message' => 'Estudiante no encontrado',
                    'status' => Response::HTTP_NOT_FOUND,
                ], Response::HTTP_NOT_FOUND);
            }

            // Si todo está bien, devolver los datos
            return response()->json([
                'students' => $students,
                'status' => Response::HTTP_OK, // 200 OK
            ], Response::HTTP_OK); //200 OK

        } catch (\Exception $e) {
            // Manejar cualquier excepción inesperada
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR, //500 INTERNAL SERVER ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR); //500 INTERNAL SERVER ERROR
        }
    }
    //----------------------------Fin de la funcion Index



    //----------------------------Inicio de la funcion store Agregar a un nuevo estudiante

    public function store(StoreStudentRequest $request)
    {
        DB::beginTransaction();


        try {
            $student = new Student();
            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->language = $request->language;
            $student->save();

            DB::commit();

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'student' => $student,
                'message' => 'Estudiante creado con éxito',
            ], Response::HTTP_CREATED); // 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Error en la validación de los datos',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error al crear el estudiante',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500 Internal Server Error
        }
    }

    //---------------------------- Fin de funcion store 


    //----------------------------Inicio de funcion show busca por ID de registro
    public function show($id)
    {
        try {
            $student = Student::find($id);

            if (!$student) {
                $data = [
                    'message' => 'Estudiante no encontrado',
                    'status' => Response::HTTP_NOT_FOUND,
                ];
                return response()->json($data, Response::HTTP_NOT_FOUND);
            }

            $data = [
                'message' => $student,
                'status' => Response::HTTP_OK, //200
            ];

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            // Manejo de cualquier otro error inesperado
            return response()->json([
                'message' => 'Ocurrió un error al buscar el estudiante',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR); //Error 500
        }
    }
    //----------------------------Fin de funcion show buscar por ID

    //----------------------------Inicio de funcion showname Búsqueda parcial

    public function showname($name)
    {
        try {
            // Buscar estudiantes cuyo nombre contenga el string proporcionado (búsqueda parcial)
            $students = Student::where('name', 'LIKE', "%{$name}%")->get();

            // Si no se encuentran estudiantes, devolver un mensaje de error
            if ($students->isEmpty()) {
                return response()->json([
                    'message' => 'Estudiante no encontrado',
                    'status' => Response::HTTP_NOT_FOUND, // 404 NOT FOUND
                ], Response::HTTP_NOT_FOUND);
            }

            // Si se encuentran estudiantes, devolver la lista
            return response()->json([
                'status' => Response::HTTP_OK, // 200 OK
                'students' => $students
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Manejar cualquier otra excepción
            return response()->json([
                'message' => 'Ocurrió un error inesperado',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR, // 500 INTERNAL SERVER ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    //----------------------------Fin de funcion showname búsqueda parcial

    //----------------------------Inicio de funcion email

    public function showemail($email)
    {
        try {
            $students = Student::where('email', 'LIKE', "%{$email}%")->get();

            if ($students->isEmpty()) {
                return response()->json([
                    'mesagge' => 'Estudiante no encontrado',
                    'status' => Response::HTTP_NOT_FOUND,

                ], Response::HTTP_NOT_FOUND);
            }
            // Si se encuentran estudiantes, devolver la lista
            return response()->json([
                'status' => Response::HTTP_OK, // 200 OK
                'students' => $students
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Manejar cualquier otra excepción
            return response()->json([
                'message' => 'Ocurrió un error inesperado',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR, // 500 INTERNAL SERVER ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    //----------------------------Inicio de funcion borrar
    public function borrar($id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => Response::HTTP_NOT_FOUND,
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND); // 404 ERROR NOT FOUND
        }

        $student->delete();

        $data = [
            'message' => 'Estudiante Eliminado',
            'status' => Response::HTTP_OK,
        ];

        return response()->json($data, Response::HTTP_OK);
    }


    //----------------------------Inicio de funcion actualizar
    public function actualizar(UpdateStudentRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $student = Student::find($id);
            if (!$student) {
                return response()->json([
                    'message' => 'Estudiante no encontrado',
                    'status' => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND); // 404 Not Found
            }

            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->language = $request->language;
            $student->save();

            DB::commit();

            return response()->json([
                'status' => Response::HTTP_OK,
                'student' => $student,
                'message' => 'Estudiante actualizado con éxito',
            ], Response::HTTP_OK); // 200 OK

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error al actualizar el estudiante',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500 Internal Server Error
        }
    }
    //----------------------------Fin de funcion actualizar


    public function actualizacionParcial(PartialUpdateStudentRequest $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ], 404);
        }

        // Aquí ya no necesitas validar porque UpdateStudentRequest lo hace por mi

        if ($request->has('name')) {
            $student->name = $request->name;
        }
        if ($request->has('email')) {
            $student->email = $request->email;
        }
        if ($request->has('phone')) {
            $student->phone = $request->phone;
        }
        if ($request->has('language')) {
            $student->language = $request->language;
        }

        $student->save();

        return response()->json([
            'message' => 'Estudiante actualizado con éxito',
            'student' => $student,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    // Método para contar el total de estudiantes
    public function total(): JsonResponse
    {
        // Obtener el total de estudiantes
        $total = Student::count();

        // Devolver la respuesta en formato JSON
        return response()->json(['total' => $total], 200);
    }

    
    public function filterByLanguageAndDate(FilterStudentRequest $request)
    {

    try {
        // Obtener los parámetros del request
        $language = $request->input('language');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Realizar la consulta en la base de datos
        $students = Student::where('language', $language)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Verificar si hay resultados
        if ($students->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron estudiantes para los criterios dados.',
                'status' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }

        // Devolver la lista de estudiantes
        return response()->json([
            'status'=> Response::HTTP_OK,
            'students' => $students,
        ], Response::HTTP_OK);

    }  catch (\Exception $e) {
        // Otros errores generales
        return response()->json([
            'message' => 'Ocurrió un error inesperado',
            'error' => $e->getMessage(),
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

}
