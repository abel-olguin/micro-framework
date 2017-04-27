<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 26/04/2017
 * Time: 12:26 AM
 * The best ;)
 */

namespace MicroFramework\Core;


class DB
{
    private $connection;

    public function __construct()
    {
        $this->make_connection();
    }

    protected function excecute($query){
        if($this->connection->query($query)){
            return true;
        }
    }

    private function make_connection(){
        $this->connection = new \mysqli(config("host"),config("user"),config("pass"),config("db"),config("port"));

        if ($this->connection->connect_error) {
            throw new FrameworkException("Connection error: ({$this->connection->connect_errno}) {$this->connection->connect_error}");
        }
    }
}