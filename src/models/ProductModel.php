<?php

namespace src\models;

use core\BaseModel;
use PDO;

//REQUETES SQL POUR PRODUITS

class ProductModel extends BaseModel
{
  public $table = 'products';
  private $id;
  private $category;
  private $designation;
  private $prix;
  private $stock;

  // public function __construct($designation, $prix, $stock){
  // $this->designation = $designation;
  // $this->prix = $prix;
  // $this->stock = $stock;
  // }

  /**
   * Get the value of id
   */
  public function getId()
  {
    return $this->id;
  }

  //! ajoute de nouveaux produit dans la base de données
  public function add_product($data)
  {
    $sql = 'INSERT INTO products (Designation, Prix, Stock, Statut, imgSrc, imgAlt, Categorie) VALUES (:Designation, :Prix, :Stock, :Statut, :imgSrc, :imgAlt, :Categorie)';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':Designation', $data['designation']);
    $query->bindParam(':Prix', $data['prix']);
    $query->bindParam(':Stock', $data['stock']);
    $query->bindParam(':Statut', $data['statut']);
    $query->bindParam(':imgSrc', $data['imgSrc']);
    $query->bindParam(':imgAlt', $data['imgAlt']);
    $query->bindParam(':Categorie', $data['categorie']);
    $query->execute();
  }

  public function changeStock($id, $qty)
  {
    $condition = '';
    if ($qty < 0) {
      $condition = ' AND Stock > 0';
    }
    $sql = 'UPDATE ' . $this->table . ' SET Stock = Stock + ' . $qty . ' WHERE id = :id' . $condition;
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
  }


  public function insertChangeToDb($id, $designation, $prix, $statut, $imgSrc, $imgAlt, $categorie)
  {
    $sql = 'UPDATE ' . $this->table . ' SET Designation = "' . $designation . '", Prix = ' . $prix . ', Statut =' . $statut . ', imgSrc= "' . $imgSrc . '", imgAlt= "' . $imgAlt . '", Categorie= "' . $categorie . '" WHERE id = :id ';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
  }

  //! JSON ________________________________________________________________
  //! recupère les produits par catégorie
  public function getAllByCategory($category)
  {
    $sql = 'SELECT * FROM ' . $this->table . ' WHERE Categorie= :Categorie';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':Categorie', $category);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function updateProductRegardingOrder($productId, $qty)
  {

    $sql = 'UPDATE ' . $this->table . ' SET Stock = Stock - ' . $qty . ' WHERE id = :id AND Stock >= :qty';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $productId);
    $query->bindParam(':qty', $qty);
    $query->execute();
  }

  public function calculateSumCart($arrayId)
  {
    $in = rtrim(str_repeat('?,', count($arrayId)), ',');
    $sql = "SELECT SUM(Prix) FROM products WHERE id in ($in)";

    $query = $this->_connexion->prepare($sql);
    //todo $query->execute(avec Parametre du tableau) == bindParam('id', $arrayId);
    $query->execute($arrayId);
    $sumCart = $query->fetch();
    return floatval($sumCart[0]);
  }
}

// JS
// "dfgfdg" + oui
// 'dfgdfg' + non 
// `chaine ${dfd}`

// PHP
// "dfkdf $er"
// 'dfdfg' .$ofo 

  // public function insertChangetoDb(){
  //   $sql = 'UPDATE ' . $this->table . 'SET ';
  //   // quelle(s) colonne(s) mettre à jour
  //   foreach($this->columns as $column=>$value){
  //     $sql .= '$column = :$column,';
  //     $sql .= '= $value = :$value';
  //   }
  //   $sql= rtrim($sql, ',');
  //   $sql .= 'WHERE id= :id';

  //   $query= $this->_connexion->prepare($sql);
  //   $query->bindValue(':id', id);

  // }
