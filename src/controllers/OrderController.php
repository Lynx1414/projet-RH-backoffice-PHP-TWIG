<?php

namespace src\controllers;

use core\BaseController;
use src\models\OrderModel;

class OrderController extends BaseController
{
  private $orderModel;

  public function __construct()
  {
    parent::__construct();
    $this->orderModel = new OrderModel();
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
    // $idEmploy = $_GET['idEmploy'];
    $order = $this->orderModel->getOrder($orderId);
    $idEmploy = $order[""];
    //var_dump($order); die();
    $totalAmountOfOrder = $this->orderModel->getTotalAmount($orderId);
    //var_dump($totalAmountOfOrder); die();

    // TODO
    /*$this->orderModel->table = "employees";
    $soldeRestant= $this->orderModel->calculNewSolde($orderId, $employId);
    */
    $this->render('order/ordersByOne.html.twig', array(
      'titre' => $title,
      'order' => $order,
      'totalAmountOfOrder' => $totalAmountOfOrder
    ));
  }
}
