<?php

function list_films($reals, $films, $twig, $message, $tri){
  $films = $films->liste($tri);
  $template = $twig->loadTemplate('film.twig.html');
  $titre="Liste des films";
  $real = $reals->liste_individus("nom");
  $recherche = array();
  if (isset($_GET["titre"]) && isset ($_GET["real"]) && isset($_GET["date_recherche"]))
    $recherche = array("titre" => $_GET["titre"], "real" => $_GET["real"], "date_recherche" => $_GET["date_recherche"]);
  if (empty($films))
    $message = "Aucun film ne correspond à votre recherche.";
  echo $template->render(array(
            'titre' => $titre,
            'films' => $films,
            'message' => $message,
            'reals'=> $real,
            'recherche' => $recherche,
            ));
}

function list_indiv($cont, $twig, $tri, $message='')
{
  $individus = $cont->liste_individus($tri);
  $template = $twig->loadTemplate('individu.twig.html');
  $titre="Détails de acteur/réalisateur";
  $recherche = array();
  if (isset($_GET["nom_indiv"]) && isset ($_GET["prenom_indiv"]))
    $recherche = array("nom_indiv" => $_GET["nom_indiv"], "prenom_indiv" => $_GET["prenom_indiv"]);
  echo $template->render(array(
            'titre' => $titre,
            'message' => $message,
            'individus' => $individus,
            'recherche' => $recherche,
            ));
}

function list_genres($cont, $twig, $tri, $message='')
{
  $genres = $cont->liste_genres($tri);
  $template = $twig->loadTemplate('genre.twig.html');
  $titre="Détails des genres";
  echo $template->render(array(
            'titre' => $titre,
            'message' => $message,
            'genres' => $genres
            ));
}

function suppr($cont, $code){
  return ($cont->suppr($code));
}

function patch_film($cont, $code_film, $titre_francais, $pays, $date, $duree, $realisateur, $couleur, $image){
  return ($cont->maj($code_film, $titre_francais, $pays, $date, $duree, $realisateur, $couleur, $image));
}

function patch_real($cont, $id, $nom, $prenom, $nationalite, $date_naiss, $date_mort){
  return ($cont->maj($id, $nom, $prenom, $nationalite, $date_naiss, $date_mort));
}

function add_film($cont, $titre_original, $titre_francais, $pays, $date, $duree, $couleur, $realisateur, $image){
  return ($cont->ajoute( $titre_original, $titre_francais, $pays, $date, $duree, $couleur, $realisateur, $image));
}

function add_real($cont, $nom, $prenom, $nationalite, $date_naiss, $date_mort){
  return ($cont->ajoute($nom, $prenom, $nationalite, $date_naiss, $date_mort));
}

function add_genre($cont, $nom){
  return ($cont->ajoute($nom));
}

function detail_films($films, $reals, $genres, $twig, $code, $message=''){
  $film = $films->get_film_by_code($code);
  $template = $twig->loadTemplate('detail_film.twig.html');
  $titre="Détails";
  $real = $reals->liste_individus("nom");
  $l_genres = $films->getGenres($code);
  $acteurs = $films->getActeurs($code);
  $all_genres = $genres->liste_genres("nom_genre");
  echo $template->render(array(
            'titre' => $titre,
            'film' => $film,
            'message' => $message,
            'reals' => $real,
            'genres' => $l_genres,
            'acteurs' => $acteurs,
            'all_genres' => $all_genres
            ));
}

function detail_indiv($reals, $twig, $code, $message='')
{
  $real = $reals->get_real_by_id($code);
  $template = $twig->loadTemplate('detail_real.twig.html');
  $titre="Détails de réalisateur";

  echo $template->render(array(
            'titre' => $titre,
            'message' => $message,
            'real' => $real
            ));
}

function suppr_film_genre($films, $code_film, $code_genre){
  return ($films->suppr_genre_film($code_film, $code_genre));
}

function suppr_film_acteur($films, $ref_code_film, $ref_code_acteur){
  return ($films->suppr_acteur_film($ref_code_film, $ref_code_acteur));
}

function add_film_genre($films, $code_film, $code_genre){
  return ($films->ajoute_genre_film($code_film, $code_genre));
}

function add_film_acteur($films, $ref_code_film, $ref_code_acteur){
  return ($films->ajoute_acteur_film($ref_code_film, $ref_code_acteur));
}

?>
