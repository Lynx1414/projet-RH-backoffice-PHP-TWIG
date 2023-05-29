<?php

namespace core;

abstract class BaseController
{
  private $twig;

  public function __construct()
  {
    $loader = new \Twig\Loader\FilesystemLoader('../views');
    $this->twig = new \Twig\Environment($loader);
    $this->twig->addGlobal('session', $_SESSION); // + dans html.twig 
    // twig n'a pas acces à $_session on add une super globale addGlobale pour avoir la session 
  }

  public function render($name, $context = [])
  {
    echo $this->twig->render($name, $context);
  }

  protected function denyIfNotAdmin()
  {
    if (empty($_SESSION['login']['Is_Admin'])) { // ou $_SESSION['login']->Is_AdmiN;
      if (!str_contains($_SERVER['REQUEST_URI'], '/api/')) {
        die('Vous n\'avez pas les autorisations d\'accès');
      }
    }
  }

  protected function denyIfNotExists()
  {
    if (empty($_SESSION['login'])) {
      die('Vos identifiants ne sont pas reconnus. Vérifiez votre saisie ou contactez l\'administrateur.');
    }
  }
}
