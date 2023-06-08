<?php

namespace src\models;

use core\BaseModel;
use PDO;

//REQUETES SQL POUR EMPLOYES

class EmployeeModel extends BaseModel
{
  public $table = "employees";
  public $newSolde;
  private $id;

  public function getConnectedEmployee($employMail, $password)
  {
    $sql = 'SELECT mail, Is_Admin FROM ' . $this->table . ' WHERE mail = :email AND Password = :password';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':email', $employMail);
    $query->bindParam(':password', $password);
    $query->execute();
    return $query->fetch();
  }

  public function insertChangetoEmployeDb($id, $service, $nom, $prenom, $mail, $solde, $is_admin, $dateReappro)
  {
    $sql = 'UPDATE ' . $this->table . ' SET Service = :service, Nom = :nom, Prenom = :prenom, mail = :mail, Solde = :solde, Is_Admin = :is_admin, Date_reappro = :dateReappro  WHERE id = :id ';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $id);
    $query->bindParam(':service', $service);
    $query->bindParam(':nom', $nom);
    $query->bindParam(':prenom', $prenom);
    $query->bindParam(':mail', $mail);
    $query->bindParam(':solde', $solde);
    $query->bindParam(':is_admin', $is_admin);
    $query->bindParam(':dateReappro', $dateReappro);
    $query->execute();
  }

  public function getOrdersByemployee($id)
  {
    $sql = 'SELECT  employees.ID, employees.Solde, orders.ID AS Num_of_order, orders.Date_of_order, orders.Amount, product_order.Quantite, products.Designation, products.Prix, products.Stock, products.Statut FROM employees 
    JOIN orders ON employees.ID = orders.Employees_ID 
    JOIN product_order ON product_order.orders_ID = orders.ID 
    JOIN products ON product_order.products_ID = products.ID
    WHERE Employees_ID = :id AND products.Stock != 0 AND products.Statut !=0
    ORDER BY Num_of_order ASC';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  //! JSON_________________________________________________________________
  public function updateSoldeRegardingSumCart($id, $sumCart)
  {
    $sql = "UPDATE employees SET Solde = Solde - :sum WHERE id = :id AND Solde >= :sum ";
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':sum', $sumCart);
    $query->bindParam(':id', $id);
    $query->execute();
    $count = $query->rowCount();
    return $count;
  }
}
