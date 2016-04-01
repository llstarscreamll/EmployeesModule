<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTests;

class CreateCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTests;
        $base_test->employees();

        $I->amLoggedAs(BaseTests::$admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad para crear empleados
     */
    public function createEmployee(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('crear nuevo empleado');
        
        $I->amOnPage('/employee');
        $I->click('Crear Empleado', 'a');
        
        $I->seeCurrentUrlEquals('/employee/create');
        $I->see('Crear Empleado', 'h2');
        
        $I->submitForm('form', [
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'internal_code'         =>  '52659',
            'email'                 =>  'alan.silvestri@example.com',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
        ], 'Crear');
        
        $I->seeCurrentUrlEquals('/employee');
        $I->see('Empleado creado correctamente.', '.alert-success');
        
        $I->see('Silvestri Alan', 'tbody tr:first-child td');
        $I->see('52659', 'tbody tr:first-child td');
        $I->see('Proyecto Beteitiva - Subcentro 1', 'tbody tr:first-child td');
        $I->see('Minero', 'tbody tr:first-child td');
        $I->see('alan.silvestri@example.com', 'tbody tr:first-child td');
    }
}
