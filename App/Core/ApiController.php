<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 30/04/2017
 * Time: 09:57 PM
 * The best ;)
 */

namespace MicroFramework\Core;


class ApiController
{
    public function ok($arr = []){
        $this->set_header(200);
        $this-$this->json_response($arr);
    }

    public function error($arr = []){
        $this->set_header(500);
        $this->json_response($arr);
    }

    public function not_found($arr = []){
        $this->set_header(404);
        $this->json_response($arr);
    }

    private function set_header($code){
        http_response_code($code);

    }

    private function json_response($arr){
        die(json_encode($arr));
    }
}