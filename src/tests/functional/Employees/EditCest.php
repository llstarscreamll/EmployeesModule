<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTests;

class EditCest
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
     * Pruebo la funciónalidad de editar un empleado
     */
    public function edit(FunctionalTester $I)
    {
        // el empleado a editar, tendrá id 9 porque ya hay 8 creados
        \llstarscreamll\EmployeesModule\app\Models\Employee::create([
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'internal_code'         =>  '52659',
            'email'                 =>  'alan.silvestri@example.com',
            'city'                  =>  'Nobsa',
            'address'               =>  'Proyecto carrera No.11 - 36',
            'phone'                 =>  '3115318813',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
            ]);
        
        // otro cargo para actualizar, ya está creado el cargo "Minero"
        \llstarscreamll\EmployeesModule\app\Models\Position::create([
            'name'  =>  'Operario'
            ]);
        
        $I->am('admin de recurso humano');
        $I->wantTo('ediat la info de un empleado');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->click('Silvestri Alan', 'tbody tr td a');
        
        // el 9 es porque ya hay 8 empleados creados
        $I->seeCurrentUrlEquals('/employee/9');
        $I->see('Detalles de Empleado', 'h2');
        $I->click('Editar', 'a');
        $I->seeCurrentUrlEquals('/employee/9/edit');
        $I->see('Actualizar Empleado', 'h2');
        
        $I->seeInFormFields('form', [
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'internal_code'         =>  '52659',
            'email'                 =>  'alan.silvestri@example.com',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
        ]);
        
        $I->submitForm('form', [
            'name'                  =>  'Alan Robert',
            'lastname'              =>  'Silvestri Smith',
            'identification_number' =>  '99987',
            'internal_code'         =>  '444141',
            'email'                 =>  'alan.silvestri@example.com',
            'sub_cost_center_id'    =>  3,
            'position_id'           =>  2
        ], 'Actualizar');
        
        $I->seeCurrentUrlEquals('/employee/9');
        $I->see('Empleados', 'h1');
        $I->see('Empleado actualizado correctamente.', '.alert-success');
    }
}
