<?php

/*
script destiné à répondre aux requêtes AJAX de l'inventaire : obtenir les fichiers* associés à un certain équipement
    (*pour les photos : données en base64 + type mime, pour les PDF : lien d'accès)
renvoie les données en JSON, ou rien si aucun fichier
nécessite le random id (rid) de l'équipement en paramètre
*/

// ---------------------------------------------
// chemin racine des fichiers, à changer en cas de déplacement ou renommages
$racine = "/home/batinv/";
// ---------------------------------------------

require_once("init.php");

// extraction du random id de l'équipement en url
$rid = $_REQUEST["rid"];

// obtention de l'id (clé primaire) à partir du random id
$requete_p = $batinv_db->prepare("SELECT id FROM equipements WHERE random_id = :rid");
$requete_p->bindValue(":rid",$rid);
$requete_p->execute();
$id = $requete_p->fetch();

if ($id != ""){
    // extraction des infos des fichiers associés à l'équipement
    $requete = "SELECT equipement, chemin, type, random_id FROM fichiers WHERE equipement = '$id[0]'";
    $reponse = $batinv_db->query($requete);
    $fichiers_infos = $reponse->fetchAll();

    if ($fichiers_infos != ""){
        $tableau_donnees = array();
        $images_donnees = array();
        $pdf_donnees = array();
        foreach ($fichiers_infos as $fichier){
            if ($fichier["type"] == "image"){
                $image = encode_image($fichier["chemin"], $racine, $fichier["random_id"]);
                array_push($images_donnees,$image);
            }else{
                $pdf = array();
                $chemin_blocs = explode("/",$fichier["chemin"]);
                $pdf["nom"] = end($chemin_blocs);
                $pdf["rid"] = $fichier["random_id"];
                array_push($pdf_donnees,$pdf);
            }
        }
        $tableau_donnees["images"] = $images_donnees;
        $tableau_donnees["pdf"] = $pdf_donnees;

        $donnees_json = json_encode($tableau_donnees);
        
        echo $donnees_json;
    }
}

// fonction pour encoder les images en base64 pour inclusion directe dans la sortie texte
function encode_image($chemin_relatif, $racine, $rid){
    $image = array();
    $chemin_complet = $racine.$chemin_relatif;
    $metad = exif_read_data($chemin_complet);

    $taille = getimagesize($chemin_complet);
    $new_width = 150;
    $new_height = intval(intval($taille[1]) / (intval($taille[0]) / $new_width));
    $img = imagecreatefromjpeg($chemin_complet);

    ob_start();
    imagejpeg(imagescale($img, $new_width, $new_height));
    $fichier_encode = base64_encode(ob_get_clean());

    $image["mimetype"] = $metad["MimeType"];
    $image["data"] = $fichier_encode;
    $image["rid"] = $rid;
    return $image;
}
