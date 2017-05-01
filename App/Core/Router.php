<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 05:32 PM
 * The best ;)
 */

namespace MicroFramework\Core;


class Router
{
    static private $instance = NULL;
    private $urls = [];

    static public function get_instance() {
        if (self::$instance == NULL) {
            self::$instance = new Router ();
        }
        return self::$instance;
    }

    public static function get($url,$callback){
        self::$instance->urls[] = ['method' => 'get','url' => array_filter(explode('/',$url)),'callback' => $callback];
    }

    public static function post($url,$callback){
        self::$instance->urls[] = ['method' => 'post','url' => array_filter(explode('/',$url)),'callback' => $callback];
    }

    public static function put($url,$callback){
        self::$instance->urls[] = ['method' => 'put','url' => array_filter(explode('/',$url)),'callback' => $callback];
    }

    public static function delete($url,$callback){
        self::$instance->urls[] = ['method' => 'delete','url' => array_filter(explode('/',$url)),'callback' => $callback];
    }

    public static function get_all_urls(){
        return self::$instance->urls;
    }


}