<?php
  date_default_timezone_set('America/Los_Angeles');
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Store.php";
  require_once __DIR__."/../src/Brand.php";

  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();

  $app['debug'] = true;

  $server = 'mysql:host=localhost;dbname=shoes';
  $user = 'root';
  $pass = 'root';
  $db = new PDO($server, $user, $pass);

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  $app->get("/", function () use ($app) {
    return $app['twig']->render('index.html.twig');
  });

  $app->get("/stores", function () use ($app) {
    return $app['twig']->render('stores.html.twig', array('stores'=>Store::getAll()));
  });

  $app->get("/brands", function () use ($app) {
    return $app['twig']->render('brands.html.twig', array('brands'=>Brand::getAll()));
  });

  $app->post("/addstore", function () use ($app) {
    if(!empty($_POST['store'])){
      $new_store = new Store($_POST['store']);
      $new_store->save();
      return $app['twig']->render('stores.html.twig', array('stores'=>Store::getAll()));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->post("/addbrand", function () use ($app) {
    if(!empty($_POST['brand'])){
      $brand_name_array = Brand::brandNames();
      if(in_array($_POST['brand'], $brand_name_array)){
        return $app['twig']->render('existed.html.twig');
      } else {
        $new_brand = new Brand($_POST['brand']);
        $new_brand->save();
        return $app['twig']->render('brands.html.twig', array('brands'=>Brand::getAll()));
      }
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->get("/store/{id}", function ($id) use ($app) {
    $store = Store::find($id);
    $all_brands = Brand::getAll();
    $brands = Brand::getBrands($id);

    foreach($brands as $brand){
      foreach($all_brands as $key=>$allbrand){
        if(in_array($brand, $all_brands)){
          array_splice($all_brands, $key, 1);
        }
      }
    }

    return $app['twig']->render('store.html.twig', array('store'=>$store, 'brands'=>$all_brands, 'storebrands'=>$brands));
  });

  $app->post("/addbrands", function () use ($app) {
    if(!empty($_POST['brand_id'])){
      $store = Store::find($_POST['store_id']);
      $brand_id_array = $_POST['brand_id'];
      foreach($brand_id_array as $brand_id){
        $store->saveBrand($brand_id);
      }
      $brands = Brand::getBrands($_POST['store_id']);
      return $app['twig']->render('result.html.twig', array('store'=>$store, 'brands'=>$brands));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->get("/brand/{id}", function ($id) use ($app) {
    $brand = Brand::find($id);
    $stores = $brand->findStores();
    $all_stores = Store::getAll();
    foreach($stores as $store){
      foreach($all_stores as $key=>$value){
        if($store['store'] == $value->getStore()){
          array_splice($all_stores, $key, 1);
        }
      }
    }
    return $app['twig']->render('brandinfo.html.twig', array('brand'=>$brand, 'stores'=>$stores, 'checkstores'=>$all_stores));
  });

  $app->get("/result/{id}", function ($id) use ($app) {
    return $app['twig']->render('result.html.twig', array('store'=>Store::find($id), 'brands'=>Brand::getBrands($id)));
  });

  $app->post("/brandaddstore", function () use ($app) {
    if(!empty($_POST['store_id'])){
      $store_id_array = $_POST['store_id'];
      $brand = Brand::find($_POST['brand_id']);
      foreach($store_id_array as $store_id){
        $brand->saveStore($store_id);
      }
      $stores = $brand->findStores();
      $all_stores = Store::getAll();
      foreach($stores as $store){
        foreach($all_stores as $key=>$value){
          if($store['store'] == $value->getStore()){
            array_splice($all_stores, $key, 1);
          }
        }
      }
      return $app['twig']->render('brandinfo.html.twig', array('brand'=>$brand, 'stores'=>$brand->findStores(), 'checkstores'=>$all_stores));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->post("/deletebrand", function () use ($app) {
    if(!empty($_POST['brand'])){
      $brand = Brand::findBrandByName($_POST['brand']);
      $brand->delelte();
      return $app['twig']->render('brands.html.twig', array('brands'=>Brand::getAll()));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  return $app;
?>
