<?php

class Bootstrap 
{
    public function init()
    {
        self::initializeAutoload();
    }

    public function initializeAutoload()
    {
        spl_autoload_register(function ($className)
            {
            $className = ltrim($className, '\\');
            $fileName  = '';
            $namespace = '';
            if ($lastNsPos = strrpos($className, '\\')) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            if (file_exists($fileName))
                include $fileName;
            }
        );
    }
}


Bootstrap::init();

