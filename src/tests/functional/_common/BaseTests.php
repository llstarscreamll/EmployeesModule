<?php
namespace common;

use \common\User as UserCommons;
use \common\Roles as RolesCommons;
use \common\Shifts as ShiftsCommons;
use \common\Employees as EmployeesCommons;
use \common\CostCenters as CostCentersCommons;
use \common\Permissions as PermissionsCommons;
use \common\RevisionKinds as RevisionKindsCommons;
use \common\SubCostCenters as SubCostCentersCommons;

class BaseTests
{
    public static $admin_user;
    
    /**
     * Las dependencias para los test del layout
     */
    public function layout()
    {
        // creo los permisos para el módulo de usuarios
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createUsersModulePermissions();
        
        // cargo los datos base
        $this->createBasicData();
        
        self::$admin_user = UserCommons::$adminUser;
    }
    
    /**
     * Creo lo datos para los test del módulo de empleados
     */
    public function employees()
    {
        // creo los permisos para el módulo de empleados
        \Artisan::call('db:seed', ['--class' => 'EmployeesModuleTableSeeder']);
        
        // cargo los datos base
        $this->createBasicData();
        
        // creo los permisos para el módulo de empleados
        EmployeesCommons::createEmployees();
        
        self::$admin_user = UserCommons::$adminUser;
    }
    
    /**
     * Creo los datos básicos que deben existir en la mayoría de los tests
     */
    private static function createBasicData()
    {
        // creo los roles de usuario y añado todos los permisos al rol de administrador
        RolesCommons::createBasicRoles();
                
        // creo el usuairo administrador
        UserCommons::createAdminUser();
        
        // creo sub centros de costo
        CostCentersCommons::createCostCenters();
        
        // creo centros de costo
        SubCostCentersCommons::createSubCostCenters();
    }
}
