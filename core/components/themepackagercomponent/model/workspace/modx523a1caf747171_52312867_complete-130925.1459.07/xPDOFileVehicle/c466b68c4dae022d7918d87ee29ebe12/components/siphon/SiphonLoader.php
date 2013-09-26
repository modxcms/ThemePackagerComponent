<?php
class SiphonLoader {
    public static function registerAutoload() {return spl_autoload_register(array(__CLASS__, 'autoload'));}
    public static function unregisterAutoload() {return spl_autoload_unregister(array(__CLASS__, 'autoload'));}
    public static function autoload($class) {
        @include SIPHON_BASE_PATH . strtr($class, '\\', '/') . '.php';
    }
}

/* use spl_autoload without throwing exceptions */
spl_autoload_extensions(".php");
spl_autoload_register(array('SiphonLoader', 'autoload'), false);
