<?php

namespace App\Generators;

use App\Contracts\Core\RouteRepository;

class RoutesGenerator 
{
    private $routeRepo;

    public function __construct(RouteRepository $routeRepo)
    {
        $this->routeRepo = $routeRepo;
    }

    public function run()
    {
        try {

            $routes = $this->routeRepo->all();
            $strRoutes = '<?php'.PHP_EOL; 
            foreach($routes as $route){
                $name = $route->name;
                $method = $route->method;
                $link = $route->link;
                $action = $route->action;
                $middleware = $this->getMiddleware($route->middleware);
                $params = $this->getParams($route->params);
                if($params !== '')
                    $params = '/'.$params;
    
                if(!is_null($name)){
                    $strRoutes .= "Route::$method('$link".$params."', ['as' => '$name', 'uses' => '$action'])".$middleware.PHP_EOL;
                }else{
                    $strRoutes .= "Route::$method('$link".$params."', '$action')".$middleware.PHP_EOL;
                }
            }

            $fp=fopen($this->getRouteFile(),"w+"); 
            fwrite($fp, $strRoutes); 
            fclose($fp); 
            return true; 

        } catch (\Throwable $th) {
            return false;
        }       
    } 

    public function prepend($strRoutes)
    {
        try {
            $fp=fopen($this->getRouteFile(),"a"); 
            fwrite($fp, $strRoutes); 
            fclose($fp);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function getMiddleware($middleware)
    {
        if(is_null($middleware) || trim(empty($middleware))){
            $middleware = ';'; 
        }else{
            if(strpos($middleware, ',') !==false){
                $middleware = explode(',', $middleware);
                $middleware = "'".implode("','", $middleware)."'";
                $middleware = "->middleware($middleware);";
            }else{
                $middleware = "->middleware('$middleware');";
            }
        }

        return $middleware;
    }

    private function getParams($params)
    {
        if(is_null($params) || trim(empty($params))){
            $params = ''; 
        }else{
            if(strpos($params, ',') !==false){
                $params = explode(',', $params);
                $params = implode('/', $params);
            }
        }
        return $params;
    }

    public function getRouteFile()
    {
        $routeFile = base_path().'/routes/moduleroutes.php';
        return $routeFile;
    }
}
