<?php

namespace Application\Classes\Utils;

use Exception;

abstract class Router
{
    /**
     * Routes
     * @var array
     */
    private static array $routes = [
        '~/regAction$~' => [
            'controller' => 'mainController',
            'action' => 'reg'
        ],
        '~/reg$~' => [
            'controller' => 'mainController',
            'action' => 'regPage'
        ],
        '~/logout$~' => [
            'controller' => 'mainController',
            'action' => 'logout'
        ],
        '~/authAction$~' => [
            'controller' => 'mainController',
            'action' => 'auth'
        ],
        '~/auth$~' => [
            'controller' => 'mainController',
            'action' => 'authPage'
        ],
        '~^/subjects$~' => [
            'controller' => 'mainController',
            'action' => 'subjectsPage'
        ],
        '~^/subjects/create$~' => [
            'controller' => 'mainController',
            'action' => 'createSubjectPage'
        ],
        '~^/subjects/remove/(?<subject_id>\d+)~' => [
            'controller' => 'mainController',
            'action' => 'removeSubject'
        ],
        '~^/subjects/edit/(?<subject_id>\d+)~' => [
            'controller' => 'mainController',
            'action' => 'editSubject'
        ],

        '~^/subjects/createAction$~' => [
            'controller' => 'mainController',
            'action' => 'createSubject'
        ],

        '~^/teachers/create$~' => [
            'controller' => 'mainController',
            'action' => 'createTeacherPage'
        ],
        '~^/teachers/createAction$~' => [
            'controller' => 'mainController',
            'action' => 'createTeacher'
        ],
        '~^/teachers/remove/(?<teacher_id>\d+)~' => [
            'controller' => 'mainController',
            'action' => 'removeTeacher'
        ],

        '~^/teachers/edit/(?<teacher_id>\d+)~' => [
            'controller' => 'mainController',
            'action' => 'editTeacher'
        ],

        '~/^teachers$~' => [
            'controller' => 'mainController',
            'action' => 'teachersPage'
        ],
        '~/~' => [
            'controller' => 'mainController',
            'action' => 'teachersPage'
        ]
    ];

    /**
     * Route
     * @var array
     */
    private static array $route = [];

    /**
     * Run Router
     * @return void
     */
    public static function run(): void
    {
        try {
            if (self::routeMatch()) {
                if (!class_exists(self::$route['controller'])) {
                    throw new Exception('Класс контроллера не существует - ' . self::$route['controller']);
                }
                if (!method_exists(self::$route['controller'], self::$route['action'])) {
                    throw new Exception('Метод класса контроллера не существует - ' . self::$route['action']);
                }
                $action = self::$route['action'];
                (new self::$route['controller']())->$action();
            } else {
                throw new Exception('Маршрут не найден!');
            }
        } catch (Exception $exception) {
            if (DEBUG_MODE === true) {
                exit($exception->getMessage());
            } else {
                header("HTTP/1.0 404 Not Found");
                exit('404 Not Found');
            }
        }
    }

    private static function routeMatch(): bool
    {
        foreach (self::$routes as $regexp => $route) {
            if (preg_match($regexp, $_SERVER['REQUEST_URI'], $matches)) {
                self::$route = $route;
                self::$route['params'] = $matches;
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public static function getRoute(): array
    {
        return self::$route;
    }
}