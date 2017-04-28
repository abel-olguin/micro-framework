<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 26/04/2017
 * Time: 12:26 AM
 * The best ;)
 */

namespace MicroFramework\Core;


abstract class Model extends DB
{
    public $attributes  = [];
    protected $id       = 'id';

    public function __construct(array $atts = [])
    {
        parent::__construct();

        $this->fill($atts);
    }



    public static function create(array $atts){
        $class      = get_called_class();
        $instance   = new $class($atts);

        $id = $instance->save();

        $instance->set_attribute("id",$id);

        return $instance;
    }

    public static function insert(array $arr){
        if(Helper::is_assoc($arr)){
                
                return self::new_insert_instance($arr);
        }else{
            $instances = [];
            foreach ($arr as $attributes) {
                $instance = self::new_insert_instance($attributes);
                $instances[] = $instance;
            }
            return $instances;
        }
                    
    }

    public static function insert_batch(array $arr){
        $instance = static::new_instance();

        if(Helper::is_assoc($arr)){
            throw new FrameworkException("Only for massive assign");
        }else{
            QueryBuilder::make_insert($arr,$instance->get_table_name());
            $response   = $instance->db_insert(QueryBuilder::get_query());
            return $response?true:false;
        }
                    
    }

    public static function where($array_or_key,$operator = "=", $value = null){
        QueryBuilder::add_where();
        $instance = static::new_instance();
        if(is_array($array_or_key)){
            $to_in = is_array($array_or_key[0])?$array_or_key:[$array_or_key];
            QueryBuilder::array_to_query($to_in);
        }else{
            QueryBuilder::array_to_query([[$array_or_key,$operator, $value]]);
        }

        return $instance;
    }

    public function get(){
        QueryBuilder::add_select("*",$this->get_table_name());
        $results = null;
        foreach ($this->get_results(QueryBuilder::get_query()) as $atts){
            $results[] = static::new_instance($atts);
        }

        return $results;
    }

    public function first(){
        QueryBuilder::add_select("*",$this->get_table_name(),1);
        QueryBuilder::limit(1);
        $result = $this->get_results(QueryBuilder::get_query());
        return isset($result[0])?static::new_instance($result[0]):null;
    }

    public function save(){
        QueryBuilder::make_insert($this->attributes,$this->get_table_name());
        $id         = $this->db_insert(QueryBuilder::get_query());
        if(is_bool($id) === false){
            $this->set_attribute("id",$id);
        }elseif(!$id){
            throw new FrameworkException("Error");
            
        }
        
        return $this;
    }


    public function get_table_name(){
        return $this->table_name;
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



    protected function get_attribute_value($name){
        if(array_key_exists($name,$this->attributes)){
            return $this->attributes[$name];
        }
    }

    /*-------------------------------------------------------------------------------------------------------------
     -
     -
     -                                                Private methods
     -
     -
     ---------------------------------------------------------------------------------------------------------------*/
    private function has_mutator($name){
        return method_exists($this,$this->get_mutator($name));
    }

    private function get_mutator($name){
        return "get_".$name."_attribute";
    }


    private static function new_instance($arr = []){
        $class      = get_called_class();
        return new $class($arr);
    }

    private function new_insert_instance($arr){

        $instance   = static::new_instance($arr);
        QueryBuilder::make_insert($arr,$instance->get_table_name());
        $id         = $instance->db_insert(QueryBuilder::get_query());
        if(is_bool($id) === false){
            $instance->set_attribute("id",$id);
            return $instance;
        } 
        return [];
    }

    private function fill($atts){
        if(!(Helper::is_assoc($atts) || !count($atts))){
            throw new FrameworkException('You can\'t put multiple records in constructor method, use insert method');
        }
        if(count($atts)){
            $this->set_attributes($atts);
        }
    }


}