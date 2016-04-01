<?php

namespace Employees;

use FunctionalTester;
use common\BaseTests;

class FilterEnabledCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTests();
        $base_test->employees();

        $I->amLoggedAs(BaseTests::$admin_user);

        // desactivo algunos empleados para el test
        \llstarscreamll\EmployeesModule\app\Models\Employee::whereNotIn('id', [1, 2])->update(['status' => 'disabled']);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba la funcionalidad de mostrar sólo los empleados activados.
     */
    public function showEnabledOnly(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('ver solo los empleados activados');

        // el index del módulo de empleados
        $I->amOnPage('/employee');
        $I->see('Empleados', 'h1');
        $I->seeElement('table');

        // veo a los empleados activados y desactivados
        $I->see('Empleado 1', 'tbody tr td');
        $I->see('Empleado 2', 'tbody tr td');
        $I->see('Empleado 3', 'tbody tr td'); // desactivado
        $I->see('Empleado 4', 'tbody tr td'); // desactivado

        // veo el link con el cual puedo filtrar y ver sólo los
        // empleados que hayan sido borrados
        $I->seeElement('a', ['id' => 'show-enabled']);
        $I->click('Mostrar Activados');

        $I->seeCurrentUrlEquals('/employee?show_only=enabled');

        // veo sólo los empleados activados
        $I->see('Empleado 1', 'tbody tr td a');
        $I->see('Empleado 2', 'tbody tr td a');

        // pero no veo los desactivados
        $I->dontSee('Empleado 3', 'tbody tr td');
        $I->dontSee('Empleado 4', 'tbody tr td');
    }
}
