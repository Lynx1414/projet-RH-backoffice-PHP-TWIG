<?php

namespace src\models;

use core\BaseModel;
use PDO;

//REQUETES SQL POUR EMPLOYES

class EmployeeModel extends BaseModel
{
  public $table = "employees";
  private $id;
  // private $service;
  // private $nom;
  // private $prenom;
  // private $mail;
  // private $password;
  // private $solde;
  // private $is_admin;


  public function getConnectedEmployee($employMail, $password)
  {
    $sql = 'SELECT mail, Is_Admin FROM ' . $this->table . ' WHERE mail = :email AND Password = :password';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':email', $employMail);
    $query->bindParam(':password', $password);
    $query->execute();
    return $query->fetch();
    //Si les informations sont correctes
    // if($employMail == )

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
    $sql = 'SELECT  employees.ID, employees.Solde, orders.ID AS Num_of_order, orders.Date_of_order, product_order.Quantite, products.Designation, products.Prix FROM employees 
    JOIN orders ON employees.ID = orders.Employees_ID 
    JOIN product_order ON product_order.orders_ID = orders.ID 
    JOIN products ON product_order.products_ID = products.ID
    WHERE Employees_ID = :id';
    $query= $this->_connexion->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }


}
