<?php

use App\App;
use App\News;
use Klein\Klein;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$router = new Klein();
$router->with('/api', function () use ($router) {
    $router->respond('GET', '/news/[i:id].html', function($request, $response) {
        return News::renderNewsItem($request->id);
    });
});
foreach (App::formSpecs() as $spec) {
    $route = App::formRoute($spec);
    $router->respond($route['method'], $route['path'], $route['handler']);
}
$router->dispatch();
