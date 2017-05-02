<?php
namespace MicroFramework\Http\Controllers;

use MicroFramework\Core\ApiController;
use MicroFramework\Core\Request;

class Controller extends ApiController
{

    public function sample(Request $request){

        $this->ok(["data" =>$request->all()]);
    }
}