<?php
namespace Employees;

use \FunctionalTester;

class ImportDataFromURLCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba que registros son ignorados en la importación de datos en el siguiente
     * escenario:
     * - Se intenta importar tres registros
     * - Los tres registros ya están presentes en la base de datos local
     * 
     * @param  FunctionalTester $I [description]
     * @return [type]              [description]
     */
    public function checkIgnoredData(FunctionalTester $I)
    {
        $I->am('Soy un administrador del sistema');
        $I->wantTo('comprobar que se ignoran los registros que ya estan presentes en DB');

        // creo los tres registros de prueba
        $this->haveThreeEmployees();

        // estoy en la ruta donde se realiza el proceso de importación
        $I->amOnRoute('employee.importDataForm');
        
        // pongo la url de donde voy a importar los datos
        $I->submitForm('#import-employees-data', [
            'url' => str_replace('localhost', env('APP_VIRTUAL_HOST', 'localhost'), route('test.get_three_employees'))
            ]);

        // debo ver la info de los registros procesados
        $I->see('Registros Creados', '#created_rows');
        $I->see('0', '#created_rows .label-success'); // ningún registro creado
        $I->see('3', '#created_rows .label-warning'); // tres registros ignorados
        $I->see('Registros Actualizados', '#updated_rows');
        $I->see('0', '#updated_rows .label-success'); // ningún registro actualizado, los registros son identicos
        $I->see('3', '#updated_rows .label-warning'); // tres registros ignorados
        $I->see('Registros Movidos a Papelera', '#deleted_rows');
        $I->see('0', '#deleted_rows .label-success'); // ningún registro eliminado
    }
    
    /**
     * Prueba el estádo de conexión a internet y de la url dada para descargar los datos
     * a importar. Para que la siguiente linea linea arroje el resultado correcto, debe
     * haber conexión a internet:
     * $I->see('Tu conexión a internet está bien.', '.alert-danger');
     * 
     * @param  FunctionalTester $I
     * @return void
     */
    public function checkURLSourceConnection(FunctionalTester $I)
    {
        $I->am('Soy un administrador del sistema');
        $I->wantTo('comprobar si tengo conexion a la URL de donde quiero importar los datos');

        // estoy en la ruta donde se realiza el proceso de importación
        $I->amOnRoute('employee.importDataForm');

        // la url a la que me conectaré
        $url = 'www.q123loskt345.example.trd.dsf'; // caracteres al azar :D
        
        // pongo la url de donde voy a importar los datos
        $I->submitForm('#import-employees-data', [
            'url' => $url
            ]);

        $I->see('Tu conexión a internet está bien.', '.alert-danger');
        $I->see('El host "'.$url.'" no responde.', '.alert-danger');
    }

    /**
     * Prueba la importación de datos en el siguiente escenario:
     * - importar tres registros de un servidor
     * - cuando en la base de datos local uno de los tres registros ya existe
     * 
     * @param  FunctionalTester $I
     * @return void
     */
    public function syncCreatedAndUpdatedData(FunctionalTester $I)
    {
        // el registro que debe estar e la base de datos local
        $this->haveOneEmployee(5);

        $I->am('Soy un administrador del sistema');
        $I->wantTo('importar 3 registros de empleados cuando uno ya existe, mediante URL');

        // estoy en la ruta donde se realiza el proceso de importación
        $I->amOnRoute('employee.importDataForm');
        
        // pongo la url de donde voy a importar los datos
        $I->submitForm('#import-employees-data', [
            'url' => str_replace('localhost', env('APP_VIRTUAL_HOST', 'localhost'), route('test.get_three_employees'))
            ]);

        // debo ver la info de los registros procesados
        $I->see('Registros Creados', '#created_rows');
        $I->see('2', '#created_rows .label-success');
        $I->see('Registros Actualizados', '#updated_rows');
        $I->see('1', '#updated_rows .label-success');
        $I->see('Registros Movidos a Papelera', '#deleted_rows');
        $I->see('0', '#deleted_rows .label-success');
    }

    /**
     * Prueba que los datos a sincronizar o importar tengan sus id de tipo numérico,
     * entero precisamente.
     * 
     * @param  FunctionalTester $I
     * @return void
     */
    public function dataToSyncMustHaveIntegerId(FunctionalTester $I)
    {
        $I->am('Soy un administrador del sistema');
        $I->wantTo('ver error al importar datos con id no numericos');

        // estoy en la ruta donde se realiza el proceso de importación
        $I->amOnRoute('employee.importDataForm');

        // pongo la url de donde voy a importar los datos con mal formato en id
        $I->submitForm('#import-employees-data', [
            'url' => str_replace('localhost', env('APP_VIRTUAL_HOST', 'localhost'), route('test.get_invalid_id_employees_data'))
        ]);

        // la ruta debe ser recargada
        $I->seeCurrentRouteIs('employee.importDataForm');
        // veo mensaje de error de los id
        $I->see('El id del empleado debe ser numérico.', '.alert-danger');
        // veo los datos procesados
        $I->see('Registros Creados', '#created_rows');
        $I->see('0', '#created_rows .label-success'); // los datos procesados
        $I->see('0', '#created_rows .label-warning'); // los datos ignorados
        $I->see('2', '#created_rows .label-danger'); // los datos con errores de validación
        $I->see('Registros Actualizados', '#updated_rows');
        $I->see('0', '#updated_rows .label-success');
        $I->see('Registros Movidos a Papelera', '#deleted_rows');
        $I->see('0', '#deleted_rows .label-success');
    }

    /**
     * Prueba la importación de datos en el siguiente escenario:
     * - importar tres registros de un servidor
     * - cuando en la base de datos local hay 1 registro que en el servidor no
     * 
     * @param  FunctionalTester $I [description]
     * @return [type]              [description]
     */
    public function syncCreatedAndDeletedData(FunctionalTester $I)
    {
        $I->am('Soy un administrador del sistema');
        $I->wantTo('importar 3 registros de empleados y borrar uno local, mediante URL');

        // creo el empleado de prueba
        $this->haveOneEmployee();

        // estoy en la ruta donde se realiza el proceso de importación
        $I->amOnRoute('employee.importDataForm');

        // pongo la url de donde voy a importar los datos
        $I->submitForm('#import-employees-data', [
            'url' => str_replace('localhost', env('APP_VIRTUAL_HOST', 'localhost'), route('test.get_three_employees'))
        ]);

        // debo ver la info de los registros procesados
        $I->see('Registros Creados', '#created_rows');
        $I->see('3', '#created_rows .label-success');
        $I->see('Registros Actualizados', '#updated_rows');
        $I->see('0', '#updated_rows .label-success');
        $I->see('Registros Movidos a Papelera', '#deleted_rows');
        $I->see('1', '#deleted_rows .label-success');
    }

    /**
     * Prueba el proceso de importación de datos del sistema a través de una URL
     * de un servidor remoto, se espera que los datos sean dados en formato Json.
     * Se prueba la importación en el siguiente escenario:
     * - importar tres empleados de un servidor
     * - cuando la base de datos local de empleados está vacía
     * 
     * @param  FunctionalTester $I
     * @return void
     */
    public function syncCreatedData(FunctionalTester $I)
    {
        $I->am('Soy un administrador del sistema');
        $I->wantTo('importar datos de empleados a DB local vacia, mediante URL');

        // estoy en la ruta donde se realiza el proceso de importación
        $I->amOnRoute('employee.importDataForm');
        
        // pongo la url de donde voy a importar los datos
        $I->submitForm('#import-employees-data', [
            'url' => str_replace('localhost', env('APP_VIRTUAL_HOST', 'localhost'), route('test.get_three_employees'))
            ]);

        // debo ver la info de los registros procesados
        $I->see('Registros Creados', '#created_rows');
        $I->see('3', '#created_rows .label-success');
        $I->see('Registros Actualizados', '#updated_rows');
        $I->see('0', '#updated_rows .label-success');
        $I->see('Registros Movidos a Papelera', '#deleted_rows');
        $I->see('0', '#deleted_rows .label-success');
    }

    /**
     * Prueba la sincronización de datos en el siguiente escenario:
     * - Importar tres registros nuevos
     * - Cuando uno de ellos está "softdeleted" en la base de datos local
     * 
     * @param  FunctionalTester $I [description]
     * @return [type]              [description]
     */
    public function syncCreatedDataWithSoftdeletes(FunctionalTester $I)
    {
        // el registro que debe estar e la base de datos local, en papelera (softdeleted)
        $this->haveOneEmployee(5);
        \App\Models\Employee::destroy(5);

        $I->am('Soy un administrador del sistema');
        $I->wantTo('importar datos de empleados cuando uno de ellos esta en papelera, mediante URL');

        // estoy en la ruta donde se realiza el proceso de importación
        $I->amOnRoute('employee.importDataForm');
        
        // pongo la url de donde voy a importar los datos
        $I->submitForm('#import-employees-data', [
            'url' => str_replace('localhost', env('APP_VIRTUAL_HOST', 'localhost'), route('test.get_three_employees'))
            ]);

        // los datos que están borrados o en papelera, deben sufrir actualizaciones, no creaciones
        $I->see('Registros Creados', '#created_rows');
        $I->see('2', '#created_rows .label-success'); // 2 correctos
        $I->see('0', '#created_rows .label-danger'); // 0 con errores de validación
        $I->see('Registros Actualizados', '#updated_rows');
        $I->see('1', '#updated_rows .label-success'); // el registro 'id' => 5, se restauró de la papelera
        $I->see('Registros Movidos a Papelera', '#deleted_rows');
        $I->see('0', '#deleted_rows .label-success');
    }

    /**
     * Creo tres empleados en la base de datos.
     * 
     * @return void
     */
    private function haveThreeEmployees()
    {
        $employees = [];

        $employees[] = [
            'id' => 3,
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

        $employees[] = [
            'id' => 5,
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

        $employees[] = [
            'id' => 7,
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

        // estos registros son iguales a los que se quiere importar
        \DB::table('employees')->insert($employees);
    }

    /**
     * Creo un empleado en la base de datos.
     * 
     * @return void
     */
    private function haveOneEmployee($id = 45)
    {
        \DB::table('employees')->insert([
            'id' => $id,
            'internal_code' => 654,
            'identification_number' => 321654987,
            'name' => 'ALEX',
            'lastname' => 'RUDINGUER',
            'status' => 'enabled',
            'email' => 'alex.654@example.com',
            'city' => 'NEW YORK', // este dato será actualizado
            'address' => 'CALLE 12 NO 34A-56', // este dato será actualizado
            'phone' => '123456789', // este dato será actualizado
            'created_at' => '2015-11-20 10:27:02',
            'updated_at' => '2015-11-20 10:27:02',
            'deleted_at' => null
            ]);
    }
}
