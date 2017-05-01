<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 06:24 PM
 * The best ;)
 */
use \MicroFramework\Core\Router;

Router::get("my/[arg]/url",'Controller#sample');

Router::get("my/url/other",function (){
    echo 1;
});