<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTests;

class ShowCest
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
     * Pruebo la funciónalidad de ver detalles de un empleado
     */
    public function show(FunctionalTester $I)
    {
        \llstarscreamll\EmployeesModule\app\Models\Employee::create([
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'internal_code'         =>  '52659',
            'email'                 =>  'alan.silvestri@example.com',
            'city'                  =>  'Nobsa',
            'address'               =>  'carrera No.11 - 36',
            'phone'                 =>  '3115318813',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
            ]);
        
        $I->am('admin de recurso humano');
        $I->wantTo('ediat la info de un empleado');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->click('Silvestri Alan', 'tbody tr td a');
        
        // el 9 es porque ya hay 8 empleados creados
        $I->seeCurrentUrlEquals('/employee/9');
        $I->see('Detalles de Empleado', 'h2');
        
        // los campos con los detalles del empleado
        $I->seeElement('input', ['value' => 'Alan']);
        $I->seeElement('input', ['value' => 'Silvestri']);
        $I->seeElement('input', ['value' => '74265326']);
        $I->seeElement('input', ['value' => '52659']);
        $I->seeElement('input', ['value' => 'alan.silvestri@example.com']);
        $I->seeElement('input', ['value' => 'Proyecto Beteitiva - Subcentro 1']);
        $I->seeElement('input', ['value' => 'Minero']);
    }
}
