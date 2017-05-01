<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 06:24 PM
 * The best ;)
 */
use \MicroFramework\Core\Router;

Router::get("my/[new_arg]/url",function (){
    echo 1;
});

Router::post("my/url/other",function (){
    return 1;
});