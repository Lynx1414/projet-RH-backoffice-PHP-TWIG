<?php

namespace core\libs;

class Utilitaire
{

  public static function validateMail($mail){
    return filter_var($mail, FILTER_VALIDATE_EMAIL); // return boolean
    }
  
 

  public static function verifyPassword($password){
    // password_verify();
  }
}
