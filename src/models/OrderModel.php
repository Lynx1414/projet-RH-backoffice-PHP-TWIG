<?php

namespace src\models;

use core\BaseModel;
use PDO;
use PDOException;

//REQUETES SQL POUR PRODUITS

class OrderModel extends BaseModel
{
  public $table = 'orders';
  protected $total_price;

  public function getOrder($orderId)
  {
    $sql = 'SELECT employees.ID, employees.Nom, employees.Prenom, employees.Solde, orders.ID AS Num_of_order, orders.Date_of_order, product_order.Quantite, products.Designation, products.Prix, products.Stock FROM employees 
    JOIN orders ON employees.ID = orders.Employees_ID 
    JOIN product_order ON product_order.orders_ID = orders.ID 
    JOIN products ON product_order.products_ID = products.ID
    WHERE orders.ID = :id';
     
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $orderId);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTotalAmount($orderId)
  {
    $sql = 'SELECT SUM(products.Prix) As TotalPrice
    FROM products 
    INNER JOIN product_order 
    ON products.ID = product_order.Products_ID 
    INNER JOIN orders 
    ON orders.ID = product_order.Orders_ID
    INNER JOIN employees 
    ON employees.ID = orders.Employees_ID
    WHERE orders.ID = :id';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $orderId);
    $query->execute();
    $total_price = $query->fetch();
    return $total_price; 
  }

  //! JSON ________________________________________________________________

  public function insertOrder($id, $sumCart)
  {
    $sql = 'INSERT INTO orders (Employees_ID, Amount ) VALUES (:id , :sumCart)';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $id);
    $query->bindParam(':sumCart', $sumCart);
    $query->execute();
    $lastOrderId = $this->_connexion->lastInsertId();
    return $lastOrderId;
  }

  public function insertProductOrder($lastOrderId, $arrayId)
  {
    foreach ($arrayId as $productId) {
      $sql = 'INSERT INTO product_order (orders_ID, products_ID) VALUES (:order_id, :product_id)';
      $query = $this->_connexion->prepare($sql);
      $query->bindParam(':order_id', $lastOrderId);
      $query->bindParam(':product_id', $productId);
      // return $this->debugDumpParam();
      $query->execute();
    }
  }

}
