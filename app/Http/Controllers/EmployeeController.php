<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Muestra el formulario de importación de datos de empleados.
     *
     * @return \Illuminate\Http\Response
     */
    public function importDataForm(Request $request)
    {
        return view('employees.importDataForm');
    }

    /**
     * Procesa el formulario de importación de datos.
     * 
     * @return \Illuminate\Http\Response
     */
    public function postImportDataForm(Request $request)
    {
        ////////////////////////////////////////////////////////////////////////
        // TO-DO:                                                             //
        // - Validar con formRequest los datos que aquí están llegando.       //
        // - Comprobar que los datos devueltos estén en formato Json, si no,  //
        // se le debe notificar al usuario.                                   //
        ////////////////////////////////////////////////////////////////////////
        
        // compruebo el estado de la conexión a internet
        $connectionHelper = new \App\Helpers\InternetConnectionHelper;
        if (! $connectionHelper->testInternetConnection($request->get('url'))){
            return redirect()->back()->with('error', $connectionHelper->messages);
        }

        // abro la url donde se encuentran los registros
        $json = file_get_contents($request->get('url'));
        
        // los datos están en Json, les convierto a array
        $data = json_decode($json, true);
        
        // si la url remota devolvió por lo menos un registro
        if (count($data) > 0){
            // para obtener el tiempo de procesamiento de los datos
            $startTime = microtime(true);

            // proceso los datos
            $employee = new \App\Models\Employee;
            $processedData = $employee->importData($data);
        }

        // me redirijo a la ruta del formulario de pesaje
        return redirect()
            ->back()
            ->with('processedData', $processedData)
            ->with('statics', ['totalProcessTime' => microtime(true) - $startTime, 'totalDBRows' => $employee->count('id')])
            ->with('error', $employee->validationMessages);
    }
}
