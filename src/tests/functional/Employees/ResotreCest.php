<?php

namespace Employees;

use FunctionalTester;
use common\BaseTests;

class ResotreCest
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
     * Prueba la función de restablecer un empleado de la papelera.
     */
    public function restoreOne(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('restablecer la info de un empelado que esta en la papelera');

        // el index del módulo de empleados
        $I->amOnPage('/employee');
        $I->see('Empleados', 'h1');
        $I->seeElement('table');

        // no puedo ver a los empleados en la papelera
        $I->dontSee('Empleado 1', 'tbody tr td');
        $I->dontSee('Empleado 2', 'tbody tr td');

        // voy a la página de los detalles del empleado borrado
        $I->amOnPage('/employee/1');
        $I->see('Detalles de Empleado');
        // veo un aviso que me informa que el registro se encuentra en la papelera
        $I->see('Este registro se encuentra en la papelera.');
        // no veo el botón de mover a la papelera, tampoco el de editar
        $I->dontSee('Mover a Papelera', 'button.btn-danger');
        $I->dontSee('Editar', 'a.btn-warning');
        // pero si veo el link para restablecer
        $I->see('Restablecer', 'a.btn-success');
        $I->click('Restablecer', 'a.btn-success');

        $I->seeCurrentUrlEquals('/employee');
        // veo el mensaje de confirmación en la operación
        $I->see('El empleado ha sido restaurado correctamente.', '.alert-success');
        $I->see('Empleado 1', 'tbody tr td');
    }
}
