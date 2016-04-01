<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTests;

class GetAllEmployeesCest
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
     * Pruebo la vista que me devuelve todos los empleados creados
     */
    public function getAllEmployeesView(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('ver la lista de los empleados del sistema');
        
        $I->amOnPage('/employee');
        
        $I->see('Empleados', 'h1');
        $I->seeElement('table');
        
        // veo en la tabla los empleados registrados
        $I->see('S1 Empleado 1', 'tbody tr:first-child td a');
        $I->see('Empleado1@example.com', 'tbody tr:first-child td');
        $I->see('2', 'tbody tr:first-child td');
        $I->see('Minero', 'tbody tr:first-child td');
        $I->see('Proyecto Beteitiva - Subcentro 1', 'tbody tr:first-child td');
        $I->see('S2 Empleado 2', 'tbody tr td a');
        $I->see('S1 Empleado 3', 'tbody tr td a');
        $I->see('S2 Empleado 4', 'tbody tr td a');
    }
}
