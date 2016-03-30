<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Devuleve la info de tres empleados en formato Json.
     * 
     * @return Json
     */
    public function returnThreeEmployees()
    {
        $employees = [];

        // la info del primero empleado
        $employees[] = [
            'id' => 3,
            'sub_cost_center_id' => 22,
            'position_id' => 52,
            'internal_code' => 73,
            'identification_number' => 9522507,
            'name' => 'FRANCISCO JAVIER',
            'lastname' => 'ALBARRACIN CASTRO',
            'status' => 'enabled',
            'email' => null,
            'city' => 'SOGAMOSO',
            'address' => 'CALLE 1 RA SUR 1-11',
            'phone' => '7700583- 3208080809',
            'created_at' => '2015-11-20 10:27:02',
            'updated_at' => '2015-11-20 10:27:02',
            'deleted_at' => null
        ];
        // info de segundo empleado
        $employees[] = [
            'id' => 5,
            'sub_cost_center_id' => 42,
            'position_id' => 52,
            'internal_code' => 328,
            'identification_number' => 74084587,
            'name' => 'MANUEL ANTONIO',
            'lastname' => 'ALFONSO PEREZ',
            'status' => 'enabled',
            'email' => 'manuelantonio.456@hotmail.com',
            'city' => 'SOGAMOSO',
            'address' => 'CALLE 38 NO 10A-62',
            'phone' => '3142686734',
            'created_at' => '2015-11-20 10:27:02',
            'updated_at' => '2015-11-20 10:27:02',
            'deleted_at' => null
        ];
        // info de tercer empleado
        $employees[] = [
            'id' => 7,
            'sub_cost_center_id' => 25,
            'position_id' => 54,
            'internal_code' => 264,
            'identification_number' => 52787990,
            'name' => 'ANGELA MAGALY',
            'lastname' => 'ALVAREZ LARA',
            'status' => 'enabled',
            'email' => null,
            'city' => 'BOGOTA',
            'address' => 'Calle 69A 75-29',
            'phone' => '2515412',
            'created_at' => '2015-11-20 10:27:02',
            'updated_at' => '2015-11-20 10:27:02',
            'deleted_at' => null,
        ];

        return json_encode($employees);
    }

    /**
     * Devuelve la información de dos empleados con formato incorrecto en el id,
     * se espera que los id sean de tipo entero.
     * 
     * @return Response
     */
    public function returnInvalidIdEmployeesData()
    {
        $employees = [];

        // la info del primero empleado
        $employees[] = [
            'id' => 'sd45',
            'sub_cost_center_id' => 22,
            'position_id' => 52,
            'internal_code' => 73,
            'identification_number' => 9522507,
            'name' => 'FRANCISCO JAVIER',
            'lastname' => 'ALBARRACIN CASTRO',
            'status' => 'enabled',
            'email' => null,
            'city' => 'SOGAMOSO',
            'address' => 'CALLE 1 RA SUR 1-11',
            'phone' => '7700583- 3208080809',
            'created_at' => '2015-11-20 10:27:02',
            'updated_at' => '2015-11-20 10:27:02',
            'deleted_at' => null
        ];
        // info de segundo empleado
        $employees[] = [
            'id' => 'sd695',
            'sub_cost_center_id' => 42,
            'position_id' => 52,
            'internal_code' => 328,
            'identification_number' => 74084587,
            'name' => 'MANUEL ANTONIO',
            'lastname' => 'ALFONSO PEREZ',
            'status' => 'enabled',
            'email' => 'manuelantonio.456@hotmail.com',
            'city' => 'SOGAMOSO',
            'address' => 'CALLE 38 NO 10A-62',
            'phone' => '3142686734',
            'created_at' => '2015-11-20 10:27:02',
            'updated_at' => '2015-11-20 10:27:02',
            'deleted_at' => null
        ];

        return json_encode($employees);
    }
}
