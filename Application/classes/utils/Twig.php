<?php

namespace Application\Classes\Utils;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Twig
{
    public static Environment $twig;

    public static function run(): void
    {
        $loader = new FilesystemLoader(ROOT_DIR . '/templates');
        self::$twig = new Environment($loader, [
            'cache' => false
        ]);
    }
}