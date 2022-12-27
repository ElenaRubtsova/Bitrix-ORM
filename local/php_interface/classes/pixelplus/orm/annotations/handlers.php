<?php

namespace Pixelplus\OrmAnnotations;

use Bitrix\Main\EventManager;

class Handlers
{
    public static function onVirtualClassBuildList()
    {
        echo('3');
        if (class_exists('\Composer\Autoload\ClassMapGenerator')) {
            echo 'false';
            $composerJsonFile = $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/composer.json';

            $map = [];
            if (file_exists($composerJsonFile)) {
                $jsonContent = json_decode(file_get_contents($composerJsonFile), true);

                if (array_key_exists('psr-4', $jsonContent['autoload'])) {
                    foreach (['psr-4', 'psr-0'] as $psrType) {
                        foreach ($jsonContent['autoload'][$psrType] as $path) {
                            if (substr($path, 0, 1) != '/') {
                                $path = dirname($composerJsonFile).'/'.$path;
                            }
                            $map = array_merge($map, array_keys(\Composer\Autoload\ClassMapGenerator::createMap(
                                $path
                            )));
                        }
                    }
                }
            }
            foreach ($map as $class) {
                class_exists($class);
            }
        }
    }

    public static function registerHandler()
    {
        echo('2');
        EventManager::getInstance()->registerEventHandler(
            'main',
            'onVirtualClassBuildList',
            'main',
            self::class,
            'onVirtualClassBuildList'
        );
    }
}