<?php

  class Brand {
    private $id;
    private $brand;

    function __construct($brand, $id=null){
      $this->brand = $brand;
      $this->id = $id;
    }

    function getId(){
      return $this->id;
    }

    function setBrand($new_brand){
      $this->brand = (string) $new_brand;
    }

    function getBrand(){
      return $this->brand;
    }

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO brands (brand) VALUES ('{$this->getBrand()}');");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function getAll(){
      $brand_array = array();
      $executed = $GLOBALS['db']->query("SELECT * FROM brands;");
      $results = $executed->fetchAll(PDO::FETCH_ASSOC);
      foreach($results as $result){
        $brand = new Brand($result['brand'], $result['id']);
        array_push($brand_array, $brand);
      }
      return $brand_array;
    }

    static function brandNames(){
      $brand_name_array = array();
      $executed = $GLOBALS['db']->query("SELECT * FROM brands;");
      $results = $executed->fetchAll(PDO::FETCH_ASSOC);
      foreach($results as $result){
        array_push($brand_name_array, $result['brand']);
      }
      return $brand_name_array;
    }

    static function getBrands($store_id){
      $brand_array = array();
      $executed = $GLOBALS['db']->prepare("SELECT brands.* FROM brands JOIN stores_brands ON (stores_brands.brand_id = brands.id) JOIN stores ON (stores.id = stores_brands.store_id) WHERE stores.id = :id;");
      $executed->bindParam(':id', $store_id, PDO::PARAM_INT);
      $executed->execute();
      $results = $executed->fetchAll(PDO::FETCH_ASSOC);
      foreach($results as $result){
        $new_brand = new Brand($result['brand'], $result['id']);
        array_push($brand_array, $new_brand);
      }
      return $brand_array;
    }

    static function find($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM brands WHERE id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_INT);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      $brand = new Brand($result['brand'], $result['id']);
      return $brand;
    }

    function findStores(){
      $executed = $GLOBALS['db']->query("SELECT stores.* FROM stores JOIN stores_brands ON (stores_brands.store_id = stores.id) JOIN brands ON (stores_brands.brand_id = brands.id) WHERE brands.id = {$this->getId()};");
      $results = $executed->fetchAll(PDO::FETCH_ASSOC);
      return $results;
    }

    function saveStore($store_id){
      $executed = $GLOBALS['db']->prepare("INSERT INTO stores_brands (store_id, brand_id) VALUES (:id, {$this->getId()});");
      $executed->bindParam(':id', $store_id, PDO::PARAM_INT);
      $executed->execute();
      if($executed){
        return true;
      } else {
        return false;
      }
    }

    static function findBrandByName($brand){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM brands WHERE brand = :brand;");
      $executed->bindParam(':brand', $brand, PDO::PARAM_STR);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      $new_brand = new Brand($result['brand'], $result['id']);
      return $new_brand;
    }

    function delelte(){
      $executed = $GLOBALS['db']->exec("DELETE FROM brands WHERE id = {$this->getId()};");
      $executed = $GLOBALS['db']->exec("DELETE FROM stores_brands WHERE brand_id = {$this->getId()};");
      if($executed){
        return true;
      } else {
        return false;
      }
    }

  }

?>
