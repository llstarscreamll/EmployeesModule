<?php
namespace common;

use Carbon\Carbon;
use Faker\Factory as Faker;

class Permissions
{
    private $faker;
    private $date;
    private $data = array();
    
    public function __construct()
    {
        $this->faker    = Faker::create();
        $this->date     = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12')->subMonth();
    }
    
    /**
     * Crea permisos para el módulo de horas extra
     */
    public function createExtraTimesModulePermissions()
    {
        $this->data[] = [
            'name'           => 'extraTime.index',
            'display_name'   => 'Listar Horas Extra Registradas',
            'description'    => 'Ver en una lista todas las horas extra o no habituales del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'extraTime.create',
            'display_name'   => 'Registrar Horas Extra',
            'description'    => 'Registrar nuevas horas extra o no habituales',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'extraTime.show',
            'display_name'   => 'Ver Detalles de Horas Extra',
            'description'    => 'Visalizar la información de las horas extra registradas (sólo lectura)',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'          => 'extraTime.edit',
            'display_name'  => 'Actualizar Horas Extra Registradas',
            'description'   => 'Actualiza la información de las horas extra que se hayan registrado',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'          => 'extraTime.destroy',
            'display_name'  => 'Eliminar Horas Extra',
            'description'   => 'Eliminar horas extra registradas',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        \DB::table('permissions')->insert($this->data);
    }
    
    /**
     * Crea permisos para el módulo de roles
     */
    public function createRolesModulePermissions()
    {
        $this->data[] = [
            'name'           => 'roles.index',
            'display_name'   => 'Listar Roles',
            'description'    => 'Ver en una lista todos los roles del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'roles.create',
            'display_name'   => 'Crear Rol',
            'description'    => 'Crear nuevos roles',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'roles.show',
            'display_name'   => 'Ver Rol',
            'description'    => 'Visalizar la información de los roles (sólo lectura)',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'roles.edit',
            'display_name'   => 'Actualizar Rol',
            'description'    => 'Actualiza la información de los roles del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'roles.destroy',
            'display_name'   => 'Eliminar Rol',
            'description'    => 'Eliminar roles del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        \DB::table('permissions')->insert($this->data);
    }
    
    /**
     * Crea los permisos para el módulo de usuarios
     */
    public function createUsersModulePermissions()
    {
        $this->data[] = [
            'name'           => 'users.index',
            'display_name'   => 'Listar Usuarios',
            'description'    => 'Ver una lista de todos usuarios del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'users.create',
            'display_name'   => 'Crear Usuario',
            'description'    => 'Crear usuarios del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'users.show',
            'display_name'   => 'Ver Usuario',
            'description'    => 'Visualizar la información de un usuario (sólo lectura)',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'users.edit',
            'display_name'   => 'Editar Usuario',
            'description'    => 'Editar la información de un usuario',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'users.destroy',
            'display_name'   => 'Eliminar Usuario',
            'description'    => 'Eliminar usuarios del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1, 10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        \DB::table('permissions')->insert($this->data);
    }
}
