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

  class StoreTest extends PHPUnit_Framework_TestCase {

    protected function tearDown(){
      Brand::deleteAll();
      Store::deleteAll();
      $GLOBALS['db']->exec("DELETE FROM stores_brands;");
    }

    function testSave () {
      $name = "Store1";
      $new_store = new Store($name);
      $result = $new_store->save();
      $this->assertTrue($result, "Fail");
    }

    function testGetAll(){
      $name = "Nike";
      $name1 = "Puma";
      $new_store = new Store($name);
      $new_store1 = new Store($name1);
      $new_store->save();
      $new_store1->save();
      $result = Store::getAll();
      $this->assertEquals([$new_store, $new_store1], $result);
    }

    function testFindById(){
      $name = "Store";
      $new_store = new Store($name);
      $new_store->save();
      $result = Store::find($new_store->getId());
      $this->assertEquals($new_store,$result);
    }



  }

?>
