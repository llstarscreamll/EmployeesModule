<?php
namespace common;

class CostCenters
{
    /**
     * Crea centros de costo
     */
    public static function createCostCenters()
    {
        $data = [];
        
        $data[] = [
            'name'          => 'Proyecto Beteitiva',
            'short_name'    => 'beteitiva',
            'description'   => 'La mina Beteitiva',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];

        $data[] = [
            'name'          => 'Proyecto Sanoha',
            'short_name'    => 'sanoha',
            'description'   => 'La mina Sanoha',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];
        
        $data[] = [
            'name'          => 'Proyecto Cazadero',
            'short_name'    => 'cazadero',
            'description'   => 'La mina Cazadero',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];
        
        $data[] = [
            'name'          => 'Proyecto Pinos',
            'short_name'    => 'pinos',
            'description'   => 'La mina Pinos',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];

        \DB::table('cost_centers')->insert($data);
    }
}
