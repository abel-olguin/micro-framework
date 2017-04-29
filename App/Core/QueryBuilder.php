<?php

/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 27/04/2017
 * Time: 11:00 PM
 * The best ;)
 */
namespace MicroFramework\Core;

class QueryBuilder
{
    private static $query = "";

    public static function get_query(){
        return self::$query;
    }
    public static function add_where(){
        self::$query .= " WHERE ";
    }

    public static function add_or(){

        if(strpos(self::$query,"WHERE") !== false){
            self::$query .= " OR ";
        }else{
            throw new FrameworkException("To use or_where is necessary use where() before");
        }

    }

    public static function add_select($fields = "*",$table){
        self::$query = "SELECT $fields FROM $table ".self::$query;
    }

    public static function limit($limit){
        self::$query .= $limit?" LIMIT $limit ":"";
    }


    public static function array_to_query(array $arr,$separator = 'AND'){
        $query = [];
        foreach ($arr as $value){

            if(count($value) == 2){
                $key = $value[0];
                if(gettype($key) == "string"){
                    $query[] = self::get_equal_query($key,"=",$value[1]);
                }else{
                    throw new FrameworkException("you need string key");
                }

            }elseif(count($value) == 3){

                $query[] = self::get_equal_query($value[0],$value[1],$value[2]);
            }else{
                throw new FrameworkException("arguments doesn't match, you need 3 fields on array [key,operator,value]");
            }

        }

        self::$query .= implode(" $separator ",$query);
    }

    public static function make_insert(array $arr,$table_name){
        $keys   = self::get_query_keys($arr);
        $values = self::get_query_values($arr);

        self::$query = "INSERT INTO $table_name ($keys) VALUES ($values)";
    }

    public static function make_insert_batch(array $arr,$table_name){
        $greater_arr    = self::get_greater_array($arr);
        $keys           = self::get_query_keys($greater_arr);
        $tmp            = [];

        foreach ($arr as $attributes) {
            $prev = [];
            foreach ($greater_arr as $key => $value) {
                $prev[] = isset($attributes[$key])?$attributes[$key]:null;
            }

            $tmp[] = self::get_query_values($prev);
        }

        $values = implode(') , (', $tmp);

        self::$query = "INSERT INTO $table_name ($keys) VALUES ($values)";

    }

    public static function make_update(array $arr,$table_name){

        $values = self::assoc_to_query($arr);

        self::$query = "UPDATE $table_name SET $values ".self::$query;
    }

    private static function assoc_to_query($arr,$separator = ','){
        $result = [];
        foreach ($arr as $key => $value){
            $result[] = self::get_equal_query($key,'=',$value);
        }

        return implode($separator,$result);
    }

    private static function get_equal_query($key,$operator = "=",$value){
        $query = "";
        switch (gettype($value)){
            case "string":
                $query = " $key $operator '$value'";
                break;
            case "integer":
                $query = " $key $operator $value";
                break;
            case "double":
                $query = " $key $operator $value";
                break;
            case "NULL":
                $query = " $key $operator NULL";
                break;
            default:
                $query = " $key $operator '$value'";
                break;
        }
        return $query;
    }
    private static function get_query_keys(array $arr){
        return  implode(',',array_keys($arr));
    }

    private static function get_query_values(array $arr){
        return implode(',',Helper::get_scaped_values(array_values($arr)));
    }

    private static function get_greater_array($arr){
        arsort($arr);

        return array_values($arr)[0];
    }

}