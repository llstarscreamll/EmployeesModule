<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTests;

class SearchCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTests;
        $base_test->employees();

        $I->amLoggedAs(BaseTests::$admin_user);
        
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
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de buscar un empleado por nombre
     */
    public function searchByName(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('buscar empleado por nombre');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->see('Empleado 1', 'tbody tr td a');
        $I->see('Empleado 2', 'tbody tr td a');
        
        $I->seeElement('form', ['name' => 'search-form']);
        
        $I->submitForm('form[name=search-form]', [
            'find'  =>  'Alan'
            ], 'Buscar');
            
        $I->seeCurrentUrlEquals('/employee?find=Alan');
        
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->dontSee('Empleado 1', 'tbody tr td a');
        $I->dontSee('Empleado 2', 'tbody tr td a');
    }
    
    /**
     * Pruebo la funcionalidad de buscar un empleado por apellido
     */
    public function searchByLastname(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('buscar empleado por apellido');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->see('Empleado 1', 'tbody tr td a');
        $I->see('Empleado 2', 'tbody tr td a');
        
        $I->seeElement('form', ['name' => 'search-form']);
        
        $I->submitForm('form[name=search-form]', [
            'find'  =>  'Silves' // Silvestri
            ], 'Buscar');
            
        $I->seeCurrentUrlEquals('/employee?find=Silves');
        
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->dontSee('Empleado 1', 'tbody tr td a');
        $I->dontSee('Empleado 2', 'tbody tr td a');
    }
    
    /**
     * Pruebo la funcionalidad de buscar un empleado por número de cédula
     */
    public function searchByCCNumber(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('buscar empleado por numero de cedula');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->see('Empleado 1', 'tbody tr td a');
        $I->see('Empleado 2', 'tbody tr td a');
        
        $I->seeElement('form', ['name' => 'search-form']);
        
        $I->submitForm('form[name=search-form]', [
            'find'  =>  '742653' // 74265326
            ], 'Buscar');
            
        $I->seeCurrentUrlEquals('/employee?find=742653');
        
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->dontSee('Empleado 1', 'tbody tr td a');
        $I->dontSee('Empleado 2', 'tbody tr td a');
    }
    
    /**
     * Pruebo la funcionalidad de buscar un empleado por código interno
     */
    public function searchByInternalCode(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('buscar empleado por codigo interno');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->see('Empleado 1', 'tbody tr td a');
        $I->see('Empleado 2', 'tbody tr td a');
        
        $I->seeElement('form', ['name' => 'search-form']);
        
        $I->submitForm('form[name=search-form]', [
            'find'  =>  '52659' // 52659
            ], 'Buscar');
            
        $I->seeCurrentUrlEquals('/employee?find=52659');
        
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->dontSee('Empleado 1', 'tbody tr td a');
        $I->dontSee('Empleado 2', 'tbody tr td a');
    }
}
