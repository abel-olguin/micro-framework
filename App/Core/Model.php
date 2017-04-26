<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 26/04/2017
 * Time: 12:26 AM
 * The best ;)
 */

namespace MicroFramework\Core;


class Model
{
    public $attributes = [];
    private $query;
    protected $id = 'id';

    public function __construct(array $atts)
    {
        $this->set_attributes($atts);
    }

    public static function create(array $atts){
        $class      = get_called_class();
        $instance   = new $class($atts);

        $id = $instance->insert();

        $instance->set_attribute("id",$id);

        return $instance;
    }

    public function save(){
        $id     = $this->get_attribute_value($this->id);
        $where  = $id?" WHERE {$this->id} = $id":"";
        $query  = "INSERT INTO {$this->table_name} VALUES (...)".$where;

        echo $query;
    }

    private function insert(){

        $keys   = implode(',',array_keys($this->attributes));
        $values = implode(',',Helper::get_scaped_values(array_values($this->attributes)));
        echo "INSERT INTO {$this->table_name} ($keys) VALUES ($values)";
        return 1;
    }


    /*-------------------------------------------------------------------------------------------------------------
     -
     -
     -                                                Magic methods
     -
     -
     ---------------------------------------------------------------------------------------------------------------*/
    public function __get($name)
    {
        return $this->get_attribute($name);
    }

    public function __set($name, $value)
    {
        $this->set_attribute($name,$value);
    }

    public function __isset($name)
    {
        return $this->isset_attribute($name);
    }

    public function __unset($name)
    {
        unset($this->attributes[$name]);
    }

    public function __toString()
    {
        return json_encode($this->attributes);
    }

    /*-------------------------------------------------------------------------------------------------------------
     -
     -
     -                                                Protected methods
     -
     -
     ---------------------------------------------------------------------------------------------------------------*/
    protected function get_attribute($name){
       $value   = $this->get_attribute_value($name);

       if($this->has_mutator($name)){
           return $this->{$this->get_mutator($name)}($value);
       }

       return $value;
    }


    protected function set_attribute($name,$value){
        $this->attributes[$name] = $value;
    }

    protected function isset_attribute($name){
        return isset($this->attributes[$name]);
    }

    protected function set_attributes(array $atts){
        $this->attributes = $atts;
    }

    /*-------------------------------------------------------------------------------------------------------------
     -
     -
     -                                                Private methods
     -
     -
     ---------------------------------------------------------------------------------------------------------------*/
    protected function has_mutator($name){
        return method_exists($this,$this->get_mutator($name));
    }

    protected function get_mutator($name){
        return "get_".$name."_attribute";
    }

    protected function get_attribute_value($name){
        if(array_key_exists($name,$this->attributes)){
            return $this->attributes[$name];
        }
    }

}