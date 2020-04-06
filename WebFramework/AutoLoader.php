<?php

namespace WebFramework;

class Autoloader
{
    /**
     * @param string $className - Class to require
     */
    public static function loader(string $className)
    {
        $className = str_replace("_", "\\", $className);
        $className = ltrim($className, '\\');
        $fileName = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\'))
        {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require '../' . $fileName;
    }

}

spl_autoload_register(__NAMESPACE__ . '\Autoloader::loader');
