<?php
  class Store {
    private $id;
    private $store;

    function __construct($store, $id=null){
      $this->store = $store;
      $this->id = $id;
    }

    function getId(){
      return $this->id;
    }

    function setStore($new_store){
      $this->store = (string) $new_store;
    }

    function getStore(){
      return $this->store;
    }

    function save($store){
      $executed = $GLOBALS['db']->prepare("INSERT INTO stores (store) VALUES (:store);");
      $executed->bindParam(':store', $store, PDO::PARAM_STR);
      $executed->execute();
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function getAll(){
      $store_array = array();
      $executed = $GLOBALS['db']->query("SELECT * FROM stores;");
      $results = $executed->fetchAll(PDO::FETCH_ASSOC);
      foreach($results as $result){
        $store = new Store($result['store'], $result['id']);
        array_push($store_array, $store);
      }
      return $store_array;
    }

    static function find($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM stores WHERE id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_INT);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      $store = new Store($result['store'], $result['id']);
      return $store;
    }

    function saveBrand($brand_id){
      $executed = $GLOBALS['db']->prepare("INSERT INTO stores_brands (store_id, brand_id) VALUES ({$this->getId()}, :id);");
      $executed->bindParam(':id', $brand_id, PDO::PARAM_INT);
      $executed->execute();
      if($executed){
        return true;
      } else {
        return false;
      }
    }

    Static function deleteAll(){
        $executed = $GLOBALS['db']->exec("DELETE FROM stores;");
    }
  }
?>
