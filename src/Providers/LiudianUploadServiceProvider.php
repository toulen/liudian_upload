<?php
namespace Liudian\Upload\Providers;

use Illuminate\Support\ServiceProvider;
use Liudian\Admin\Http\Middleware\AdminAuth;
use Liudian\Admin\Logic\AdminAuthLogic;
use Liudian\Admin\Repositories\AdminRbacPermissionRepository;
use Liudian\Upload\Logic\OssFile;

class LiudianUploadServiceProvider extends ServiceProvider
{

    protected $routeMiddleware = [];

    protected $middlewareGroups = [];

    public function register(){
        $this->app->singleton('oss_file', OssFile::class);
    }

    public function boot(){
        $this->loadLiudianUploadConfig();

        if($this->app->runningInConsole()){
            $this->publishes([
                __DIR__ . '/../../config/liudian_upload.php' => config_path('liudian_upload.php'),
            ]);
            $this->publishes([
                __DIR__ . '/../../public/jquery.html5upload.js' => public_path('js/jquery.html5upload.js'),
            ]);
            $this->publishes([
                __DIR__ . '/../../public/demo.blade.php' => public_path('js/demo.blade.php'),
            ]);
        }
    }

    protected function loadLiudianUploadConfig(){

        $this->mergeConfigFrom(__DIR__ . '/../../config/liudian_upload.php', 'liudian_upload');
    }

    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}