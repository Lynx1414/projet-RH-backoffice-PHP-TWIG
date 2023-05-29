<?php

namespace src\controllers;

use core\BaseController;
use src\models\ProductModel;

class ProductController extends BaseController
{
  private $productModel;

  public function __construct()
  {
    //récupèrer le constructor de la class mère
    parent::__construct();
    //vers quel page ProductController va communiquer ici en locurrence avec productModel.php
    $this->productModel = new ProductModel();
    $this->denyIfNotAdmin(); // check if he is admin
  }

  // récupérer les données et charger la vue dans list.html.twig
  public function displayList()
  {
    $title = 'Produits disponibles';
    $products = $this->productModel->getAll();
    // var_dump($products); die();
    // echo "Ici nous aurons la liste des produits";
    $this->render('product/list.html.twig', array(
      'titre' => $title,
      'produits' => $products //penser au foreach pour boucler sur cet array à 2 dimensions
    ));
  }

  public function editProduct()
  {
    if (isset($_GET['id'])) {
      $title = 'Modifications Produits';
      $id = $_GET["id"]; // == ?id= de lign 37 de list.html.twig
      $product = $this->productModel->getOne($id);

      $this->render('product/updateProduct.html.twig', array(
        'title' => $title,
        'produit' => $product
      ));
    }
  }

  public function addStock()
  {
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      $this->productModel->changeStock($id, 10);

      header('Location: /products');
    }
  }

  //! affiche les nouveaux produits 
  public function insertProduct()
  {
    $title= 'Ajouter des nouveaux produits';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->productModel->add_product($_POST);
      header('Location: /products');
    }
    else{
      $this->render('product/insert.html.twig', array(
        'title' => $title

      ));
  }
}

  //modification des values de designation et/ou de prix
  public function updateDatabaseProducts()
  {
    $id = $_POST['id'];

    $designation = $_POST['designation'];
    $prix = $_POST['prix'];
    $statut = $_POST['statut'];
    $imgSrc = $_POST['imgSrc'];
    $imgAlt = $_POST['imgAlt'];
    $categorie = $_POST['categorie'];

    $this->productModel->insertChangeToDb($id, $designation, $prix, $statut, $imgSrc, $imgAlt, $categorie);
    header('Location: /products');
  }

  // ! JSON API________________________________
  public function displayAllProducts() //apiList
  {
    $allProducts = $this->productModel->getAll();
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    echo json_encode($allProducts);
  }

  public function consumeOne() //apiConsumeOne
  {
    $id = $_GET['id'];
    $this->productModel->changeStock($id, -1);
    $oneProduct = $this->productModel->getOne($id);
    header('Content-Type: application/json'); // indique le type de contenu réellement renvoyé convention json pour dire je t'envoie un objet de type json
    header("Access-Control-Allow-Origin: *");
    echo json_encode($oneProduct);
  }

  public function displayProductsByCategory()
  {
   $category= $_GET['category']; 
   $allByCategory= $this->productModel->getAllByCategory($category);
   header('Content-Type: application/json');
   header("Access-Control-Allow-Origin: *");
   echo json_encode($allByCategory);
  }
  // public function consumeMany(){//apiConsumeMany

  // } 

}
  // {
  //   
  //   ['designation' => 'croissant', 'prix' => 1];
  //   $dataProduct= $_POST['designation']; 
  //   $dataPrice= $_POST['prix'];
  //   $dataStock= $_POST['stock'];

  //   $this->productModel->add_product($_POST);
  //   $product->
  // }
