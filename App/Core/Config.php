<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 26/04/2017
 * Time: 12:26 AM
 * The best ;)
 */

$GLOBALS["config"] = json_decode(file_get_contents(__DIR__.'/../Config/config.json'),true);

function config($key,$default = null){
    $config = $GLOBALS["config"];
    if(isset($config[$key])){
        return $config[$key];
    }

    return $default;
}