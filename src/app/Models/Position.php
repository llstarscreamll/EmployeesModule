<?php 
namespace llstarscreamll\EmployeesModule\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function employee()
    {
        return $this->hasMany('llstarscreamll\EmployeesModule\app\Models\Employee');
    }
    
    /**
     * Resuelve que datos de $file_data son para actualizar, crear o ignorar,
     * los datos dentro del array $file_data vienen en minúscula
     * 
     * @param   array   $file_data
     * @return  array
     */
    public static function resolveWhatToUpdateToCreateToIgnore($file_data)
    {
        $db_data = \grapas\Models\Position::whereIn('name', $file_data)->lists('name', 'id');
        
        $data['update'] = array();
        $data['create'] = array();
        $data['ignore'] = array();
        $data['diference'] = array_diff($file_data, $db_data->toArray()); // lo que se debe preparar para crear
        $data['intersect'] = array_intersect($file_data, $db_data->toArray()); // lo que se debe preparar para actualizar o ignorar

        // los datos a crear
        foreach ($data['diference'] as $key => $value) {
            $data['create'][] = [
                'name'          =>  $value,
                'created_at'    =>  date('Y-m-d H:i:s'),
                'updated_at'    =>  date('Y-m-d H:i:s'),
                'deleted_at'    =>  null
            ];
        }
        
        // los datos a ignorar
        foreach ($data['intersect'] as $key => $value) {
            if ($db_data->search($value, true) !== false) {
                $data['ignore'][] = $value;
            }
        }

        return $data;
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
                \DB::table('positions')
                    ->where('id', $key)
                    ->update(array(
                        'name'       => $value,
                        'updated_at' => date('Y-m-d H:i:s')
                    ));
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
            \DB::table('positions')->insert($chunk);
        }
    }
    
    /**
     * Gestor para importar datos de cargos a db
     * 
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
}
