<?php

namespace Employees;

use FunctionalTester;
use common\BaseTests;

class ShowTrashedCest
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
     * Prueba que se pueda consultar los detalles de los empleados
     * aún cuando están borrados (softdeleted).
     */
    public function showTrashed(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('ver los detalles de un empelado que esta en la papelera');

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

        $I->click('Empleado 1', 'tbody tr td a.text-danger');
        // la página de la información del empleado
        $I->seeCurrentUrlEquals('/employee/1');
        $I->see('Detalles de Empleado');
        $I->seeElement('input', ['value' => 'Empleado 1']);
        $I->seeElement('input', ['value' => 'S1']);

        // veo un aviso que me informa que el registro se encuentra en la papelera
        $I->see('Este registro se encuentra en la papelera.', '.alert-warning');
        // no veo el botón de mover a la papelera, tampoco el de editar
        $I->dontSee('Mover a Papelera', 'button.btn-danger');
        $I->dontSee('Editar', 'a.btn-warning');
    }
}
