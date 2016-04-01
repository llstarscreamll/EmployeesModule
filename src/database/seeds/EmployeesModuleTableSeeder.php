<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmployeesModuleTableSeeder extends Seeder
{
    private $date;
    private $data = array();
    
    public function run()
    {
        $this->date = Carbon::now();
        $this->createEmployeesModulePermissions();
        \DB::table('permissions')->insert($this->data);
    }

    /**
     * Creo los permisos para el módulo de empleados
     */
    public function createEmployeesModulePermissions()
    {
        $this->data[] = [
            'name'           => 'employee.index',
            'display_name'   => 'Listar empleados',
            'description'    => 'Ver una lista de todos empleados del sistema',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'employee.create',
            'display_name'   => 'Crear empleado',
            'description'    => 'Crear empleados del sistema',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'employee.show',
            'display_name'   => 'Ver empleado',
            'description'    => 'Visualizar la información de un empleado (sólo lectura)',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'employee.edit',
            'display_name'   => 'Editar empleado',
            'description'    => 'Editar la información de un empleado',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'employee.destroy',
            'display_name'   => 'Eliminar empleado',
            'description'    => 'Eliminar empleados del sistema',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'employee.status',
            'display_name'   => 'Activar/Desactivar empleados',
            'description'    => 'Activa o desactiva empleados, permitiendo ver o no su información en los demás módulos del sistema.',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'employee.restore',
            'display_name'   => 'Restaurar empleados',
            'description'    => 'Restaura la información de un empleado si se encuentra en la papelera.',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'employee.balanceAccessControlHours',
            'display_name'   => 'Balance de horas de Empleado',
            'description'    => 'Permite balancear las horas del módulo de control de acceso de un empleado en específico.',
            'created_at'    =>  $this->date->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
    }
}
