<?php
namespace common;

use Carbon\Carbon;
use Faker\Factory as Faker;
use \common\Roles as RolesCommons;
use llstarscreamll\CoreModule\app\Models\User as UserModel;

class User
{
    /**
     * The user admin data, this user is the actor
     * in all scenarios in admin role contexts
     *
     * @var array
     */
    public static $adminUser = [
        'name'          =>      'Travis',
        'lastname'      =>      'Orbin',
        'email'         =>      'travis.orbin@example.com',
        'password'      =>      '123456',
        'activated'     =>      1,
    ];

    /**
     * The test user info, this is the user to test
     * functionalities on users module
     *
     * @var array
     */
    public $testUser = [
        'role_id'           =>    1,
        'subCostCenter_id'  =>    [1],
        'name'              =>    'Andrew',
        'lastname'          =>    'Mars',
        'email'             =>    'andrew.mars@example.com',
        'password'          =>    '123456'
    ];

    /**
     * The base admin url
     *
     * @var string
     */
    public $usersIndexUrl = '/users';
    
    /**
     * Las dependencias para crear un usuario 
     */
    private $permissionsCommons;
    private $rolesCommons;
    
    public function __construct()
    {
        $this->rolesCommons = new RolesCommons;
    }
    
    /**
     * Create the admin user and attach role
     * Ya deben estar creados los roles y permisos
     * 
     * @return Model
     */
    public static function createAdminUser()
    {
        $user = UserModel::firstOrCreate(array_merge(self::$adminUser, [
            'password' => bcrypt(self::$adminUser['password'])
            ]));
        $user->attachRole(2); // 2 es el id del rol admin

        return $user;
    }

    /**
     * Create several users on storage, default 10 users
     *
     * @param int $num
     * @return void
     */
    public function createUsers($num = 10)
    {
        $faker = Faker::create();
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subDays(3);
        $data = [];
        
        for ($i=1; $i <= $num; $i++) {
            $data[] = [
                'name'          =>      $faker->firstName,
                'lastname'      =>      $faker->lastname,
                'email'         =>      $faker->unique()->email,
                'password'      =>      '123456',
                'activated'     =>      1,
                'created_at'    =>      $date->addSeconds($faker->numberBetween(1, 10))->toDateTimeString(),
                'updated_at'    =>      $date->toDateTimeString(),
                'deleted_at'    =>      null
            ];
        }
        
        \DB::table('users')->insert($data);
    }
}
