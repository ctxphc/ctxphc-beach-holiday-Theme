<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 11/24/2015
 * Time: 7:38 AM
 */

/*************
 * Tools for ooPHP
 ***********/

//method_exists( object or class name, method_name )
// Class name is Product and method is getPrice
return method_exists( "Product", "getPrice" );

$prd = new Product( "Shirt", 24.99, "Orange Men's Tee" );
return method_exists( $p, "getPrice" );


//is_subclass_of( object, class_name );

$s = new Soda();
is_subclass_of($s, "Product" );

