<?php
class Dbh {
  protected function connectDB() {
    try {
      $username = 'root';
      $password = 'Asare4ster...';
      $dbn = new PDO('mysql:host=localhost;dbname=unifi', $username, $password);
      return $dbn;
    } catch (PDOException $e) {
      var_dump($e);
    }
  }
}
