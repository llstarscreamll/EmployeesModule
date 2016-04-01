<?php

namespace Employees;

use FunctionalTester;
use common\BaseTests;

class FilterTrashedCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTests();
        $base_test->employees();

        $I->amLoggedAs(BaseTests::$admin_user);

        // borro algunos empleados (softdelete)
        \llstarscreamll\EmployeesModule\app\Models\Employee::destroy([1, 2]);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba la funcionalidad de ver sólo los empleados en la papelera.
     */
    public function seeTrashed(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('ver los empelados que estan en la papelera');

        // el index del módulo de empleados
        $I->amOnPage('/employee');
        $I->see('Empleados', 'h1');
        $I->seeElement('table');

        // no puedo ver a los empleados en la papelera
        $I->dontSee('Empleado 1', 'tbody tr td');
        $I->dontSee('Empleado 2', 'tbody tr td');

        // veo el link con el cual puedo filtrar y ver sólo los
        // empleados que hayan sido borrados
        $I->seeElement('a', ['id' => 'show-trashed']);
        $I->click('Mostrar Registros en Papelera');

        $I->seeCurrentUrlEquals('/employee?show_only=trashed');

        // veo los registros borrados
        $I->see('Empleado 1', 'tbody tr td a.text-danger');
        $I->see('Empleado 2', 'tbody tr td a.text-danger');

        // no veo algunos botones de la "barra de acciones" o barra de
        // herramientas, pues con el registro borrado no puedo hacer mucho
        $I->dontSee('Activar Empleado(s)', '.btn-default');
        $I->dontSee('Desactivar Empleado(s)', '.btn-default');
        $I->dontSee('Mover Empleados a Papelera', '.btn-default');

        // pero no veo los demás
        $I->dontSee('Empleado 3', 'tbody tr td');
        $I->dontSee('Empleado 4', 'tbody tr td');
    }
}
