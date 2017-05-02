<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 06:21 PM
 * The best ;)
 */

namespace MicroFramework\Core;


class RouterManager
{
    private $uri,$query,$routes;
    private $controller_namespace = 'MicroFramework\Http\Controllers\\';
    public function __construct()
    {
        $this->uri    = UrlManager::get_uri();
        $this->query  = UrlManager::get_query();
        $this->routes = Router::get_all_urls();

        $this->manage();
    }

    public function manage(){
        if($this->route_exists()){

        }
    }

    public function route_exists(){

        $posible_first  = array_filter($this->routes,function($route){return count($this->uri) == count($route['url']) && $_SERVER['REQUEST_METHOD'] === strtoupper ($route['method']);});
        $posible_second = [];

        foreach ($posible_first as $route){
            $match = $this->check_url_match($route['url'],$this->uri);
            if($match){
                $posible_second[] = $route;
            }
        }
        if(count($posible_second)){
            $route      = $posible_second[count($posible_second)-1];
            $arguments  = $this->get_url_arguments($route['url'],$this->uri);
            $arguments  = array_merge($arguments,$this->get_body_arguments($route["method"]));
            $callback   = $route['callback'];
            if(is_callable($callback)){
                $callback(new Request($arguments));
            }elseif(is_string($callback)){
                $callback = explode('#',$callback);

                if(count($callback) != 2){
                    throw new FrameworkException("you need 2 args in route callback Controller#action");
                }
                $callback[0] = $this->controller_namespace.$callback[0];

                if(!method_exists(...$callback)){
                    throw new FrameworkException("Method or class not exists: ".implode('::',$callback));
                }

                $controller = new $callback[0]();
                $controller->{$callback[1]}(new Request($arguments));
            }else{
                throw new FrameworkException("error");
            }
        }else{
            header("HTTP/1.0 404 Not Found");
            die();
        }

    }

    private function check_url_match(array $url,array $uri){
        $count = 0;
        foreach ($url as $index => $argument){

            if($uri[$index] == $argument || Helper::is_argument($argument)){
                $count++;
            }
        }

        return $count == count($url);
    }

    private function get_url_arguments(array $url,array $uri){
        $args = [];
        foreach ($url as $index => $argument){

            if( Helper::is_argument($argument)){
                $args[Helper::remove_brackets($argument)] = $uri[$index];
            }
        }

        return $args;
    }

    private function get_body_arguments($method){
        $params = [];
        switch (strtoupper($method)){
            case "PUT":
                parse_str(file_get_contents('php://input'),$params);
                $params = array_merge($params,$_REQUEST);
                break;
            default:
                $params = $_REQUEST;
                break;
        }
        return $params;
    }


}