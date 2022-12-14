<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitd3e6d20872031c8fcef9522ea97f6824
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitd3e6d20872031c8fcef9522ea97f6824', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitd3e6d20872031c8fcef9522ea97f6824', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitd3e6d20872031c8fcef9522ea97f6824::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
