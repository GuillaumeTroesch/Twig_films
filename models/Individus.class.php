<?php
class Individus{
  private $pdo;

  function __construct()
  {
    $this->pdo = connect_db();
  }

  function liste_individus($parametre_tri)
  {
    $res = Array();
    $sql = "SELECT * from individus where 1";
    if (!empty($_GET["nom_indiv"]))
      $sql .= " and nom like '%$_GET[nom_indiv]%'";
    if (!empty($_GET["prenom_indiv"]))
      $sql .= " and prenom like '%$_GET[prenom_indiv]%'";

    if (!empty($parametre_tri))
      $sql .= " ORDER BY " . $parametre_tri;

    foreach ($this->pdo->query($sql) as $row)
    {
      $res[] = $row;
    }
    return $res;
  }

  function ajoute($nom, $prenom, $nationalite, $date_naiss, $date_mort)
  {
    $sql = "INSERT INTO individus VALUES(0,:nom,:prenom,:nationalite,:date_naiss,:date_mort)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':nationalite', $nationalite);
    $stmt->bindParam(':date_naiss', $date_naiss);
    $stmt->bindParam(':date_mort', $date_mort);
    return $stmt->execute();
  }

  function suppr($id)
  {
    $sql = "DELETE from individus where code_indiv=:code_indiv";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_indiv', $id);
    $stmt->execute();

    $sql = "DELETE from acteurs
     where code_indiv=:code_indiv";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_indiv', $id);
    $stmt->execute();

    $sql = "DELETE from films where realisateur=:code_indiv";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_indiv', $id);

    return $stmt->execute();
  }

  function maj($id, $nom, $prenom, $nationalite, $date_naiss, $date_mort)
  {
    $sql = "UPDATE individus SET nom = :nom, prenom = :prenom, nationalite = :nationalite, date_naiss = :date_naiss, date_mort = :date_mort WHERE code_indiv=:id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":nom", $nom);
    $stmt->bindParam(":prenom", $prenom);
    $stmt->bindParam(":nationalite", $nationalite);
    $stmt->bindParam(":date_naiss", $date_naiss);
    $stmt->bindParam(":date_mort", $date_mort);
    return $stmt->execute();
  }

  function get_real_by_id($id)
  {
    $connexion=connect_db();
    $sql="SELECT * from individus where code_indiv=:code";
    $stmt=$connexion->prepare($sql);
    $stmt->bindParam(':code',$id);
    $stmt->execute();
    return $stmt->fetch();
  }
}




?>
