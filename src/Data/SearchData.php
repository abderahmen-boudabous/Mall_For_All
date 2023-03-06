<?php
namespace App\Data;

use App\Entity\Shop;
use App\Entity\Product;


class SearchData
{
/** 
 *   @var string 
*/
 public $q = '' ;

 /**
 * @var Shop[]
 */
 public $shop = [];

 /**
 * @var null|integer
 */ 
 public $max;

 /**
*@var null|integer
*/
public $min;

/**
* @var boolean
*/
public $stock = false;

}