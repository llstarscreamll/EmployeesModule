<?php
namespace common;

use Carbon\Carbon;
use Faker\Factory as Faker;
use llstarscreamll\CoreModule\app\Models\CostCenter as CostCenterModel;

class SubCostCenters
{
    /**
     * Crea subcentros de costos
     */
    public static function createSubCostCenters()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        $costCenters = CostCenterModel::all();

        foreach ($costCenters as $costCenter) {
            $data[] = [
                'cost_center_id'    =>      $costCenter->id,
                'name'              =>      'Subcentro 1',
                'short_name'        =>      'S1',
                'description'       =>      'Descripción de Subcentro',
                'created_at'        =>      $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
                'updated_at'        =>      $date->toDateTimeString()
            ];
            
            $data[] = [
                'cost_center_id'    =>      $costCenter->id,
                'name'              =>      'Subcentro 2',
                'short_name'        =>      'S2',
                'description'       =>      'Descripción de Subcentro',
                'created_at'        =>      $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
                'updated_at'        =>      $date->toDateTimeString()
            ];
        }
        
        \DB::table('sub_cost_centers')->insert($data);
    }
}
