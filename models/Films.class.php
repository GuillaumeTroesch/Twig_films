<?php
class Films{
  private $pdo;

  function __construct(){
    $this->pdo = connect_db();
  }

  function liste($parametre){
    $res = Array();
    $sql = "SELECT code_film, titre_original, titre_francais, pays, date ,duree, couleur, CONCAT(prenom, nom) realisateur, code_indiv from films INNER JOIN individus ON films.realisateur=individus.code_indiv WHERE 1";
    if (!empty($_GET["titre"]))
      $sql .= " and titre_original like '%$_GET[titre]%'";
    if (!empty($_GET["real"]))
      $sql .= " and nom like '%$_GET[real]%'";
    if (!empty($_GET["date_recherche"]))
      $sql .= " and date = $_GET[date_recherche]";

      if (!empty($parametre))
        $sql .= " ORDER BY " . $parametre;


    foreach ($this->pdo->query($sql) as $row)
    {
      $res[] = $row;
    }
    return $res;
  }

  function getGenres($code_film){
    $res = Array();
    $sql = "SELECT distinct code_genre, nom_genre from genres natural join classification natural join films where code_film = ".$code_film;
    foreach ($this->pdo->query($sql) as $row)
    {
      $res[] = $row;
    }
    return $res;

  }

  function getActeurs($code_film){
    $res = Array();
    $sql = "SELECT distinct individus.code_indiv, individus.nom, individus.prenom from individus natural join films natural join acteurs where films.code_film = ".$code_film." and films.code_film = acteurs.ref_code_film and individus.code_indiv = acteurs.ref_code_acteur";
    foreach ($this->pdo->query($sql) as $row)
    {
      $res[] = $row;
    }
    return $res;

  }


  function ajoute($titre_original,$titre_francais,$pays,$date,$duree,$couleur,$realisateur,$image){
    $sql = "INSERT INTO films VALUES(0,:titre_original,:titre_francais,:pays,:date,:duree,:couleur,:realisateur,:image)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':titre_original', $titre_original);
    $stmt->bindParam(':titre_francais', $titre_francais);
    $stmt->bindParam(':pays', $pays);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':duree', $duree);
    $stmt->bindParam(':couleur', $couleur);
    $stmt->bindParam(':realisateur', $realisateur);
    $stmt->bindParam(':image', $image);
    return $stmt->execute();
  }

  function ajoute_acteur_film($ref_code_film, $ref_code_acteur)
  {
    $sql = "INSERT INTO acteurs VALUES(:ref_code_film, :ref_code_acteur)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':ref_code_film', $ref_code_film);
    $stmt->bindParam(':ref_code_acteur', $ref_code_acteur);
    return $stmt->execute();
  }

  function ajoute_genre_film($code_film, $code_genre)
  {
    $sql = "INSERT INTO classification VALUES(:code_film, :code_genre)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_film', $code_film);
    $stmt->bindParam(':code_genre', $code_genre);
    return $stmt->execute();
  }

  function suppr($id){
    $sql = "DELETE from films where CODE_FILM=:code_film";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_film', $id);
    return $stmt->execute();
  }

  function suppr_acteur_film($ref_code_film, $ref_code_acteur)
  {
    $sql = "DELETE from acteurs where ref_code_film=:ref_code_film and ref_code_acteur=:ref_code_acteur";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':ref_code_film', $ref_code_film);
    $stmt->bindParam(':ref_code_acteur', $ref_code_acteur);
    return $stmt->execute();
  }

  function suppr_genre_film($code_film, $code_genre)
  {
    $sql = "DELETE from classification where code_film=:code_film and code_genre=:code_genre";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':code_film', $code_film);
    $stmt->bindParam(':code_genre', $code_genre);
    return $stmt->execute();
  }

  function get_film_by_code($code){
    $connexion=connect_db();
    $sql="SELECT * from films where code_film=:code";
    $stmt=$connexion->prepare($sql);
    $stmt->bindParam(':code',$code,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
  }

  function maj($code_film,$titre_francais,$pays,$date,$duree, $realisateur, $couleur,$image){
    $sql = "UPDATE films SET titre_francais = :titre_francais, pays = :pays, date = :_date, realisateur=:realisateur, duree = :duree, couleur = :couleur, image = :image where code_film = :code_film;";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(":titre_francais", $titre_francais);
    $stmt->bindParam(":pays", $pays);
    $stmt->bindParam(":realisateur", $realisateur);
    $stmt->bindParam(":_date", $date);
    $stmt->bindParam(":duree", $duree);
    $stmt->bindParam(":couleur", $couleur);
    $stmt->bindParam(":image", $image);
    $stmt->bindParam(":code_film", $code_film);
    return $stmt->execute();
  }
}




?>
