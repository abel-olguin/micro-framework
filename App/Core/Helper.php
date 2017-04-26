<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 26/04/2017
 * Time: 02:25 AM
 * The best ;)
 */

namespace MicroFramework\Core;


class Helper
{
    public static function get_scaped_values(array $arr){
        $scaped_values = [];

        foreach ($arr as $value){
            switch (gettype($value)){
                case "string":
                    $scaped_values[] = "'$value'";
                    break;
                case "integer":
                    $scaped_values[] = $value;
                    break;
                case "double":
                    $scaped_values[] = $value;
                    break;
                default:
                    $scaped_values[] = "'$value'";
                    break;
            }
        }

        return $scaped_values;
    }

    public static function is_assoc(array $arr){
        $keys = array_keys($arr);
        return  $keys !== array_keys($keys);
    }

}