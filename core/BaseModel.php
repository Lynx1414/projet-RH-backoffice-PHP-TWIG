<?php

namespace core;

use PDO;
use PDOException;

abstract class BaseModel
{
  private $host = 'localhost';
  private $db_name = 'projet_rh_onlinesnackies';
  private $username = 'root';
  private $password = '';
  protected $_connexion;
  protected $table;
  protected $ID = 'id';
  // protected $columns;
  // protected $value;
  // protected $where;

  public function __construct()
  {
    $this->getConnexion();
  }
  public function getConnexion()
  {
    // On supprime la connexion précédente
    $this->_connexion = null;
    // On essaie de se connecter à la base avec PDO
    try {
      $this->_connexion = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->_connexion->exec('set names utf8');
      $this->_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
      echo "Error: " . $exception->getMessage();
    }
  }

  public function getAll()
  {
    $sql = 'SELECT * FROM ' . $this->table;
    $query = $this->_connexion->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getOne($id)
  {
    $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
    $query = $this->_connexion->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
  }

  // public function update($table, $columns, $where){
  //   $sql = 'UPDATE ' . $this->table . ' SET ';
  //   foreach ($this->columns as $column => $value) {
  //     $sql .= '$column = :$columns';
  //     $sql .= '= $value = :$value';
  //   }
  // }
}
