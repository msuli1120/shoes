<?php

  /**
  * @backupGlobals disabled
  * @backupStaticAttributes disabled
  */

  require_once "src/Brand.php";
  require_once "src/Store.php";

  $server = 'mysql:host=localhost;dbname=shoes_test';
  $user = 'root';
  $pass = 'root';

  $db = new PDO($server, $user, $pass);

  class BrandTest extends PHPUnit_Framework_TestCase {

    protected function tearDown(){
      Brand::deleteAll();
      Store::deleteAll();
      $GLOBALS['db']->exec("DELETE FROM stores_brands;");
    }

    function testSave () {
      $name = "Nike";
      $new_brand = new Brand($name);
      $result = $new_brand->save();
      $this->assertTrue($result, "Fail");
    }

    function testGetAll(){
      $name = "Nike";
      $name1 = "Puma";
      $new_brand = new Brand($name);
      $new_brand1 = new Brand($name1);
      $new_brand->save();
      $new_brand1->save();
      $result = Brand::getAll();
      $this->assertEquals([$new_brand, $new_brand1], $result);
    }

    function testBrandNames(){
      
    }




  }

?>