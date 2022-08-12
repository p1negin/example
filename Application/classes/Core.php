<?php

namespace Application\Classes;

use Application\Classes\Utils\Mysql;
use Application\Classes\Utils\Router;
use Application\Classes\Utils\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class Core
{
    /**
     * Run core project
     * @return void
     */
    public static function run(): void
    {
        session_start();
        self::mysqlConnect();
        try {
            self::twig();
        } catch (LoaderError|RuntimeError|SyntaxError $exception) {
            if (DEBUG_MODE === true) {
                exit($exception->getMessage());
            } else {
                exit('Unknown error!');
            }
        }
        self::router();
    }

    /**
     * Mysql run
     * @return void
     */
    private static function mysqlConnect(): void
    {
        Mysql::connect(MySQL_HOST, MySQL_USER, MySQL_PASS, MySQL_BASE);
    }

    /**
     * Router run
     * @return void
     */
    private static function router(): void
    {
        Router::run();
    }

    /**
     * Twig run
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    private static function twig(): void
    {
        Twig::run();
    }
}