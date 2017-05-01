<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 07:36 PM
 * The best ;)
 */

namespace MicroFramework\Core;


class Request
{
    private $attributes;
    public function __construct($atts)
    {
        $this->attributes = $atts;
    }

    public function get($key){
        return isset($this->attributes["key"])?$this->attributes["key"]:null;
    }
}