<?php

/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 09:24 PM
 * The best ;)
 */
namespace MicroFramework\Http\Controllers;
use MicroFramework\Core\Request;

class Controller
{

    public function sample(Request $request){

        var_dump($request->get("arg"));
    }
}