<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Route;


final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList;
        $router->addRoute('prihlaseni', 'Home:in');
        $router->addRoute('<presenter>[/<taskId \d+>[/<action>]]', [
                'presenter' => [
                    Route::Value => 'Home',
                    Route::FilterTable => [
                        'uloha' => 'Task'
                    ],
                ],
                'action' => [
                    Route::Value => 'default',
                    Route::FilterTable => [
                        'upravit' => 'edit',
                        'smazat' => 'delete'
                    ]
                ]
            ]
        );

        return $router;
    }
}
