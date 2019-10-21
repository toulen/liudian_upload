<?php
namespace Liudian\Upload\Facades;

use Illuminate\Support\Facades\Facade;

class OssFile extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'oss_file';
    }
}