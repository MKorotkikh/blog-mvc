<?php

define ("ROOT", dir_name(__FILE__));

class Router {
    private static $instance;

    private function __construct() {

    }
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __clone() { /* ... @return Singleton */ }  // Защищаем от создания через клонирование
    private function __wakeup() { /* ... @return Singleton */ }  // Защищаем от создания через unserialize

    private function routes() {
        return [
            '|^/site/register$|' => [
                'controller' => 'SiteController',
                'action' => 'actionRegister'
            ],
            '|^/user/profile/\d+$|' => [
                'controller' => 'UserController',
                'action' => 'actionProfile'
            ],
            '|^/add-post/new-post$|' => [
                'controller' => 'Add-PostController',
                'action' => 'actionNew-Post'
            ],
            '|^blog/posts/\d+$|' => [
                'controller' => 'BlogController',
                'action' => 'actionPosts'
            ],
            '|^blog/photos/\d+$|' => [
                'controller' => 'BlogController',
                'action' => 'actionPhotos'
            ],
            '|^blog/search/\d+$|' => [
                'controller' => 'BlogController',
                'action' => 'actionPhotos'
            ],
        ];
    }

//    public function handle($route) {
//        $routes = $this->routes();
//        if(array_key_exists($route, $routes)) {
//            $className = $routes[$route]['controller'];
//            $methodName = $routes[$route]['action'];
//
//            $controller = new $className();
//            $controller->$methodName();
//        } else {
//
////            foreach($routes as $key => $value) {
////                if(preg_match($key, $route)) {
////                    $className = $routes[$key]['controller'];
////                    $methodName = $routes[$key]['action'];
////
////                    $controller = new $className();
////                    $controller->$methodName();
////                    return;
////                }
////            }
//
//            $route = array_values(
//                array_filter(explode('/', $route))
//            );
//            $className = ucfirst($route[0]) . 'Controller';
//            if(class_exists($className)) {
//                $controller = new $className;
//                $methodName = 'action' . ucfirst($route[1]);
//                if(method_exists($controller, $methodName)) {
//                    $controller->$methodName();
//                }
//            }
//        }
//    }
    /*
     *Return request string
     * */
    private function getURI(){
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
/*
 * */
    public function run () {
        $uri = $this->getURI();

        foreach ($this->routes() as $uriPattern => $path){
            if (preg_match($uriPattern, $uri)) {
                $segments = explode ('/', $path);
                $controllerName = ucfirst(array_shift($segments) . "Controller");
                $actionName = 'action' . ucfirst(array_shift($segments));
                $controllerFile = ROOT . '/controllers/' . $controllerName . 'php';

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                $controllerObject = new $controllerName;
                $result = $controllerObject->$actionName;
            }
        }
    }
}