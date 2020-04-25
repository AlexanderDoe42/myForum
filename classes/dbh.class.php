<?php

class Dbh {
  private $host     = "localhost";
  private $user     = "user";
  private $pwd      = "user";
  private $dbName   = "myForum";
  private $charset  = "utf8";

  protected function connect() {
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName .
           ';charset=' . $this->charset;
    $pdo = new PDO($dsn, $this->user, $this->pwd);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
  }
  protected function disconnect($arg) {

  }
  public static function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

}

 ?>
