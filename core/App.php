<?php

namespace core;


use src\controllers\HomeController;
use src\controllers\EmployeeController;
use src\controllers\ProductController;
use src\controllers\OrderController;

// récupérez l’URI (cf echo var_dump($_SERVER); sur index.php)

class App
{
    public function __construct()
    {
        session_start(); // demarre la session et recupere la session 
    }

    public function run()
    {
        //todo LOGIN LOGOUT____________________________________________________________________________
        $uri = strtok($_SERVER['REQUEST_URI'], '?');

        if ($uri == '/' || $uri == '/index.php') {
            $controller = new HomeController(); // HomeController est une class qui va gerer une entité. 
            $controller->index(); //HomeController aura differentes methodes ici index()
        } elseif ($uri == '/home/auth') { // authenticated // autorisation
            $controller = new HomeController();
            $controller->authentification();
        } elseif ($uri == '/home') { // authenticated // autorisation
            $controller = new HomeController();
            $controller->welcom();
        } elseif ($uri == '/logout') {
            $controller = new HomeController();
            $controller->logout();
        }

        //todo PRODUCTS____________________________________________________________________________
        elseif ($uri == '/products') {
            $controller = new ProductController();
            $controller->displayList();
        } elseif ($uri == '/products/addTen' && isset($_GET['id'])) {
            $controller = new ProductController();
            $controller->addStock();
        } elseif ($uri == '/products/update') {
            $controller = new ProductController();
            $controller->editProduct();
        } elseif ($uri == '/products/updateDatabase' && isset($_POST['id'])) {
            $controller = new ProductController();
            $controller->updateDatabaseProducts();
            // affiche les nouveaux produits 
        } elseif ($uri == '/products/insertProduct') {
            $controller = new ProductController();
            $controller->insertProduct();
            // ! JSON API_________________________________________________
        } elseif ($uri == '/api/products') {
            $controller = new ProductController();
            $controller->displayAllProducts();
        } elseif ($uri == '/api/products/consume' && isset($_GET['id'])) {
            $controller = new ProductController();
            $controller->consumeOne($_GET['id']);
        } elseif ($uri == '/api/products/category') {
            $controller = new ProductController();
            $controller->displayProductsByCategory();
        }

        //todo EMPLOYEES____________________________________________________________________________
        elseif ($uri == '/employees') {
            $controller = new EmployeeController();
            $controller->displayAllEmployees();
        } elseif ($uri == '/employees/update') {
            $controller = new EmployeeController();
            $controller->editEmployee();
        } elseif ($uri == '/employees/updateDatabase' && isset($_POST['id'])) {
            $controller = new EmployeeController();
            $controller->updateDatabaseEmploy();
        } elseif ($uri == '/employees/orders' && isset($_GET['id'])) {
            $controller = new EmployeeController();
            $controller->displayOrdersByemployee();
        }
        // ! JSON API_________________________________________________
        elseif ($uri == '/api/employee/one' && isset($_GET['id'])) {
            $controller = new EmployeeController();
            $controller->displayDetailsEmployee();
        }
        //!$_POST____________________________
        elseif ($uri == '/api/orders/place') {
            $controller = new OrderController();
            $controller->placeOrder();
        }

        //todo ORDERS____________________________________________________________________________
        elseif ($uri == '/orders') {
            $controller = new OrderController();
            $controller->displayAllOrders();
        } elseif ($uri == '/orders/details') {
            $controller = new OrderController();
            $controller->displayDetailsOrderByEmploye();
        }
        // ! JSON API_________________________________________________
        elseif ($uri == '/api/order/history' && isset($_GET['id'])) {
            $controller = new EmployeeController();
            $controller->displayOrdersHistory();
        }
    }
}
