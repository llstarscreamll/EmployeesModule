<?php

namespace llstarscreamll\EmployeesModule;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class EmployeesModuleServiceProvider extends ServiceProvider
{
    /**
     * Service Providers Array
     * @var array
     */
    protected $providers = [
    ];

    /**
     * Aliases array
     * @var array
     */
    protected $aliases = [
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // carga las vistas
        $this->loadViewsFrom(__DIR__.'/resources/views', 'EmployeesModule');

        // publica las vistas
        $this->publishes([__DIR__.'/resources/views' => base_path('/resources/views/vendor/EmployeesModule')], 'views');

        // publica las migraciones
        $this->publishes([__DIR__.'/database/migrations' => database_path('/migrations')], 'migrations');

        // publica los seeders
        $this->publishes([__DIR__.'/database/seeds' => database_path('/seeds')], 'seeds');

        // publica los archivos de configuración
        $this->publishes([__DIR__.'/config' => config_path('')], 'config');
        
        // publica los assets
         $this->publishes([
            __DIR__.'/public/resources' => base_path('/public/resources/EmployeesModule/'),
        ], 'assets');

        // Registra la clase que extiende las reglas de validación
        /*$this->app['validator']->resolver(
            function(
                $translator,
                $data,
                $rules,
                $messages = array(),
                $customAttributes = array()
            ) {
            return new Validation($translator, $data, $rules, $messages, $customAttributes);
        });*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->app->routesAreCached()) {
            include __DIR__.'/app/Http/routes.php';
        }

        $this->app->make('llstarscreamll\EmployeesModule\app\Http\Controllers\EmployeeController');

        $this->registerServiceProviders();
        $this->registerAliases();
        $this->registerMiddleware();
    }

    /**
     * Registra los middleware
     * @return void
     */
    private function registerMiddleware()
    {
        //$this->app['router']->middleware('checkCostCenter', 'llstarscreamll\EmployeesModule\app\Http\Middleware\EmployeeFormRequest');
    }

    /**
     * Registra los Service Providers
     * @return void
     */
    private function registerServiceProviders()
    {
        foreach ($this->providers as $provider)
        {
            $this->app->register($provider);
        }
    }

    /**
     * Registra los Aliases
     * @return void
     */
    private function registerAliases()
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->aliases as $key => $alias)
        {
            $loader->alias($key, $alias);
        }
    }
}
