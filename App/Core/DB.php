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

    public function __destruct(){
    	$this->connection->close();
    }

    protected function db_insert($query){

        if( $results = $this->connection->query($query)){
            
		    if($this->connection->affected_rows === 0)
		    {
		    	return false;
		    }
		    elseif($this->connection->affected_rows == 1)
		    {
		        return $this->connection->insert_id; 
		    }else{
		    	return true;
		    }
			
        }else{
        	return false;
        }
    }

    protected function db_update($query){
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