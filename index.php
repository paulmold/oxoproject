<?php
spl_autoload_register();

$router = new \config\Router([
    new \config\Route('home_page', '/', [\controller\IndexController::class]),
    new \config\Route('get_all_jobs', '/api/job', [\controller\JobController::class, 'getAll']),
    new \config\Route('get_job', '/api/job/{id}', [\controller\JobController::class, 'get']),
    new \config\Route('add_job', '/api/job', [\controller\JobController::class, 'add'], ['POST']),
    new \config\Route('update_job', '/api/job/{id}', [\controller\JobController::class, 'update'], ['PUT']),
    new \config\Route('delete_job', '/api/job/{id}', [\controller\JobController::class, 'delete'], ['DELETE']),
]);

try {
    $route = $router->matchFromPath($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

    $parameters = $route->getParameters();
    $arguments = $route->getVars();

    $controllerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    $controller = new $controllerName();
    if (!is_callable($controller)) {
        $controller =  [$controller, $methodName];
    }

    echo $controller(...array_values($arguments));

} catch (\Exception $exception) {
    header("HTTP/1.0 404 Not Found");
}
