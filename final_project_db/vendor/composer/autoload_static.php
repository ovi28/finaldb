<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitce5f13a88bf49bc82d5319641fb1b89d
{
    public static $prefixesPsr0 = array (
        'E' => 
        array (
            'Everyman\\Neo4j' => 
            array (
                0 => __DIR__ . '/..' . '/everyman/neo4jphp/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitce5f13a88bf49bc82d5319641fb1b89d::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
