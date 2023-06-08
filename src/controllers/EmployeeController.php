<?php

namespace src\controllers;

use core\BaseController;
use core\libs\Utilitaire;
use src\models\EmployeeModel;

class EmployeeController extends BaseController
{
  private $employeeModel;
  public $validateData;

  public function __construct()
  {
    parent::__construct(); //récupèrer le constructor de la class mère BaseController
    $this->employeeModel = new EmployeeModel; //vers quel page ProductController va communiquer ici en locurrence avec employeeModel.php
    $this->denyIfNotAdmin(); // check if he is admin
  }

  public function displayAllEmployees()
  {
    $title = 'Liste d\'employes';
    $employees = $this->employeeModel->getAll();
    $this->render('employee/employee.html.twig', array( // compact('title', 'employees');
      'titre' => $title,
      'employes' => $employees // 'employes' == key qui a une valeur $employees qui est un tableau  qui est la penser au foreach sur employee.html.twig pour boucler sur cet array 
    ));
  }

  public function editEmployee()
  {
    if (isset($_GET['id'])) {
      $title = 'Edition employe';
      $id = $_GET["id"];
      $employees = $this->employeeModel->getOne($id);

      $this->render('employee/updateEmployee.html.twig', array( // compact('title', 'employees');
        'titre' => $title,
        'employes' => $employees // 'employes' == key qui a une valeur $employees qui est un tableau  qui est la penser au foreach sur employee.html.twig pour boucler sur cet array 
      ));
    }
  }

  //modification des values d'un employe
  public function updateDatabaseEmploy()
  {
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
      $service = $_POST['service'];
      $nom = $_POST['nom'];
      $prenom = $_POST['prenom'];
      $mail = $_POST['mail'];
      if (!Utilitaire::validateMail($mail)) {
        $msg = "Email address '$mail' is considered invalid.\n";
        $this->render('employee/updateEmployee.html.twig', array(
          // 'employee' => ['ID' => $id]// {{employee.ID}}
          // 'employes' => $_POST, si nom de colonne de table employes est en minuscule comme les name de chq $_post
          'employes' => [
            'ID' => $id,
            'Service' => $service,
            'Nom' => $nom,
            'Prenom' => $prenom,
            'mail' => $mail,
            'Solde' => $_POST['solde'],
            'Is_Admin' => $_POST['is_admin'],
            'Date_reappro' => $_POST['date_reappro']
          ],
          'msg' => $msg
        ));
      } else {
        $solde = $_POST['solde'];
        $isAdmin = $_POST['is_admin'];
        $dateReappro = $_POST['date_reappro'];
        $this->employeeModel->insertChangetoEmployeDb($id, $service, $nom, $prenom, $mail, $solde, $isAdmin, $dateReappro);
        header('Location:/employees');
      }
    }
  }

  public function displayOrdersByemployee()
  {
    $title = 'Historique de commandes';
    $id = $_GET['id'];
    if (isset($_GET['id'])) {
      $ordersByEmployee = $this->employeeModel->getOrdersByemployee($id);

      // var_dump($ordersByEmployee); die();
      $this->render('employee/ordersByEmploye.html.twig', array(
        'titre' => $title,
        'ordersByEmploye' => $ordersByEmployee
      ));
    }
  }

  public function getAllEmployees()
  {
    $employees = $this->employeeModel->getAll();
    header('Content-Type: application/json'); // indique le type de contenu réellement renvoyé convention json pour dire je t'envoie un objet de type json
    header("Access-Control-Allow-Origin: *");
    echo json_encode($employees);
  }

  // ! JSON API________________________________
  public function displayDetailsEmployee()
  {
    if (isset($_GET['id'])) {
      $id = $_GET['id'];

      $oneEmployee = $this->employeeModel->getOne($id);
      header('Content-Type: application/json');
      header("Access-Control-Allow-Origin: *");
      echo json_encode($oneEmployee);
    }
  }

  public function displayOrdersHistory()
  {
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      $ordersByEmployee = $this->employeeModel->getOrdersByemployee($id);
      // var_dump($ordersByEmployee); die();
      header('Content-Type: application/json');
      header("Access-Control-Allow-Origin: *");
      echo json_encode($ordersByEmployee);
    }
  }
}
