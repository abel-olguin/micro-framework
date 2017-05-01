<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 05:34 PM
 * The best ;)
 */

namespace MicroFramework\Core;


class UrlManager
{
    public static function get_uri(){
        $uri     = str_replace('?'.self::get_query(),'',$_SERVER['REQUEST_URI']);
        $uri_arr = explode('/',$uri);

        if(in_array('public',$uri_arr)){
            $pub     = array_search('public',$uri_arr);

            $uri_arr = array_slice($uri_arr,$pub+1);
        }

       return array_filter($uri_arr);
    }

    public static function get_query(){

        return $_SERVER['QUERY_STRING'];
    }
}