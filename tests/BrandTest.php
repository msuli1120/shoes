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
      $result = $new_brand->save($name);
      $this->assertTrue($result, "Fail");
    }

    function testGetAll(){
      $name = "Nike";
      $name1 = "Puma";
      $new_brand = new Brand($name);
      $new_brand1 = new Brand($name1);
      $new_brand->save($name);
      $new_brand1->save($name1);
      $result = Brand::getAll();
      $this->assertEquals([$new_brand, $new_brand1], $result);
    }

    function testBrandNames(){
      $name = "Nike";
      $name1 = "Puma";
      $new_brand = new Brand($name);
      $new_brand1 = new Brand($name1);
      $new_brand->save($name);
      $new_brand1->save($name1);
      $result = Brand::brandNames();
      $this->assertEquals(['Nike', 'Puma'], $result);
    }

    function testFindById(){
      $name = "Nike";
      $new_brand = new Brand($name);
      $new_brand->save($name);
      $result = Brand::find($new_brand->getId());
      $this->assertEquals($new_brand,$result);
    }

    function testFindByName(){
      $name = "Nike";
      $new_brand = new Brand($name);
      $new_brand->save($name);
      $result = Brand::findBrandByName($name);
      $this->assertEquals($new_brand,$result);
    }

    function testDelete(){
      $name = "Nike";
      $new_brand = new Brand($name);
      $new_brand->save($name);
      $result = $new_brand->delete();
      $this->assertEquals('', $result);
    }

  }

?>
