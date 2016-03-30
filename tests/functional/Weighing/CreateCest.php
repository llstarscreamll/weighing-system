<?php
namespace Weighing;

use \FunctionalTester;

/**
 * Realiza las pruebas del formulario de registro de pesaje, aunque para el registro de pesajes
 * se hace lectura de datos del puerto serial, aquí no se prueba esa funcionalidad por su
 * complejidad y variación cuando al pasar de un sistema operativo a otro, de todos modos si no
 * hay conexión con el puerto serial, el sistema sigue su corso y deja el campo vacío donde va
 * el dato obtenido del puerto serial, esto con el fin de que el usuario teclee el dato manualmente.
 */

class CreateCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba el proceso de registro de pesaje de producto
     * @param  FunctionalTester $I
     * @return void
     */
    public function registerWeighing(FunctionalTester $I)
    {
        $I->am('operario de la planta');
        $I->wantTo('crear registro de pesaje de producto');

        // empelado de prueba para el registro de peso
        $I->haveRecord('employees', [
            'id'                    => 1,
            'internal_code'         => '2362',
            'identification_number' => '123456',
            'name'                  => 'Jon',
            'lastname'              => 'Doe',
            'status'                => 'enabled',
            'email'                 => 'doejohn@example.com',
            'city'                  => 'Lima',
            'address'               => 'calle 14 No. 15 - 46',
            'phone'                 => '3126952522',
            'created_at'            => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s')
            ]);

        // voy a la ruta donde está el formulario de pesaje
        $I->amOnRoute('weighing.create');
        $I->see('Registar Peso', 'h1');
        // debo ver error en la conexión al puerto serial, aquí no tratamos de probar
        // conexión con el puerto
        $I->see('No se puede establecer conexión con el puerto serial.', '.alert-danger');

        $I->submitForm('#register-weighing', [
            'employee_id'   => 1,
            'weight'        => '77.5',
            'machine_id'    => 'Máquina 1',
            'product_id'    => 'Producto Test',
            ]);

        // soy redirigido a la ruta para imprimir el registro
        $I->seeCurrentRouteIs('weighing.printTicket');
        // veo mensaje de confirmación de éxito de la operación
        $I->see('Peso registrado correctamente.', '.alert-success');
        // veo la info a imprimir
        $I->see('Producto: Producto Test');
        $I->see('Máquina: Máquina 1');
        $I->see('Peso(kg): 77.5');
        $I->see('Código de Operario: 2362');
        $I->see('Código de Barras: 7702140007750', '.barcode');
    }
}
