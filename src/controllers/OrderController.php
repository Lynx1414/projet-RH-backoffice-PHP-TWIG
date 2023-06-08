<?php

namespace src\controllers;

use core\BaseController;
use src\models\EmployeeModel;
use src\models\OrderModel;
use src\models\ProductModel;

class OrderController extends BaseController
{
  private $orderModel;
  private $productModel;
  private $employeeModel;

  public function __construct()
  {
    parent::__construct();
    $this->orderModel = new OrderModel();
    $this->productModel = new ProductModel();
    $this->employeeModel = new EmployeeModel();
  }

  public function displayAllOrders()
  {
    $title = 'Historique de commandes';
    $orders = $this->orderModel->getAll();
    // var_dump($orders); die();
    $this->render('order/order.html.twig', array(
      'titre' => $title,
      'orders' => $orders //penser au foreach pour boucler sur cet array à 2 dimensions
    ));
  }

  public function displayDetailsOrderByEmploye()
  {
    $title = 'Détails de commande';
    $orderId = $_GET['id'];
    $order = $this->orderModel->getOrder($orderId);
    //var_dump($order); die();
    $totalAmountOfOrder = $this->orderModel->getTotalAmount($orderId);
    //var_dump($totalAmountOfOrder); die();
    $this->render('order/ordersByOne.html.twig', array(
      'titre' => $title,
      'order' => $order,
      'totalAmountOfOrder' => $totalAmountOfOrder
    ));
  }

  // ! JSON API________________________________
  // reçoit le tableau d'objet json du Front panier (function pay()) 
  public function placeOrder()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    $postData = file_get_contents('php://input');

    $data = json_decode($postData);

    $products = json_decode($data->products);
    $employee = json_decode($data->employee);
    $arrayId = [];

    // seules infos à récupèrer : id employee et id products
    // (pas les prix, pas le solde car peuvent être modifiés dans inspecter - Application)
    $nbElem = count($products);
    $id = $employee->ID;
    $sumCart = 0;
    $lastOrderId= 0;
    foreach ($products as $index) {
      $arrayId[] = $index->ID;
    }

    // echo '{
    //   "name": "$employee->Prenom",
    //   "surname": "$employee->Nom"
    // }';
    //   die();

    //todo 1. calculer la somme (avec les id des produits)
    $sumCart = $this->productModel->calculateSumCart($arrayId);
    //return sumCart[0]
    //todo 2. tester de faire un update et voir avec rowcount s'il a fonctionné
    $count = $this->employeeModel->updateSoldeRegardingSumCart($id, $sumCart);
    //return $count;
    //todo 3. en fonction de ce rowcount renvoyer une réponse sous forme de json en fonction 
    //echo json_encode($message);
    header('Content-Type: application/json');
    if ($count >= 1) {
      $lastOrderId= $this->orderModel->insertOrder($id, $sumCart);
      $this->orderModel->insertProductOrder($lastOrderId, $arrayId);

      foreach ($products as $index) {
        $id = $index->ID;
        $this->productModel->updateProductRegardingOrder($id, 1);
      };

      $message = "Votre commande de " . $nbElem . " article(s)  a ete <span class='orange'> acceptee !<span>";
      echo json_encode($message);
    } else {
      $message = "Votre commande n'est pas acceptee.";
      echo json_encode($message);
    }
  }
}
