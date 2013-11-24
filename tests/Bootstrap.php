<?php
namespace NpComposerInstallerTest;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

Bootstrap::init();
class Bootstrap
{

    protected static $loader;
    
    public static function init()
    {
        // Load the user-defined test configuration file, if it exists; otherwise, load
        if (is_readable(__DIR__ . '/TestConfig.php')) {
            $testConfig = include __DIR__ . '/TestConfig.php';
        } else {
            $testConfig = include __DIR__ . '/TestConfig.php.dist';
        }

        static::initAutoloader();
        
        if (isset(self::$loader)) {
            $prefixes = self::$loader->getPrefixes();
            if (!isset($prefixes['Composer'])) {
                error_log('composer not found');
            }
            
            if (!isset($prefixes[__NAMESPACE__])) {
                self::$loader->add(__NAMESPACE__ . '\\', __DIR__);
            }
            
            $libraryPrefix = substr(__NAMESPACE__, 0, -4) . '\\';
            if (!isset($prefixes[$libraryPrefix])) {
                self::$loader->add($libraryPrefix, __DIR__ . '/../src');
            }
        }
        
        if (!class_exists('Composer\Composer')) {
            die('If you want to test this module , load the Composer library by composer or include composer.phar');
        }
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');
        
        //we have composer autoloader?
        if (is_readable($vendorPath . '/autoload.php')) {
            self::$loader = require_once $vendorPath . '/autoload.php';
            //self::$loader->register();
        }

    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }
        return realpath($dir . '/' . $path);
    }
}


