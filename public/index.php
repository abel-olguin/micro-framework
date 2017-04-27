<?php
/**
 * Created by Vendetta.
 * User: Vendetta
 * Date: 26/04/2017
 * Time: 01:23 AM
 * The best ;)
 */


require_once(__DIR__ . '/../vendor/autoload.php');

use MicroFramework\Models\Sample;

$sample = Sample::insert([["description" => "wea"],["description" => "wea2"]]);

