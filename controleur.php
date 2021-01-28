<?php
require_once 'models/Films.class.php';
require_once 'models/Individus.class.php';
require_once 'models/Genres.class.php';
include 'vendor/autoload.php';
include 'connect.php';
$films = new Films;
$reals = new Individus;
$genres = new Genres;
// le dossier ou on trouve les templates
$loader = new Twig_Loader_Filesystem('templates');
// initialiser l'environement Twig
$twig = new Twig_Environment($loader);

include 'controllers.php';
// on lit une action en parametre
// par defaut, 'list'
$action = $_GET['action'] ?? 'list';
$action_tmp = explode("~", $action); //Lire l'action, si un ~ est présent : demande de tri
$tri = null;
if (sizeof($action_tmp) > 1)
{
  $action = $action_tmp[0];
  $tri = $action_tmp[1];
}

$action_tmp = explode("%", $action); //Lire l'action, si un % est présent : demande de suppression d'un acteur ou d'un genre dans détail_film
$code_suppr = null;
if (sizeof($action_tmp) > 1)
{
  $action = $action_tmp[0];
  $code_suppr = $action_tmp[1];
}
switch ($action)
{
    case "detail":
        detail_films($films, $reals, $genres, $twig, $_GET['code_film']);
        break;

    case "detail_add_genre":
        add_film_genre($films, $_GET['code_film'], $_GET['ajout_genre']);
        detail_films($films, $reals, $genres, $twig, $_GET['code_film']);
        break;

    case "detail_add_acteur":
        add_film_acteur($films, $_GET['code_film'], $_GET['ajout_acteur']);
        detail_films($films, $reals, $genres, $twig, $_GET['code_film']);
        break;

    case "detail_suppr_genre":
        suppr_film_genre($films, $_GET['code_film'], $code_suppr);
        detail_films($films, $reals, $genres, $twig, $_GET['code_film']);
        break;

    case "detail_suppr_acteur":
        suppr_film_acteur($films, $_GET['code_film'], $code_suppr);
        detail_films($films, $reals, $genres, $twig, $_GET['code_film']);
        break;

    case "suppr":
       if (suppr($films, $_GET['code_film']))
            $message = "Film supprimé avec succès !";
       else $message = "Problème de suppression.";
       list_films($reals, $films,$twig,$message, $tri);
       break;

   case "suppr_real":
      if (suppr($reals, $_GET['code_indiv']))
           $message = "Réalisateur supprimé avec succès !";
      else $message = "Problème de suppression.";
      list_indiv($reals, $twig, $tri, $message);
      break;

    case "suppr_genre":
       if (suppr($genres, $_GET['code_genre']))
            $message = "Genre supprimé avec succès !";
       else $message = "Problème de suppression.";
       list_genres($genres, $twig, $tri, $message);
       break;

    case "patch":
       if (!empty($_GET['code_film']) && !empty($_GET['titre_francais']) && !empty($_GET['realisateur']))
		     $res = patch_film($films,$_GET['code_film'],$_GET['titre_francais'], $_GET['pays'],$_GET['date'] ,$_GET['duree'], $_GET['realisateur'], $_GET['couleur'], $_GET['image']);
         if (!empty($res))
            $message = "Film modifié avec succès !";
         else
            $message = "Problème de modification.";
        list_films($reals, $films,$twig,$message, $tri);
        break;

    case "modif_real":
       if (!empty($_GET['code_indiv']) && !empty($_GET['nom']) && !empty($_GET['prenom']) && !empty($_GET['nationalite']) && !empty($_GET['date_naiss']) && !empty($_GET['date_mort']))
		     $res = patch_real($reals,$_GET['code_indiv'],$_GET['nom'], $_GET['prenom'],$_GET['nationalite'] ,$_GET['date_naiss'], $_GET['date_mort']);
         if (!empty($res))
            $message = "Réalisateur modifié avec succès !";
         else
            $message = "Problème de modification.";
        list_indiv($reals, $twig, $tri, $message);
        break;

	  case "add":
        if (add_film($films, $_GET['titre_original'], $_GET['titre_francais'], $_GET['pays'], $_GET['date'], $_GET['duree'], $_GET['couleur'], $_GET['lreal'], $_GET['image']))
  		       $message = "Film ajouté avec succès !";
  	    else $message = "Problème d'ajout.";
        list_films($reals, $films,$twig,$message, $tri);
        break;

    case "add_real":
        if (add_real($reals, $_GET['nom'], $_GET['prenom'], $_GET['nationalite'], $_GET['date_naiss'], $_GET['date_mort']))
  		       $message = "Réalisateur ajouté avec succès !";
  	    else $message = "Problème d'ajout d'acteur/réalisateur.";
        list_indiv($reals, $twig, $tri, $message);
        break;

    case "add_genre":
        if (add_genre($genres, $_GET['nom_genre']))
             $message = "Genre ajouté avec succès !";
        else $message = "Problème d'ajout du genre.";
        list_genres($genres, $twig, $tri, $message);
        break;


    case "list_recherche":
        if (empty($_GET['titre']) && empty($_GET['real']) && empty($_GET['date_recherche']))
          $message = "Recherche vide ou invalide.";
        else
          $message = "Voici la liste des films recherchés :";
          list_films($reals, $films, $twig, $message, $tri);
        break;

    case "detail_real":
        detail_indiv($reals, $twig, $_GET['code_indiv']);
        break;

    case "list_indiv":
        list_indiv($reals, $twig, $tri);
        break;

    case "list_genre":
        list_genres($genres, $twig, $tri);
        break;

    case "list_film":
        list_films($reals, $films, $twig, $message, $tri);
        break;

    default:
        list_films($reals, $films, $twig, '' ,$tri);
}

//header("refresh:4;url=controleur.php");
?>
