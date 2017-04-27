<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 26/04/2017
 * Time: 12:26 AM
 * The best ;)
 */

namespace MicroFramework\Core;


class Model extends DB
{
    public $attributes  = [];
    private $query      = '';
    protected $id       = 'id';

    public function __construct(array $atts = [])
    {
        parent::__construct();
        if(!(Helper::is_assoc($atts) || !count($atts))){
            throw new FrameworkException('You can\'t put multiple records in constructor method, use insert method');
        }

        if(count($atts)){
            $this->set_attributes($atts);
            $this->make_insert_query($this->attributes);
        }

    }

    public static function create(array $atts){
        $class      = get_called_class();
        $instance   = new $class($atts);

        $id = $instance->save();

        $instance->set_attribute("id",$id);

        return $instance;
    }

    public static function insert(array $arr){
        $class      = get_called_class();

        if(Helper::is_assoc($arr)){
                
                return self::new_insert_instance($class,$arr);
        }else{
            $instances = [];
            foreach ($arr as $attributes) {
                $instance = self::new_insert_instance($class,$attributes);
                $instances[] = $instance;
            }
            return $instances;
        }
                    
    }

    public static function insert_batch(array $arr){
        $class      = get_called_class();

        if(Helper::is_assoc($arr)){
                
            return false;
        }else{
            $instance   = new $class([]);
            $response   = $instance->db_insert($instance->make_insert($arr));

            return $response?true:false;
        }
                    
    }

    public function save(){
    
        echo $this->query;//run query
    }
    /*-------------------------------------------------------------------------------------------------------------
     -
     -
     -                                                Query methods
     -
     -
     ---------------------------------------------------------------------------------------------------------------*/

     private function make_insert(array $arr){

        if(Helper::is_assoc($arr)){
            $keys   = $this->get_query_keys($arr);
            $values = $this->get_query_values($arr);
        }else{
            $greater_arr    = $this->get_greater_array($arr);
            $keys           = $this->get_query_keys($greater_arr);
            $tmp            = [];

            foreach ($arr as $attributes) {
                $prev = [];
                foreach ($greater_arr as $key => $value) {
                    $prev[] = isset($attributes[$key])?$attributes[$key]:null;
                }

                $tmp[] = $this->get_query_values($prev);
            }
            $values = implode(') , (', $tmp);
        }
        
        return "INSERT INTO {$this->table_name} ($keys) VALUES ($values)";
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

    private function make_insert_query(array $arr){
        $this->query = $this->make_insert($arr);
    }

    private function new_insert_instance($class,$arr){
        $instance   = new $class($arr);
        $id         = $instance->db_insert($instance->make_insert($arr));
        $instance->set_attribute("id",$id);
        return $instance;
    }

    private function get_greater_array($arr){
        arsort($arr);

        return array_values($arr)[0];
    }
    private function get_query_keys(array $arr){
        return  implode(',',array_keys($arr));
    }

     private function get_query_values(array $arr){
        return implode(',',Helper::get_scaped_values(array_values($arr)));
     }


}