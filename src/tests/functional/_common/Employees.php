<?php

namespace common;

use \llstarscreamll\EmployeesModule\app\Models\Position as PositionModel;
use \llstarscreamll\CoreModule\app\Models\SubCostCenter as SubCostCenterModel;

class Employees
{
    /**
     * Create several employees on storage, default 10 users
     *
     * @param int $num
     * @return void
     */
    public static function createEmployees()
    {
        $data = [];
        $pivot_data = [];
        $subCostCenters = SubCostCenterModel::get();
        $count = 1;
        
        $date = \Carbon\Carbon::now();
        $date = $date->subMonth();
        
        PositionModel::create([
            'name'  =>  'Minero'
        ]);
        
        foreach ($subCostCenters as $subCostCenter) {
            $data[] = [
                'sub_cost_center_id'    =>      $subCostCenter->id,
                'position_id'           =>      1,
                'internal_code'         =>      $count+1,
                'identification_number' =>      $count+2,
                'name'                  =>      'Empleado ' . $count,
                'lastname'              =>      $subCostCenter->short_name,
                'email'                 =>      'Empleado'.$count++.'@example.com',
                'city'                  =>      'Nobsa',
                'address'               =>      'carrera #11 - 36',
                'phone'                 =>      '3115318813',
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
        }
        
        \DB::table('employees')->insert($data);
    }
}
