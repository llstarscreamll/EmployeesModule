<?php
namespace llstarscreamll\EmployeesModule\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position_id',
        'sub_cost_center_id',
        'internal_code',
        'identification_number',
        'name',
        'lastname',
        'email',
        'phone',
        'address',
        'city'
    ];

    /**
     * La relación entre empleado y subcentro de costo
     */
    public function subCostCenter()
    {
        return $this->belongsTo('llstarscreamll\CoreModule\app\Models\SubCostCenter', 'sub_cost_center_id')->withTrashed();
    }
    
    /**
     * La relación entre empleado y cargo
     * 
     * @return 
     */
    public function position()
    {
        return $this->belongsTo('llstarscreamll\EmployeesModule\app\Models\Position')->withTrashed();
    }
    
    /**
     * Obtiene el nombre completo del empleado
     * 
     * @return string
     */
    public function getFullnameAttribute()
    {
        return $this->attributes['lastname'] .' '. $this->attributes['name'];
    }
    
    /**
     * Actualización masiva de datos
     * 
     * @param   array   $data
     * @return  void
     */
    public static function massiveUpdate($data)
    {
        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                \DB::table('employees')
                    ->where('internal_code', $key)
                    ->update($value);
            }
        });
    }
    
    /**
     * Creación masiva de datos
     * 
     * @return  void
     */
    public static function massiveCreate($data)
    {
        // número de filas a insertar por query
        $rows_per_chunk = 100;
        
        $data_chunk = array_chunk($data, $rows_per_chunk);
        foreach ($data_chunk as $chunk) {
            \DB::table('employees')->insert($chunk);
        }
    }
    
    /**
     * Resuelve que datos de $file_data son para actualizar, crear o ignorar
     * 
     * @param   array   $file_data
     * @return  array
     */
    public static function resolveWhatToUpdateToCreateToIgnore($file_data)
    {
        $db_data = \grapas\Models\Employee::whereIn('internal_code', $file_data['employees_codes'])->withTrashed()->get();
        $positions = \grapas\Models\Position::whereIn('name', $file_data['positions'])->withTrashed()->get()->lists('name', 'id');
        $sub_cost_centers = \grapas\Models\SubCostCenter::withTrashed()->lists('name', 'id');
        
        $data['update'] = array();
        $data['create'] = array();
        $data['ignore'] = array();
        $data['diference'] = array_diff($file_data['employees_codes'], $db_data->lists('internal_code', 'id')->toArray()); // lo que se debe preparar para crear
        $data['intersect'] = array_intersect($file_data['employees_codes'], $db_data->lists('internal_code', 'id')->toArray()); // lo que se debe preparar para actualizar o ignorar

        // reviso las diferencias primero
        foreach ($file_data['employees'] as $key => $employee) {
            
            // cambio el texto del cargo por el id del mismo
            foreach ($positions as $position_key => $position_value) {
                // convierto los strings de los cargos a integers con los id's de los cargos en la DB
                // los cargos ya tienen que estar creados antes de hacer este proceso
                if (in_array($position_value, $employee)) {
                    $employee['position_id'] = $position_key;
                }
            }
            
            foreach ($sub_cost_centers as $sub_cost_center_key => $sub_cost_center) {
                if (in_array($sub_cost_center, $employee)) {
                    $employee['sub_cost_center_id'] = $sub_cost_center_key;
                }
            }
            
            $employee += [
                        'created_at'    =>  date('Y-m-d H:i:s'),
                        'updated_at'    =>  date('Y-m-d H:i:s'),
                        'deleted_at'    =>  null
                    ];
            
            foreach ($data['diference'] as $diference_key => $diference_value) {
                if ($employee['internal_code'] === $diference_value) {
                    $data['create'][] = $employee;
                }
            }
            
            // recorro los que hay en comun para actualizar
            foreach ($data['intersect'] as $intersect_key => $value) {
                if ($employee['internal_code'] === $value) {
                    $data['update'][$employee['internal_code']] = $employee;
                }
            }
        }

        return $data;
    }
    
    /**
     * Gestor para importar datos de empleados a db
     * 
     * @param   array   $file_data
     * @retunr  array
     */
    public static function importData($file_data)
    {
        // compruebo que datos son para actualizar y que datos para crear
        $data = self::resolveWhatToUpdateToCreateToIgnore($file_data);

        // actualizo y creo los datos necesarios
        self::massiveUpdate($data['update']);
        self::massiveCreate($data['create']);
        
        // devuelvo los datos que se han creado o actualizado

        return $data;
    }
    
    /**
     * Obtengo los datos que serán exportados a excel
     */
    public static function getDataToExportExcel()
    {
        $employees = \grapas\Models\Employee::all();
        $data = array();
        
        foreach ($employees as $key => $employee) {
            $data[] = [
                // los campos opcionales se llenan con espacios
                'codigo'            =>  $employee->internal_code,
                'cedula'            =>  $employee->identification_number,
                'nombres'           =>  $employee->name,
                'apellidos'         =>  $employee->lastname,
                'email'             =>  $employee->email ? $employee->email : '  ',
                'ciudad'            =>  $employee->city ? $employee->city : '  ',
                'direccion'         =>  $employee->address ? $employee->address : '  ',
                'telefono'          =>  $employee->phone ? $employee->phone : '  ',
                'centro_costo'      =>  $employee->subCostCenter->costCenter->name,
                'subcentro_costo'   =>  $employee->subCostCenter->name,
                'cargo'             =>  $employee->position->name
                
            ];
        }

        return $data;
    }

    /**
     * Obtengo el estado del empleado formateado, es decir con letras de cierto
     * color dependiendo del estado.
     */
    public function getStatusHtml()
    {
        // el valor a mostrar en la ui
        switch ($this->status) {
            case 'enabled':
                $txt = 'Activado';
                $style_class = 'text-success';
                break;
            case 'disabled':
                $txt = 'Desactivado';
                $style_class = 'text-danger';
                break;
            default:
                $txt = $this->status;
                $style_class = '';
                break;
        }
        
        $tag = '<span class="%style_class%">'.$txt.'</span>';
        
        return str_replace('%style_class%', $style_class, $tag);
    }
}
