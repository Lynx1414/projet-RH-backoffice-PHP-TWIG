<?php

namespace src\controllers;
// demarre la session et recupere la session
// session_start();


use core\BaseController;
use src\models\EmployeeModel;


class HomeController extends BaseController
{
  private $employeeModel;

  public function __construct()
  {
    //récupèrer le constructor de la class mère
    parent::__construct();
    //vers quel page ProductController va communiquer ici en locurrence avec HomeModel.php
    $this->employeeModel = new EmployeeModel();
  }

  // récupérer les données et charger la vue dans index.html.twig
  public function index()
  {
    $title = 'Connexion';
    // var_dump($login); die();
    $this->render('home/index.html.twig', array(
      'titre' => $title,
    ));
  }

  public function authentification()
  {
    //Vérifiez si le formulaire de connexion a été soumis
    if (($_SERVER['REQUEST_METHOD'] == 'POST') && !empty($_POST['mail']) && !empty($_POST['password'])) {
      //Vérifiez les informations de connexion de l'utilisateur
      $employMail = $_POST['mail'];
      $password = $_POST['password'];
      $login = $this->employeeModel->getConnectedEmployee($employMail, $password);
      if (($login != false)) {
        if ($login['Is_Admin']) {
          $_SESSION['login'] = $login;
          header('Location: /home');
        }
        else{
          $this->denyIfNotAdmin();
        }
      }
      else{
        $this->denyIfNotExists();
      }
    }
  }

  public function logout()
  {
    unset($_SESSION['login']);
    header('Location: /');
  }

  public function welcom(){
    $title= "Bienvenue";
    
    $this->render('home/home.html.twig', array(
      'titre' => $title,
    ));
  }
}
