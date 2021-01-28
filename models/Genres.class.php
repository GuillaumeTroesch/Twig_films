<?php
class Genres{
  private $pdo;

  function __construct()
  {
    $this->pdo = connect_db();
  }

  function liste_genres($parametre)
  {
    $res = Array();

    $sql = "SELECT code_genre, nom_genre from genres";
    if (!empty($parametre)){
      $sql .= " ORDER BY ".$parametre;
    }
    foreach ($this->pdo->query($sql) as $row)
    {
      $res[] = $row;
    }
    return $res;
  }



  function ajoute($nom)
  {
    $sql = "INSERT INTO genres VALUES(0,:nom)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    return $stmt->execute();
  }

  function suppr($id)
  {
    $sql = "DELETE from classification where code_genre=:code_genre";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_genre', $id);
    $stmt->execute();

    $sql = "DELETE from genres where code_genre=:code_genre";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_genre', $id);
    return $stmt->execute();
  }


}




?>
