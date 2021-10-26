<?php
spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class . '.php');
    if (file_exists($file)) {
        require $file;
    }
});

$router = new \config\Router([
    new \config\Route('home_page', '/', [\controller\IndexController::class]),
    new \config\Route('get_all_jobs', '/api/job', [\controller\JobController::class, 'getAll']),
    new \config\Route('get_all_companies', '/api/company', [\controller\CompanyController::class, 'getAll']),
    new \config\Route('get_all_professions', '/api/profession', [\controller\ProfessionController::class, 'getAll']),
]);

try {
    $uri = $_SERVER['REQUEST_URI'];
    $pos = strpos($uri, "?");
    if ($pos !== false) {
        $uri = substr($uri, 0, $pos);
    }

    $route = $router->matchFromPath($uri, $_SERVER['REQUEST_METHOD']);

    $parameters = $route->getParameters();
    $arguments = $route->getVars();

    $controllerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    $controller = new $controllerName();
    if (!is_callable($controller)) {
        $controller =  [$controller, $methodName];
    }

    echo $controller($_GET, ...array_values($arguments));

} catch (\Exception $exception) {
    header("HTTP/1.0 404 Not Found");
}
